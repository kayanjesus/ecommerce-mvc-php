<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Endereco, Pedido, PedidoItem, PagamentoCheckout as Pagamento, Cupom, User};
use App\Models\PagamentoCheckout;
use App\Notifications\NovoPedidoNotification;
use Illuminate\Support\Facades\{Auth, Http, Log, DB};
use Darryldecode\Cart\Facades\CartFacade as Cart;




class PagamentoController extends Controller
{
    public function cep(Request $request)
    {
        $tipoCheckout = $request->input('tipo_checkout', 'todos');

        if ($tipoCheckout === 'selecionados') {
            $selectedItems = json_decode($request->input('selected_items', '[]'), true);

            if (empty($selectedItems)) {
                return redirect()->back()->with('erro', 'Selecione pelo menos um item para finalizar o pedido');
            }

            $itens = collect(\Cart::getContent())->filter(function ($item) use ($selectedItems) {
                return in_array($item->id, $selectedItems);
            });

            // Debug: Verifique os itens antes de salvar
            \Log::debug('Itens do carrinho (selecionados):', ['itens' => $itens->toArray()]);

            session([
                'checkout_type' => 'selecionados',
                'selected_items' => $selectedItems,
                'itens_checkout' => $itens // Converta para array para garantir persistência
            ]);
        } else {
            $itens = \Cart::getContent();

            // Debug: Verifique os itens antes de salvar
            \Log::debug('Itens do carrinho (todos):', ['itens' => $itens->toArray()]);

            session([
                'checkout_type' => 'todos',
                'itens_checkout' => $itens->toArray() // Converta para array para garantir persistência
            ]);
        }

        if ($itens->isEmpty()) {
            return redirect()->back()->with('erro', 'Seu carrinho está vazio');
        }

        $total = $itens->sum(function ($item) {
            return $item->price * $item->quantity;
        });

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

        // Usa os itens da sessão de checkout
        if (!session()->has('itens_checkout')) {
            return redirect()->route('pagamento.cep')->with('erro', 'Sessão expirada. Por favor, selecione os itens novamente.');
        }

        $endereco = session('endereco_entrega');
        $itens = session('itens_checkout');
        $total = $itens->sum(function ($item) {
            return $item->price * $item->quantity;
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

        // Redireciona de volta para a etapa apropriada
        if (session()->has('forma_pagamento')) {
            return redirect()->route('pagamento.revisao');
        } else {
            // Garante que os itens da sessão de checkout ainda existam
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

        // Usa os itens da sessão de checkout
        if (!session()->has('itens_checkout')) {
            return redirect()->route('pagamento.cep')->with('erro', 'Sessão expirada. Por favor, selecione os itens novamente.');
        }

        // Converta o array para coleção
        $itens = collect(session('itens_checkout'));

        // Agora podemos usar sum()
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


    public function revisao()
    {
        $itens = session('itens_checkout', \Cart::getContent()->toArray());

        // Converta para coleção
        $itens = collect($itens)->map(function ($item) {
            return (object) $item;
        });

        $total = $itens->sum(function ($item) {
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

        // Criar um novo pedido base para usar o método
        $pedido = new \App\Models\Pedido();

        // Calcular o frete
        $frete = $pedido->calcularFrete($endereco['cep']);
        $totalComFrete = $total + $frete;

        // Aplicar desconto no PIX
        if ($formaPagamento === 'pix') {
            $descontoPix = $totalComFrete * 0.05;
            $totalComFrete -= $descontoPix;
        }

        return view('home.pagamento.revisao', compact('itens', 'total', 'endereco', 'formaPagamento', 'frete', 'totalComFrete'));
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
        // Verifica se os itens do checkout estão na sessão
        if (!session()->has('itens_checkout') || empty(session('itens_checkout'))) {
            throw new \Exception('Nenhum item encontrado no carrinho. Por favor, adicione itens antes de finalizar o pedido.');
        }

        // Verifica se o endereço de entrega está na sessão
        if (!session()->has('endereco_entrega')) {
            throw new \Exception('Endereço de entrega não encontrado. Por favor, informe o endereço antes de finalizar o pedido.');
        }

        // Verifica se a forma de pagamento está na sessão
        if (!session()->has('forma_pagamento')) {
            throw new \Exception('Forma de pagamento não selecionada. Por favor, selecione uma forma de pagamento antes de finalizar o pedido.');
        }

        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            throw new \Exception('Usuário não autenticado. Por favor, faça login antes de finalizar o pedido.');
        }
    }

    protected function obterItensCheckout()
    {
        if (!session()->has('itens_checkout')) {
            \Log::error('Sessão de checkout vazia', ['session' => session()->all()]);
            throw new \Exception('Nenhum item encontrado no carrinho.');
        }

        $itens = session('itens_checkout');

        // Converta de volta para coleção se necessário
        $itens = collect($itens)->map(function ($item) {
            return (object) $item; // Converte array de volta para objeto
        });

        if ($itens->isEmpty()) {
            \Log::error('Itens do checkout vazios após recuperação');
            throw new \Exception('Nenhum item encontrado no carrinho.');
        }

        \Log::debug('Itens recuperados do checkout:', ['itens' => $itens->toArray()]);

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
        $pedido->endereco_entrega = $endereco;
        $pedido->data_pedido = now();
        $pedido->save();

        return $pedido;
    }

    protected function calcularTotalFinal($pedido, $formaPagamento)
    {
        $total = $pedido->total;

        // Calcular frete
        $frete = $pedido->calcularFrete($pedido->endereco_entrega['cep']);
        $total += $frete;

        // Aplicar desconto no PIX
        if ($formaPagamento === 'pix') {
            $total *= 0.95; // 5% de desconto
        }

        return $total;
    }

    protected function criarPagamento($pedido, $formaPagamento, $totalPedido, $request)
    {
        $pagamento = new PagamentoCheckout();
        $pagamento->id_pedido = $pedido->id_pedido;
        $pagamento->metodo_pagamento = $formaPagamento;
        $pagamento->valor_pago = $totalPedido;
        $pagamento->valor_original = $pedido->total;
        $pagamento->desconto = $pedido->total - $totalPedido;
        $pagamento->status = 'pendente';

        if ($formaPagamento === 'cartao') {
            $pagamento->parcelas = $request->parcelas ?? 1;
        }

        $pagamento->save();
    }

    protected function adicionarItensAoPedido($pedido, $itens)
    {
        foreach ($itens as $item) {
            // Separa o ID composto (formato: "id_produto-cor-tamanho")
            $partes = explode('-', $item->id);
            $idProduto = $partes[0]; // Pega apenas a parte numérica do ID

            $pedidoItem = new PedidoItem();
            $pedidoItem->id_pedido = $pedido->id_pedido;
            $pedidoItem->id_produto = $idProduto; // Apenas o ID numérico
            $pedidoItem->quantidade = $item->quantity;
            $pedidoItem->preco_unitario = $item->price;

            // Adiciona cor e tamanho se existirem
            if (count($partes) > 1) {
                $pedidoItem->cor = $partes[1] ?? null;
                $pedidoItem->tamanho = $partes[2] ?? null;
            }

            $pedidoItem->save();
        }
    }

    protected function finalizarCheckout()
    {
        // Limpar carrinho baseado no tipo de checkout
        if (session('checkout_type') === 'selecionados') {
            $selectedItems = session('selected_items', []);
            foreach ($selectedItems as $itemId) {
                \Cart::remove($itemId);
            }
        } else {
            \Cart::clear();
        }

        // Limpar sessão de checkout
        session()->forget([
            'itens_checkout',
            'checkout_type',
            'selected_items',
            'endereco_entrega',
            'forma_pagamento'
        ]);
    }

    protected function prepararDadosSucesso($pedido, $totalPedido, $formaPagamento)
    {
        session([
            'pedido_data' => [
                'id_pedido' => $pedido->id_pedido,
                'total' => $totalPedido,
                'metodo_pagamento' => $formaPagamento,
                'data' => $pedido->data_pedido->format('d/m/Y H:i:s')
            ]
        ]);

        // Notificar administradores
        $this->notificarAdministradores($pedido);
    }



    public function finalizar(Request $request)
    {
        DB::beginTransaction();

        try {
            // Verificação manual da sessão
            if (!session()->has('endereco_entrega')) {
                throw new \Exception('Endereço de entrega não encontrado');
            }

            if (!session()->has('forma_pagamento')) {
                throw new \Exception('Forma de pagamento não selecionada');
            }

            // 1. Validação dos dados
            if (!session()->has('itens_checkout') || empty(session('itens_checkout'))) {
                throw new \Exception('Nenhum item encontrado para checkout');
            }

            // 2. Preparar dados
            $itens = collect(session('itens_checkout'));
            $endereco = session('endereco_entrega');
            $formaPagamento = session('forma_pagamento');

            // 3. Criar pedido
            $pedido = new Pedido();
            $pedido->id_usuario = Auth::id();
            $pedido->total = $itens->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });
            $totalPedido = $pedido->total;
            $pedido->status = 'pendente';
            $pedido->endereco_entrega = $endereco;
            $pedido->save();

            // 4. Registrar pagamento
            $pagamento = new PagamentoCheckout();
            $pagamento->id_pedido = $pedido->id_pedido;
            $pagamento->metodo_pagamento = $formaPagamento;
            $pagamento->valor_pago = $pedido->total;
            $pagamento->valor_original = $pedido->total; // Adicione esta linha
            $pagamento->status = 'pendente';

            // Se for pagamento no pix, aplique desconto de 5%
            if ($formaPagamento === 'pix') {
                $pagamento->valor_pago = $pedido->total * 0.95;
                $pagamento->desconto = $pedido->total * 0.05;
            }

            $pagamento->save();

            // 5. Adicionar itens ao pedido
            foreach ($itens as $item) {
                $pedidoItem = new PedidoItem();
                $pedidoItem->id_pedido = $pedido->id_pedido;
                $pedidoItem->id_produto = explode('-', $item['id'])[0]; // Pega apenas o ID do produto
                $pedidoItem->quantidade = $item['quantity'];
                $pedidoItem->preco_unitario = $item['price'];
                $pedidoItem->save();
            }

            // 6. Limpar carrinho e sessão
            \Cart::clear();
            session()->forget(['itens_checkout', 'endereco_entrega', 'forma_pagamento']);

            DB::commit();

            // Manter os dados na sessão para a página de sucesso
            session([
                'pedido_data' => [
                    'id_pedido' => $pedido->id_pedido,
                    'total' => $totalPedido,
                    'metodo_pagamento' => $formaPagamento,
                    'data' => now()->format('d/m/Y H:i:s')
                ]
            ]);

            return response()->json([
                'success' => true,
                'redirect' => route('pagamento.sucesso')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('FALHA NO PEDIDO', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'redirect' => route('pagamento.erro')
            ], 500);
        }
    }


    protected function validarDadosCheckout()
    {
        if (!session()->has('itens_checkout') || empty(session('itens_checkout'))) {
            throw new \Exception('Nenhum item encontrado para checkout');
        }

        if (!session()->has('endereco_entrega')) {
            throw new \Exception('Endereço de entrega não informado');
        }

        if (!session()->has('forma_pagamento')) {
            throw new \Exception('Forma de pagamento não selecionada');
        }
    }

    protected function formatarItensCheckout()
    {
        return collect(session('itens_checkout'))->map(function ($item) {
            if (is_array($item)) {
                $item = (object) $item;

                // Garante que os atributos sejam objeto
                if (isset($item->attributes) && is_array($item->attributes)) {
                    $item->attributes = (object) $item->attributes;
                }
            }
            return $item;
        });
    }




    public function sucesso()
    {
        // Tenta recuperar da sessão primeiro
        if (session()->has('pedido_data')) {
            return view('home.pagamento.sucesso', [
                'pedido' => session('pedido_data')
            ]);
        }

        // Fallback: busca o último pedido do usuário
        $pedido = Pedido::where('id_usuario', Auth::id())
            ->with('pagamento')
            ->latest()
            ->first();

        if (!$pedido) {
            return redirect()->route('home.index')
                ->with('erro', 'Pedido não encontrado');
        }

        $pedidoData = [
            'id_pedido' => $pedido->id_pedido,
            'total' => $pedido->pagamento->valor_pago,
            'metodo_pagamento' => $pedido->pagamento->metodo_pagamento,
            'data' => $pedido->data_pedido->format('d/m/Y H:i:s')
        ];

        return view('home.pagamento.sucesso', [
            'pedido' => $pedidoData
        ]);
    }




    public function aplicarCupom(Request $request)
    {
        $request->validate(['codigo_cupom' => 'required|string']);

        $cupom = Cupom::where('codigo', $request->codigo_cupom)->first();

        if (!$cupom || !$cupom->estaValido()) {
            return back()->with('erro', 'Cupom inválido ou expirado');
        }

        // Calcular novo total com desconto
        $itens = \Cart::getContent();
        $total = $itens->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $totalComDesconto = $cupom->aplicarDesconto($total);

        // Armazenar na sessão
        session([
            'cupom_aplicado' => $cupom->id_cupom,
            'total_com_desconto' => $totalComDesconto
        ]);

        return back()->with('sucesso', 'Cupom aplicado com sucesso!');
    }




    public function confirmarPagamentoFicticio(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'pedido_id' => 'required|exists:pedidos,id_pedido'
            ]);

            // Carrega o pedido com o relacionamento
            $pedido = Pedido::with('pagamentoCheckout')->findOrFail($request->pedido_id);

            if ($pedido->status === 'pago') {
                return response()->json([
                    'success' => true,
                    'message' => 'Pedido já estava marcado como pago'
                ]);
            }

            // Atualizar status do pedido
            $pedido->status = 'pago';
            $pedido->save();

            // Atualizar status do pagamento (usando pagamentoCheckout)
            if ($pedido->pagamentoCheckout) {
                $pedido->pagamentoCheckout->status = 'pago';
                $pedido->pagamentoCheckout->data_pagamento = now();
                $pedido->pagamentoCheckout->save();
            }

            // Notificar administradores
            $this->notificarAdministradores($pedido);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pagamento confirmado com sucesso'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao confirmar pagamento fictício: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao confirmar pagamento: ' . $e->getMessage()
            ], 500);
        }
    }






    public function erro()
    {
        return view('home.pagamento.erro');
    }
}

