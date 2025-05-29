<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Endereco;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Pagamento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Darryldecode\Cart\Facades\CartFacade as Cart;


class PagamentoController extends Controller
{
    public function cep(Request $request)
    {
        if (session()->has('endereco_entrega')) {
            return redirect()->route('pagamento.revisao');
        }

        $itens = Cart::getContent();
        $total = Cart::getTotal();

        return view('home.pagamento.cep', compact('itens', 'total'));
    }

    public function buscarCep(Request $request)
    {
        $request->validate(['cep' => 'required|string|min:8|max:9']);

        try {
            $cep = preg_replace('/[^0-9]/', '', $request->cep);

            // Verifica se o CEP tem 8 dígitos
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

            // Verifica se os dados essenciais existem
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

        $endereco = session('endereco_entrega');
        $itens = Cart::getContent();
        $total = Cart::getTotal();

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

        // Redireciona de volta para a etapa apropriada
        if (session()->has('forma_pagamento')) {
            return redirect()->route('pagamento.revisao');
        } else {
            return redirect()->route('pagamento.forma-pagamento');
        }
    }


    public function formaPagamento()
    {
        if (!session()->has('endereco_entrega')) {
            return redirect()->route('pagamento.cep')->with('erro', 'Por favor, informe o endereço de entrega');
        }

        $itens = Cart::getContent();
        $total = Cart::getTotal();

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


    public function revisao()
    {
        if (!session()->has('endereco_entrega')) {
            return redirect()->route('pagamento.cep')->with('erro', 'Por favor, informe o endereço de entrega');
        }

        if (!session()->has('forma_pagamento')) {
            return redirect()->route('pagamento.forma-pagamento')->with('erro', 'Por favor, selecione a forma de pagamento');
        }

        
        $itens = Cart::getContent();
        $total = Cart::getTotal();
        $endereco = session('endereco_entrega');
        $formaPagamento = session('forma_pagamento');

        return view('home.pagamento.revisao', compact('itens', 'total', 'endereco', 'formaPagamento'));
    }

    public function finalizar(Request $request)
    {
        // dd('Finalizar foi chamado');

        $request->validate([
            'metodo_pagamento' => 'required|in:pix,cartao,boleto'
        ]);

        // Verifica se há itens no carrinho
        if (Cart::isEmpty()) {
            return redirect()->back()->with('erro', 'Seu carrinho está vazio');
        }

        // Cria o pedido
        $pedido = Pedido::create([
            'id_usuario' => Auth::id(),
            'total' => Cart::getTotal(),
            'status' => 'pendente'
        ]);

        // Adiciona itens ao pedido
        foreach (Cart::getContent() as $item) {
            PedidoItem::create([
                'id_pedido' => $pedido->id_pedido,
                'id_produto' => $item->id,
                'quantidade' => $item->quantity,
                'preco_unitario' => $item->price,
                'cor' => $item->attributes->cor ?? null,
                'tamanho' => $item->attributes->tamanho ?? null
            ]);
        }

        // Cria o pagamento
        $pagamento = Pagamento::create([
            'id_pedido' => $pedido->id_pedido,
            'metodo_pagamento' => $request->metodo_pagamento,
            'valor_pago' => Cart::getTotal(),
            'data_pagamento' => now(),
            'status' => 'pendente'
        ]);

        // Limpa o carrinho
        Cart::clear();

        // Salva dados na sessão para a página de sucesso
        session([
            'ultimo_pedido' => [
                'id_pedido' => $pedido->id_pedido,
                'total' => $pedido->total,
                'metodo_pagamento' => $request->metodo_pagamento,
                'data' => now()->format('d/m/Y H:i')
            ]
        ]);

        return redirect()->route('pagamento.sucesso');
    }


    public function erro()
    {
        return view('home.pagamento.erro');
    }
}

