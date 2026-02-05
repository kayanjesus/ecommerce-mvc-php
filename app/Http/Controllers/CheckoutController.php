<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\Endereco; // Se você tiver um modelo de Endereco para o usuário
use App\Models\Pedido;
use App\Models\Pagamento; // Seu novo modelo Pagamento (antes PagamentoCheckout)
use App\Models\User; // Para notificar o admin
use App\Notifications\NovoPedidoNotification; // Sua notificação
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Notification; // Para enviar notificações
use App\Http\Controllers\EstoqueController;

class CheckoutController extends Controller
{
    // Exibe o resumo do carrinho e pede informações de entrega
    public function showSummary()
    {
        $itens = Cart::getContent();
        if ($itens->isEmpty()) {
            return redirect()->route('home.carrinho')->with('erro', 'Seu carrinho está vazio!');
        }

        $total = Cart::getTotal();
        $user = Auth::user();
        // Você pode carregar o endereço padrão do usuário aqui, se tiver
        $enderecoPadrao = $user->enderecos->first() ?? null; // Assumindo relação hasMany Enderecos no User

        return view('home.checkout-summary', compact('itens', 'total', 'user', 'enderecoPadrao'));
    }

    // Processa o checkout, cria o pedido e gera o Pix
    public function processCheckout(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::getContent();
        $total = Cart::getTotal();

        if ($cartItems->isEmpty()) {
            return redirect()->route('home.carrinho')->with('erro', 'Carrinho vazio.');
        }

        // Validação do endereço de entrega
        $request->validate([
            'rua' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:2',
            'cep' => 'required|string|regex:/^\d{5}-?\d{3}$/', // Formato 00000-000 ou 00000000
            'observacoes' => 'nullable|string|max:500',
        ]);

        try {
            // 1. Criar o Pedido no banco (status 'pendente')
            $pedido = Pedido::create([
                'id_usuario' => $user->id,
                'total' => $total,
                'status' => 'pendente', // Pedido inicial com status pendente
                'endereco_entrega' => json_encode([
                    'rua' => $request->rua,
                    'numero' => $request->numero,
                    'complemento' => $request->complemento,
                    'bairro' => $request->bairro,
                    'cidade' => $request->cidade,
                    'estado' => $request->estado,
                    'cep' => preg_replace('/[^0-9]/', '', $request->cep), // Salva apenas dígitos
                ]),
                'observacoes' => $request->observacoes,
            ]);

            // 2. Adicionar itens do carrinho ao pedido
            foreach ($cartItems as $item) {
                $pedido->itens()->create([
                    'id_produto' => $item->attributes->product_id,
                    'quantidade' => $item->quantity,
                    'preco_unitario' => $item->price,
                    'cor' => Cor::find($item->attributes->cor_id)->nome ?? null, // Assumindo que você tem um modelo Cor e um campo 'nome'
                    'tamanho' => Tamanho::find($item->attributes->tamanho_id)->nome ?? null, // Assumindo modelo Tamanho e 'nome'
                    'variacao' => ($item->attributes->cor_id ? 'Cor: ' . (Cor::find($item->attributes->cor_id)->nome ?? '') : '') .
                        ($item->attributes->tamanho_id ? ' Tamanho: ' . (Tamanho::find($item->attributes->tamanho_id)->nome ?? '') : '')
                ]);
            }

            // 3. Notificar o administrador sobre o novo pedido (mesmo pendente)
            $adminUser = User::where('is_admin', true)->first(); // Adapte para pegar o admin correto
            if ($adminUser) {
                $adminUser->notify(new NovoPedidoNotification($pedido));
            } else {
                Log::warning('Nenhum usuário administrador encontrado para notificação de novo pedido.');
            }

            // 4. Gerar o QR Code Pix com PagSeguro
            $pagSeguroUrl = env('PAGSEGURO_URL');
            $pagSeguroToken = env('PAGSEGURO_TOKEN');

            $customerPhone = preg_replace('/[^0-9]/', '', $user->telefone ?? '11999999999');
            $customerPhoneArea = substr($customerPhone, 0, 2);
            $customerPhoneNumber = substr($customerPhone, 2);

            $body = [
                "reference_id" => "pedido-{$pedido->id_pedido}", // Sua referência para identificar o pedido
                "customer" => [
                    "name" => $user->name,
                    "email" => $user->email,
                    "tax_id" => preg_replace('/[^0-9]/', '', $user->cpf ?? '11111111111'), // Remover caracteres não numéricos
                    "phones" => [
                        [
                            "country" => "55",
                            "area" => $customerPhoneArea,
                            "number" => $customerPhoneNumber,
                            "type" => "MOBILE"
                        ]
                    ]
                ],
                "items" => [],
                "shipping" => [
                    "address" => [
                        "street" => $request->rua,
                        "number" => $request->numero,
                        "complement" => $request->complemento,
                        "locality" => $request->bairro,
                        "city" => $request->cidade,
                        "region" => $request->estado,
                        "region_code" => $request->estado,
                        "country" => "BRA",
                        "postal_code" => preg_replace('/[^0-9]/', '', $request->cep)
                    ]
                ],
                "notification_urls" => [
                    // A URL do seu webhook! Certifique-se de que está acessível publicamente.
                    config('app.url') . '/webhooks/pagseguro'
                ],
                "charges" => [
                    [
                        "amount" => [
                            "value" => (int) round($total * 100), // Valor total em centavos
                            "currency" => "BRL"
                        ],
                        "payment_method" => [
                            "type" => "PIX"
                        ]
                    ]
                ]
            ];

            foreach ($cartItems as $item) {
                $body['items'][] = [
                    "name" => $item->name,
                    "quantity" => $item->quantity,
                    "unit_amount" => (int) round($item->price * 100) // Preço unitário em centavos
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $pagSeguroToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($pagSeguroUrl, $body);

            Log::info('Requisição PagSeguro:', ['body' => $body, 'response' => $response->json()]);

            if ($response->successful()) {
                $pagSeguroResponse = $response->json();

                if (isset($pagSeguroResponse['qr_codes'][0]['links'][0]['href']) && isset($pagSeguroResponse['qr_codes'][0]['text'])) {
                    $qrCodeData = $pagSeguroResponse['qr_codes'][0]['links'][0]['href'];
                    $pixKey = $pagSeguroResponse['qr_codes'][0]['text'];
                    $expirationDate = $pagSeguroResponse['qr_codes'][0]['expiration_date'];
                    $chargeId = $pagSeguroResponse['charges'][0]['id'] ?? null; // ID da cobrança no PagSeguro

                    // 5. Salvar os dados do Pix e criar registro de pagamento
                    Pagamento::create([
                        'id_pedido' => $pedido->id_pedido,
                        'metodo_pagamento' => 'pix',
                        'valor_pago' => $total,
                        'valor_original' => $total, // Supondo que não há desconto aqui
                        'status' => 'pendente', // Status inicial do pagamento
                        'codigo_transacao' => $chargeId, // Armazena o ID da cobrança do PagSeguro
                        'detalhes' => json_encode([
                            'qr_code_link' => $qrCodeData,
                            'pix_key' => $pixKey,
                            'expiration_date' => $expirationDate
                        ])
                    ]);

                    // Limpa o carrinho após o pedido ser criado e o Pix gerado
                    Cart::clear();
                    if (Auth::check()) {
                        \App\Models\Carrinho::where('id_usuario', Auth::id())->delete();
                    }

                    // Armazena dados na sessão para a página de pagamento
                    Session::put([
                        'qrCodeData' => $qrCodeData,
                        'pixKey' => $pixKey,
                        'expirationDate' => $expirationDate,
                        'last_pedido_id' => $pedido->id_pedido,
                    ]);

                    return redirect()->route('pagamento.pagar', ['pedidoId' => $pedido->id_pedido]);

                } else {
                    Log::error('QR Code URL ou texto não encontrado na resposta do PagSeguro.', $pagSeguroResponse);
                    return redirect()->route('pagamento.erro')->with('erro', 'Erro ao gerar Pix: dados incompletos do PagSeguro.');
                }
            } else {
                Log::error('Erro na requisição ao PagSeguro:', ['status' => $response->status(), 'response' => $response->json()]);
                return redirect()->route('pagamento.erro')->with('erro', 'Erro ao processar pagamento: ' . ($response->json()['message'] ?? 'Erro desconhecido.'));
            }

        } catch (\Exception $e) {
            Log::error('Erro ao processar checkout:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('pagamento.erro')->with('erro', 'Erro interno ao processar seu pedido. ' . $e->getMessage());
        }
    }
}