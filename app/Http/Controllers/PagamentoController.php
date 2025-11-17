<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Endereco, Pedido, PedidoItem, PagamentoCheckout, Cupom, User};
use App\Notifications\NovoPedidoNotification;
use Illuminate\Support\Facades\{Auth, Http, Log, DB};
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Session;
use App\Models\Tamanho;
use App\Models\Cor;
use Carbon\Carbon;

class PagamentoController extends Controller
{
    public function cep(Request $request)
    {
        $userId = Auth::id();
        $tipoCheckout = $request->input('tipo_checkout', 'todos');

        $itens = collect([]);
        $total = 0;

        if ($tipoCheckout === 'selecionados') {
            $selectedItems = json_decode($request->input('selected_items', '[]'), true);

            if (empty($selectedItems)) {
                return redirect()->back()->with('erro', 'Selecione pelo menos um item para finalizar o pedido');
            }

            $itens = \Cart::session($userId)->getContent()->filter(function ($item) use ($selectedItems) {
                return in_array($item->id, $selectedItems);
            });

            Log::debug('Itens do carrinho (selecionados):', ['itens' => $itens->toArray()]);

            session([
                'checkout_type' => 'selecionados',
                'selected_items' => $selectedItems,
                'itens_checkout' => $itens->toArray()
            ]);
        } else {
            $itens = \Cart::session($userId)->getContent();

            Log::debug('Itens do carrinho (todos):', ['itens' => $itens->toArray()]);

            session([
                'checkout_type' => 'todos',
                'itens_checkout' => $itens->toArray()
            ]);
        }

        foreach ($itens as $item) {
            if (isset($item->attributes['cor_id'])) {
                $item->cor = Cor::find($item->attributes['cor_id']);
            }
            if (isset($item->attributes['tamanho_id'])) {
                $item->tamanho = Tamanho::find($item->attributes['tamanho_id']);
            }
        }

        if (!$itens->isEmpty()) {
            $total = $itens->sum(function ($item) {
                return $item->price * $item->quantity;
            });
        }

        return view('home.pagamento.cep', compact('itens', 'total'));
    }


    public function buscarCep(Request $request)
    {
        $request->validate(['cep' => 'required|string|min:8|max:9']);

        try {
            $cep = preg_replace('/[^0-9]/', '', $request->cep);

            if (strlen($cep) !== 8) {
                return response()->json([
                    'error' => 'CEP deve conter 8 dígitos'
                ], 400);
            }

            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

            if ($response->failed() || isset($response->json()['erro'])) {
                return response()->json([
                    'error' => 'CEP não encontrado. Por favor, verifique e tente novamente.'
                ], 404);
            }

            $data = $response->json();

            if (empty($data['logradouro']) || empty($data['bairro']) || empty($data['localidade']) || empty($data['uf'])) {
                return response()->json([
                    'error' => 'Dados do CEP incompletos. Por favor, preencha manualmente.'
                ], 422);
            }

            return response()->json([
                'cep_data' => [
                    'cep' => $data['cep'] ?? '',
                    'rua' => $data['logradouro'] ?? '',
                    'bairro' => $data['bairro'] ?? '',
                    'cidade' => $data['localidade'] ?? '',
                    'estado' => $data['uf'] ?? ''
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Erro ao buscar CEP: " . $e->getMessage());
            return response()->json([
                'error' => 'Erro ao consultar o serviço de CEP. Por favor, tente novamente mais tarde.'
            ], 500);
        }
    }

    public function salvarEndereco(Request $request)
    {
        $request->validate([
            'cep' => 'required|string|size:9',
            'rua' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|size:2',
            'complemento' => 'nullable|string|max:255'
        ]);

        $endereco = [
            'cep' => $request->cep,
            'rua' => $request->rua,
            'bairro' => $request->bairro,
            'numero' => $request->numero,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
            'complemento' => $request->complemento,
            'id_usuario' => Auth::id()
        ];

        session(['endereco_entrega' => $endereco]);

        Endereco::updateOrCreate(
            ['id_usuario' => Auth::id()],
            $endereco
        );

        return redirect()->route('pagamento.revisao');
    }

    private function buscarDadosCep($cep)
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) != 8) {
            return null;
        }

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get("https://viacep.com.br/ws/{$cep}/json/");
            $data = json_decode($response->getBody(), true);

            if (isset($data['erro']) || empty($data['logradouro'])) {
                return null;
            }

            return [
                'cep' => $data['cep'] ?? '',
                'rua' => $data['logradouro'] ?? '',
                'bairro' => $data['bairro'] ?? '',
                'cidade' => $data['localidade'] ?? '',
                'estado' => $data['uf'] ?? ''
            ];
        } catch (\Exception $e) {
            \Log::error("Erro ao buscar CEP: " . $e->getMessage());
            return null;
        }
    }

    public function editarEndereco()
    {
        if (!session()->has('endereco_entrega')) {
            return redirect()->route('pagamento.cep');
        }

        if (!session()->has('itens_checkout')) {
            return redirect()->route('pagamento.cep')->with('erro', 'Sessão expirada. Por favor, selecione os itens novamente.');
        }

        $endereco = session('endereco_entrega');
        $itens = session('itens_checkout');
        $total = collect($itens)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('home.pagamento.editar-endereco', compact('endereco', 'itens', 'total'));
    }

    public function atualizarEndereco(Request $request)
    {
        $request->validate([
            'cep' => 'required|string|size:9',
            'rua' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|size:2',
            'complemento' => 'nullable|string|max:255'
        ]);

        $endereco = [
            'cep' => $request->cep,
            'rua' => $request->rua,
            'bairro' => $request->bairro,
            'numero' => $request->numero,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
            'complemento' => $request->complemento,
            'id_usuario' => Auth::id()
        ];

        session(['endereco_entrega' => $endereco]);

        Endereco::updateOrCreate(
            ['id_usuario' => Auth::id()],
            $endereco
        );

        if (session()->has('forma_pagamento')) {
            return redirect()->route('pagamento.revisao');
        } else {
            if (!session()->has('itens_checkout')) {
                return redirect()->route('pagamento.cep')->with('erro', 'Sessão expirada. Por favor, selecione os itens novamente.');
            }
            return redirect()->route('pagamento.forma-pagamento');
        }
    }


    public function formaPagamento()
    {
        if (!session()->has('endereco_entrega')) {
            return redirect()->route('pagamento.cep')->with('erro', 'Por favor, informe o endereço de entrega');
        }

        if (!session()->has('itens_checkout')) {
            return redirect()->route('pagamento.cep')->with('erro', 'Sessão expirada. Por favor, selecione os itens novamente.');
        }

        $itens = collect(session('itens_checkout'));

        $total = $itens->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('home.pagamento.formapagamento', compact('itens', 'total'));
    }

    public function salvarFormaPagamento(Request $request)
    {
        $request->validate([
            'metodo_pagamento' => 'required|in:pix,cartao,boleto'
        ]);

        session(['forma_pagamento' => $request->metodo_pagamento]);

        return redirect()->route('pagamento.revisao');
    }


    const REGIAO_SUL = ['PR', 'SC', 'RS'];
    const REGIAO_SUDESTE = ['MG', 'SP', 'RJ', 'ES'];
    const VALOR_FRETE_PADRAO = 25.00;

    protected function getRegiaoPorUf(string $uf): ?string
    {
        $uf = strtoupper($uf);
        if (in_array($uf, self::REGIAO_SUL)) {
            return 'sul';
        }
        if (in_array($uf, self::REGIAO_SUDESTE)) {
            return 'sudeste';
        }
        return 'outras';
    }

    protected function calcularValorFrete(float $subtotal, string $uf): float
    {
        $regiao = $this->getRegiaoPorUf($uf);

        if (in_array($regiao, ['sul', 'sudeste']) && $subtotal >= 250.00) {
            return 0.00;
        }

        if ($regiao === 'outras' && $subtotal >= 399.00) {
            return 0.00;
        }

        return self::VALOR_FRETE_PADRAO;
    }


    public function revisao()
    {
        $itens = session('itens_checkout', \Cart::getContent()->toArray());

        $itens = collect($itens)->map(function ($item) {
            return (object) $item;
        });

        $subtotal = $itens->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        if (!session()->has('endereco_entrega')) {
            return redirect()->route('pagamento.cep')->with('erro', 'Por favor, informe o endereço de entrega');
        }

        if (!session()->has('forma_pagamento')) {
            return redirect()->route('pagamento.forma-pagamento')->with('erro', 'Por favor, selecione a forma de pagamento');
        }

        $endereco = session('endereco_entrega');
        $formaPagamento = session('forma_pagamento');

        $frete = $this->calcularValorFrete($subtotal, $endereco['estado']);
        $totalComFrete = $subtotal + $frete;

        if ($formaPagamento === 'pix') {
            $descontoPix = $totalComFrete * 0.05;
            $totalComFrete -= $descontoPix;
        }

        return view('home.pagamento.revisao', compact('itens', 'subtotal', 'endereco', 'formaPagamento', 'frete', 'totalComFrete'));
    }


    protected function notificarAdministradores(Pedido $pedido)
    {
        try {
            $administradores = User::where('access_level', 'admin')->get();

            foreach ($administradores as $admin) {
                try {
                    $admin->notify(new NovoPedidoNotification($pedido));
                } catch (\Exception $e) {
                    \Log::error("Falha ao notificar admin {$admin->id}: " . $e->getMessage());
                    continue;
                }
            }
        } catch (\Exception $e) {
            \Log::error("Erro no processo de notificação: " . $e->getMessage());
        }
    }


    protected function verificarEValidarDadosSessao()
    {
        if (!session()->has('itens_checkout') || empty(session('itens_checkout'))) {
            throw new \Exception('Nenhum item encontrado no carrinho. Por favor, adicione itens antes de finalizar o pedido.');
        }

        if (!session()->has('endereco_entrega')) {
            throw new \Exception('Endereço de entrega não encontrado. Por favor, informe o endereço antes de finalizar o pedido.');
        }

        if (!session()->has('forma_pagamento')) {
            throw new \Exception('Forma de pagamento não selecionada. Por favor, selecione uma forma de pagamento antes de finalizar o pedido.');
        }

        if (!Auth::check()) {
            throw new \Exception('Usuário não autenticado. Por favor, faça login antes de finalizar o pedido.');
        }
    }

    protected function obterItensCheckout()
    {
        $sessionItems = session('itens_checkout', []);

        if (empty($sessionItems)) {
            \Log::warning('Sessão de checkout vazia ou sem itens.', ['session' => session()->all()]);
            return collect();
        }

        $itens = collect($sessionItems)->map(function ($itemData) {
            if (is_array($itemData)) {
                if (isset($itemData['stdClass']) && is_array($itemData['stdClass'])) {
                    return (object) $itemData['stdClass'];
                }
                return (object) $itemData;
            } elseif (is_object($itemData)) {
                if (isset($itemData->stdClass) && is_object($itemData->stdClass)) {
                    return $itemData->stdClass;
                }
                return $itemData;
            }
            return (object) $itemData;
        });

        \Log::debug('Itens recuperados e transformados para o PagSeguro:', ['itens' => $itens->toArray()]);

        return $itens;
    }

    protected function criarPedido($itens, $endereco)
    {
        $pedido = new Pedido();
        $pedido->id_usuario = Auth::id();
        $pedido->total = $itens->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $pedido->status = 'pendente';
        $pedido->endereco_entrega = json_encode($endereco);
        $pedido->data_pedido = now();
        $pedido->save();

        return $pedido;
    }

    protected function calcularTotalFinal($pedido, $formaPagamento)
    {
        $subtotal = $pedido->total;

        $enderecoPedido = json_decode($pedido->endereco_entrega, true);
        $uf = $enderecoPedido['estado'] ?? 'SP';

        $frete = $this->calcularValorFrete($subtotal, $uf);
        $totalComFrete = $subtotal + $frete;

        if ($formaPagamento === 'pix') {
            $totalComFrete *= 0.95;
        }

        return $totalComFrete;
    }

    protected function criarPagamento($pedido, $formaPagamento, $totalPedido, $request)
    {
        $pagamento = new PagamentoCheckout();
        $pagamento->id_pedido = $pedido->id_pedido;
        $pagamento->metodo_pagamento = $formaPagamento;
        $pagamento->valor_pago = $totalPedido;
        $pagamento->valor_original = $pedido->getOriginal('total');
        $pagamento->desconto = $pagamento->valor_original - $totalPedido;
        $pagamento->status = 'pendente';

        if ($formaPagamento === 'cartao') {
            $pagamento->parcelas = $request->parcelas ?? 1;
        }

        $pagamento->save();
        return $pagamento;
    }


    protected function adicionarItensAoPedido($pedido, $itens)
    {
        foreach ($itens as $item) {
            try {
                $partes = explode('-', $item->id);
                $idProduto = $partes[0];

                $pedidoItem = new PedidoItem();
                $pedidoItem->id_pedido = $pedido->id_pedido;
                $pedidoItem->id_produto = $idProduto;
                $pedidoItem->quantidade = (int) $item->quantity;
                $pedidoItem->preco_unitario = (float) $item->price;

                if (count($partes) > 1) {
                    $pedidoItem->id_cor = $partes[1] ?? null;
                    $pedidoItem->id_tamanho = $partes[2] ?? null;
                }
            } catch (\Exception $e) {
                Log::error("Erro ao adicionar item ao pedido #{$pedido->id_pedido}: " . $e->getMessage(), [
                    'item' => $item,
                    'exception' => $e->getTraceAsString()
                ]);
                throw new \Exception("Erro ao adicionar item ao pedido: " . $e->getMessage());
            }
            $pedidoItem->save();
        }
    }

    protected function finalizarCheckout()
    {
        $userId = Auth::id();
        if (session('checkout_type') === 'selecionados') {
            $selectedItems = session('selected_items', []);
            foreach ($selectedItems as $itemId) {
                \Cart::session($userId)->remove($itemId);
            }
        } else {
            \Cart::session($userId)->clear();
        }

        session()->forget([
            'itens_checkout',
            'checkout_type',
            'selected_items',
            'endereco_entrega',
            'forma_pagamento',
            'cupom_aplicado',
        ]);
    }


    public function processarPagamento(Request $request)
    {
        DB::beginTransaction();

        try {
            $this->verificarEValidarDadosSessao();

            $itens = $this->obterItensCheckout();
            $endereco = session('endereco_entrega');
            $formaPagamento = session('forma_pagamento');
            $usuario = Auth::user();

            if (!$usuario) {
                return response()->json(['error' => true, 'message' => 'Usuário não autenticado. Redirecionando para login.'], 401);
            }

            if ($itens->isEmpty()) {
                return response()->json(['error' => true, 'message' => 'Seu carrinho está vazio. Por favor, adicione itens para finalizar a compra.'], 400);
            }

            if (!$endereco) {
                return response()->json(['error' => true, 'message' => 'Endereço de entrega não encontrado. Por favor, revise seu endereço.'], 400);
            }

            $cpfParaPagSeguro = preg_replace('/[^0-9]/', '', $usuario->cpf ?? '');

            if (empty($cpfParaPagSeguro)) {
                \Log::warning('CPF do usuário ' . $usuario->id . ' está nulo ou vazio após limpeza.');
                return response()->json(['error' => true, 'message' => 'CPF/CNPJ do cliente é obrigatório e não pode estar vazio. Por favor, atualize seus dados cadastrais.'], 400);
            }
            if (strlen($cpfParaPagSeguro) !== 11 && strlen($cpfParaPagSeguro) !== 14) {
                \Log::error('CPF do usuário ' . $usuario->id . ' inválido ou com tamanho incorreto após limpeza: ' . $cpfParaPagSeguro);
                return response()->json(['error' => true, 'message' => 'CPF/CNPJ do cliente com formato inválido. Por favor, atualize seus dados cadastrais.'], 400);
            }
            \Log::info('CPF FINAL enviado para PagSeguro:', ['cpf' => $cpfParaPagSeguro]);

            // --- CORREÇÃO: Usar telefone placeholder se não houver um real ---
            // Como o cliente não terá telefone no cadastro, usaremos um número genérico válido para o PagSeguro.
            // O PagSeguro requer um número de 8 ou 9 dígitos para 'number' e 2 dígitos para 'area'.
            $dddPagSeguro = '11'; // DDD de São Paulo como placeholder
            $numeroPagSeguro = '999999999'; // Número de 9 dígitos (celular) como placeholder

            \Log::info('Telefone PLACEHOLDER enviado para PagSeguro (usuário não possui telefone cadastrado):', ['ddd' => $dddPagSeguro, 'number' => $numeroPagSeguro]);
            // --- FIM CORREÇÃO TELEFONE ---

            $pedido = $this->criarPedido($itens, $endereco);
            $this->adicionarItensAoPedido($pedido, $itens);

            $totalFinal = $this->calcularTotalFinal($pedido, $formaPagamento);

            $pedido->total = $totalFinal;
            $pedido->save();

            $pagamentoCheckout = $this->criarPagamento($pedido, $formaPagamento, $totalFinal, $request);

            $qrCodeData = null;
            $pixKey = null;
            $redirectUrl = null;

            if ($formaPagamento === 'pix') {
                $endpoint = 'https://sandbox.api.pagseguro.com/orders';
                $token = env('PAGSEGURO_BEARER_TOKEN', 'YOUR_DEFAULT_SANDBOX_TOKEN_HERE'); // MUDANÇA AQUI!


                $shippingAddress = [
                    "street" => $endereco['rua'] ?? '',
                    "number" => $endereco['numero'] ?? '',
                    "locality" => $endereco['bairro'] ?? '',
                    "city" => $endereco['cidade'] ?? '',
                    "region_code" => $endereco['estado'] ?? '',
                    "country" => "BRA",
                    "postal_code" => preg_replace('/[^0-9]/', '', $endereco['cep'] ?? '')
                ];

                if (!empty($endereco['complemento'])) {
                    $shippingAddress["complement"] = $endereco['complemento'];
                }

                $body = [
                    "reference_id" => "pedido-" . $pedido->id_pedido,
                    "customer" => [
                        "name" => $usuario->name,
                        "email" => $usuario->email,
                        "tax_id" => $cpfParaPagSeguro,
                        "phones" => [
                            [
                                "country" => "55",
                                "area" => $dddPagSeguro,       // Usando o DDD placeholder
                                "number" => $numeroPagSeguro, // Usando o número placeholder
                                "type" => "MOBILE"
                            ]
                        ]
                    ],
                    "items" => $itens->map(function ($item) {
                        return [
                            "name" => $item->name ?? 'Item sem nome',
                            "quantity" => (int) ($item->quantity ?? 1),
                            "unit_amount" => (int) round(($item->price ?? 0) * 100)
                        ];
                    })->values()->toArray(),
                    "qr_codes" => [
                        [
                            "amount" => [
                                "value" => (int) round($totalFinal * 100)
                            ],
                            "expiration_date" => now()->addMinutes(30)->format('Y-m-d\TH:i:sP'),
                        ]
                    ],
                    "shipping" => [
                        "address" => $shippingAddress
                    ],
                    "notification_urls" => [
                        "https://179faabf7b23.ngrok-free.app/webhooks/pagseguro" // Placeholder! MUDAR ISSO!
                    ]
                ];

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ])->post($endpoint, $body);

                $pagSeguroResponse = $response->json();
                \Log::info('PagSeguro API Response:', ['status' => $response->status(), 'body' => $pagSeguroResponse]);

                if ($response->successful()) {
                    if (isset($pagSeguroResponse['qr_codes'][0]['links'][0]['href']) && isset($pagSeguroResponse['qr_codes'][0]['text'])) {
                        $qrCodeData = $pagSeguroResponse['qr_codes'][0]['links'][0]['href'];
                        $pixKey = $pagSeguroResponse['qr_codes'][0]['text'];
                        $expirationDate = $pagSeguroResponse['qr_codes'][0]['expiration_date'];
                        $pagSeguroOrderId = $pagSeguroResponse['id'];

                        $pagamentoCheckout->codigo_transacao = $pagSeguroOrderId;
                        $pagamentoCheckout->status = 'pendente';
                        $pagamentoCheckout->data_pagamento = now();
                        $pagamentoCheckout->detalhes = $pagSeguroResponse;

                        $pagamentoCheckout->save();

                        \Log::info('PagamentoCheckout atualizado com dados da transação PagSeguro:', [
                            'pagamento_id' => $pagamentoCheckout->id_pagamento,
                            'status' => $pagamentoCheckout->status,
                            'codigo_transacao' => $pagamentoCheckout->codigo_transacao
                        ]);

                    } else {
                        throw new \Exception('QR Code URL ou Pix Key não encontrados na resposta do PagSeguro.');
                    }
                } else {
                    $errorMessage = 'Erro desconhecido ao gerar QR Code Pix.';
                    if (isset($pagSeguroResponse['error_messages']) && !empty($pagSeguroResponse['error_messages'][0]['description'])) {
                        $errorMessage = $pagSeguroResponse['error_messages'][0]['description'];
                    } elseif (isset($pagSeguroResponse['error_message'])) {
                        $errorMessage = $pagSeguroResponse['error_message'];
                    }
                    throw new \Exception('Erro na comunicação com PagSeguro: ' . $errorMessage);
                }

                session([
                    'qrCodeData' => $qrCodeData,
                    'pixKey' => $pixKey,
                    'expirationDate' => $expirationDate,
                    'last_pedido_id' => $pedido->id_pedido,
                ]);

                $redirectUrl = route('pagamento.pagar', ['pedidoId' => $pedido->id_pedido]);

            } elseif ($formaPagamento === 'cartao') {
                $pagamentoCheckout->status = 'aguardando_captura_cartao';
                $pagamentoCheckout->save();
                $redirectUrl = route('pagamento.sucesso', ['pedidoId' => $pedido->id_pedido]);

            } elseif ($formaPagamento === 'boleto') {
                $pagamentoCheckout->status = 'boleto_gerado';
                $pagamentoCheckout->save();
                $redirectUrl = route('pagamento.sucesso', ['pedidoId' => $pedido->id_pedido]);

            } else {
                throw new \Exception('Forma de pagamento inválida selecionada.');
            }

            $this->notificarAdministradores($pedido);
            $this->finalizarCheckout();

            DB::commit();

            return response()->json(['success' => true, 'redirect' => $redirectUrl]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Erro de validação ao processar pagamento: ' . $e->getMessage(), $e->errors());
            $firstError = collect($e->errors())->flatten()->first();
            return response()->json(['error' => true, 'message' => 'Dados inválidos: ' . $firstError], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro no processo de pagamento: " . $e->getMessage(), ['exception' => $e->getTraceAsString()]);
            return response()->json(['error' => true, 'message' => 'Ocorreu um erro ao processar seu pagamento: ' . $e->getMessage()], 500);
        }
    }

    public function mostrarPagamentoPix(Request $request, Pedido $pedido)
    {
        $qrCodeData = session('qrCodeData');
        $pixKey = session('pixKey');
        $total = $pedido->total;

        if (!$qrCodeData || !$pixKey) {
            return redirect()->route('carrinho.index')->with('error', 'Dados do PIX não encontrados. Por favor, tente novamente.');
        }

        return view('pagamento.pagar', compact('pedido', 'total', 'qrCodeData', 'pixKey'));
    }

    public function pagar(Request $request, $pedidoId)
    {
        try {
            $pedido = Pedido::findOrFail($pedidoId);

            if (Auth::id() !== $pedido->id_usuario && (!Auth::user() || !Auth::user()->isAdmin())) {
                return redirect()->route('home.index')->with('erro', 'Você não tem permissão para acessar este pagamento.');
            }

            $qrCodeData = session('qrCodeData');
            $pixKey = session('pixKey');
            $expirationDate = session('expirationDate');

            if (!$qrCodeData || !$pixKey || !$expirationDate) {
                return redirect()->route('pagamento.erro')->with('erro', 'Dados do PIX não encontrados. Por favor, tente novamente ou gere um novo pagamento.');
            }

            return view('home.pagamento.pagar', [
                'pedido' => $pedido,
                'qrCodeData' => $qrCodeData,
                'pixKey' => $pixKey,
                'expirationDate' => $expirationDate,
                'total' => $pedido->total
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('pagamento.erro')->with('erro', 'Pedido não encontrado.');
        } catch (\Exception $e) {
            \Log::error("Erro ao acessar página de pagamento: " . $e->getMessage(), ['exception' => $e->getTraceAsString()]);
            return redirect()->route('pagamento.erro')->with('erro', 'Ocorreu um erro ao carregar a página de pagamento.');
        }
    }

    public function erro()
    {
        return view('home.pagamento.erro');
    }

    public function sucesso($pedidoId)
    {
        $pedido = Pedido::with('pagamentoCheckout')->findOrFail($pedidoId);
        return view('home.pagamento.sucesso', compact('pedido'));
    }


}
