<?php

namespace App\Http\Controllers;

use App\Models\Tamanho;
use App\Models\Categoria;
use App\Models\Cor;
use App\Models\Produto;
use App\Models\Pedido;
use App\Models\Entrega;
use App\Models\Rastreio;
use App\Models\Avaliacao;
use App\Models\Reembolso;
use App\Models\User; // Certifique-se que o User model está importado
use DB; // Certifique-se que DB está importado
use Illuminate\Support\Facades\{Auth, Http, Log, };
use Carbon\Carbon;

use Illuminate\Http\Request; // Importe a classe Request

class DashboardController extends Controller
{
    public function index()
    {
        $vendasHoje = Pedido::whereDate('created_at', today())->count();
        $valorRecebido = Pedido::whereDate('created_at', today())->sum('total');
        // $avaliacoes = 0; // Removido pois não está sendo usado no seu view index
        $notificacoes = auth()->user()->unreadNotifications()->latest()->take(10)->get();

        return view('admin.sistema', compact('vendasHoje', 'valorRecebido', 'notificacoes'));
    }

    public function dashboard()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso não autorizado. Você não é um administrador.');
        }

        // Definir os status que consideramos como "valor recebido" ou "venda efetivada"
        $statusRecebidosOuConcluidos = [
            'pago',
            'processando',
            'enviado',
            'em_transito',
            'saiu_para_entrega',
            'entregue',
            'reembolso_solicitado',
            'reembolso_aprovado',
            'reembolso_processando',
            'reembolso_concluido'
        ];

        // Métrica: Vendas hoje (pedidos com status que indicam venda efetivada, criados hoje)
        $vendasHoje = Pedido::whereDate('created_at', today())
            ->whereIn('status', $statusRecebidosOuConcluidos)
            ->count();

        // Métrica: Valor recebido hoje (pedidos com status de recebimento, criados hoje)
        $valorRecebido = Pedido::whereDate('created_at', today())
            ->whereIn('status', $statusRecebidosOuConcluidos)
            ->sum('total');

        // --- Consulta para os últimos pedidos ---
        $ultimosPedidos = Pedido::with('usuario')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        // --- Fim da consulta de últimos pedidos ---

        // Métrica: Total de Avaliações
        $totalAvaliacoes = Avaliacao::count();

        // Carrega todas as notificações não lidas para exibição inicial
        $notificacoes = Auth::user()->unreadNotifications()->get();

        // --- UM ÚNICO RETURN VIEW PASSANDO TODAS AS VARIÁVEIS ---
        return view('adm.dashboard', compact('vendasHoje', 'valorRecebido', 'totalAvaliacoes', 'notificacoes', 'ultimosPedidos'));
    }


    /**
     * Retorna as métricas via AJAX para o dashboard principal (adm.dashboard).
     */
    public function metricas()
    {
        // Definir os status que consideramos como "valor recebido" ou "venda efetivada"
        $statusRecebidosOuConcluidos = [
            'pago',
            'processando',
            'enviado',
            'em_transito',
            'saiu_para_entrega',
            'entregue',
            'reembolso_solicitado',
            'reembolso_aprovado',
            'reembolso_processando',
            'reembolso_concluido'
        ];

        // Métrica: Vendas hoje (pedidos com status que indicam venda efetivada, criados hoje)
        $vendasHoje = Pedido::whereDate('created_at', today())
            ->whereIn('status', $statusRecebidosOuConcluidos)
            ->count();

        // Métrica: Valor recebido hoje (pedidos com status de recebimento, criados hoje)
        $valorRecebido = Pedido::whereDate('created_at', today())
            ->whereIn('status', $statusRecebidosOuConcluidos)
            ->sum('total');

        // Métrica: Total de Avaliações
        $totalAvaliacoes = Avaliacao::count();

        return response()->json([
            'vendasHoje' => $vendasHoje,
            'valorRecebido' => $valorRecebido,
            'avaliacoes' => $totalAvaliacoes
        ]);
    }

    public function pedidos()
    {
        // Busque pedidos pagos e em andamento
        // Certifique-se que 'usuario', 'itens' e 'pagamento' estão sendo eager loaded
        $pedidos = Pedido::with(['usuario', 'itens.produto.variacoes', 'itens.cor', 'itens.tamanho', 'pagamento'])
            ->whereIn('status', ['pago', 'processando', 'enviado'])
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginação para 10 pedidos por página

        return view('adm.pedidos', compact('pedidos'));
    }

    public function detalhePedido($id_pedido)
    {
        // Eager loading para carregar os relacionamentos necessários de uma vez
        $pedido = Pedido::with(['usuario', 'itens.tamanho', 'pagamentoCheckout', 'entrega.rastreio'])
            ->find($id_pedido);

        if (!$pedido) {
            return redirect()->route('adm.pedidos')->with('erro', 'Pedido não encontrado.');
        }

        // Se o pedido não tem uma entrega associada, crie uma com status inicial
        if (!$pedido->entrega) {
            // Determina um método de entrega fictício para o TCC
            $metodo_entrega = 'sedex'; // Pode ser 'pac' ou 'retirada'

            // Valor do frete já deve ter sido calculado e salvo no pedido->total (ou em outro lugar)
            // Para simplicidade na demonstração fictícia, vamos usar o valor do frete do pedido
            // ou um valor default se não estiver explícito na estrutura atual do pedido.
            // Para o TCC, o importante é ter um valor.
            $valor_entrega = $pedido->total - ($pedido->pagamentoCheckout->valor_original ?? $pedido->total);
            if ($valor_entrega < 0) { // Garante que o frete não seja negativo por causa de descontos
                $valor_entrega = 0.00;
            }

            try {
                $entrega = Entrega::create([
                    'id_pedido' => $pedido->id_pedido,
                    'metodo_entrega' => $metodo_entrega,
                    'valor_entrega' => $valor_entrega,
                    'data_envio' => null, // Ainda não enviado
                    'data_entrega' => null, // Ainda não entregue
                ]);
                // Recarrega o relacionamento para que o objeto $pedido agora tenha $pedido->entrega
                $pedido->load('entrega');
                Log::info("Entrega criada automaticamente para o pedido #{$pedido->id_pedido}");
            } catch (\Exception $e) {
                Log::error("Erro ao criar entrega para o pedido #{$pedido->id_pedido}: " . $e->getMessage());
                return redirect()->route('adm.pedidos')->with('erro', 'Erro ao preparar informações de entrega.');
            }
        }

        return view('adm.detalhe_pedido', compact('pedido'));
    }

    public function alterarStatusPedido(Request $request, $id_pedido)
    {
        $pedido = Pedido::findOrFail($id_pedido);
        $novoStatus = $request->input('status');

        if (!in_array($novoStatus, ['pago', 'processando', 'enviado', 'entregue', 'cancelado'])) {
            return back()->with('erro', 'Status inválido.');
        }

        // Lógica para verificar o status de pagamento antes de processar/enviar
        if ($pedido->pagamento && $pedido->pagamento->status !== 'pago' && in_array($novoStatus, ['processando', 'enviado', 'entregue'])) {
            return back()->with('erro', 'Não é possível alterar o status do pedido antes do pagamento ser confirmado.');
        }

        $pedido->status = $novoStatus;
        $pedido->save();

        return back()->with('sucesso', 'Status do pedido atualizado para ' . ucfirst($novoStatus) . '!');
    }


    public function atualizarStatusEntrega(Request $request, $id_pedido)
    {
        $request->validate([
            'status_entrega' => [
                'required',
                'in:pendente,processando,enviado,em_transito,saiu_para_entrega,entregue'
            ],
        ]);

        $pedido = Pedido::with('entrega')->find($id_pedido);

        if (!$pedido) {
            return redirect()->back()->with('erro', 'Pedido não encontrado.');
        }

        if (!$pedido->entrega) {
            return redirect()->back()->with('erro', 'Registro de entrega não encontrado para este pedido. Por favor, recarregue a página.');
        }

        DB::beginTransaction();
        try {
            // Atualiza o status do PEDIDO
            $pedido->status = $request->status_entrega;
            $pedido->save();

            // Atualiza o status da ENTREGA e datas relevantes
            $entrega = $pedido->entrega;
            switch ($request->status_entrega) {
                case 'enviado':
                    if (is_null($entrega->data_envio)) {
                        $entrega->data_envio = now();
                    }
                    $entrega->data_entrega = null; // Garante que a data de entrega seja nula até ser entregue
                    break;
                case 'entregue':
                    if (is_null($entrega->data_envio)) { // Se for entregue direto, marca o envio também
                        $entrega->data_envio = now();
                    }
                    $entrega->data_entrega = now();
                    break;
                default:
                    // Para outros status, pode limpar datas ou deixá-las como estão
                    $entrega->data_entrega = null;
                    if ($request->status_entrega === 'pendente' || $request->status_entrega === 'processando') {
                        $entrega->data_envio = null;
                    }
                    break;
            }
            $entrega->save();

            DB::commit();
            return redirect()->back()->with('sucesso', 'Status da entrega do pedido #' . $id_pedido . ' atualizado para "' . ucfirst($request->status_entrega) . '".');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao atualizar status da entrega para o pedido #{$id_pedido}: " . $e->getMessage(), ['exception' => $e->getTraceAsString()]);
            return redirect()->back()->with('erro', 'Ocorreu um erro ao atualizar o status da entrega.');
        }
    }



    /**
     * Adiciona ou atualiza o código de rastreio para uma entrega.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id_pedido
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adicionarRastreio(Request $request, $id_pedido)
    {
        $request->validate([
            'codigo_rastreio' => ['required', 'string', 'max:50'],
        ]);

        $pedido = Pedido::with('entrega')->find($id_pedido);

        if (!$pedido || !$pedido->entrega) {
            return redirect()->back()->with('erro', 'Pedido ou registro de entrega não encontrado.');
        }

        DB::beginTransaction();
        try {
            Rastreio::updateOrCreate(
                ['id_entrega' => $pedido->entrega->id_entrega],
                ['codigo_rastreio' => strtoupper($request->codigo_rastreio)] // Armazena em maiúsculas
            );

            DB::commit();
            return redirect()->back()->with('sucesso', 'Código de rastreio adicionado/atualizado para o pedido #' . $id_pedido . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao adicionar código de rastreio para o pedido #{$id_pedido}: " . $e->getMessage(), ['exception' => $e->getTraceAsString()]);
            return redirect()->back()->with('erro', 'Ocorreu um erro ao adicionar o código de rastreio.');
        }
    }


    /**
     * Exibe a página de produtos e estoque com funcionalidades de pesquisa e filtro.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function pdtestoque(Request $request)
    {
        // 1. Obter parâmetros da requisição
        $search = $request->query('search'); // Termo de pesquisa
        $stockFilter = $request->query('stock_filter', 'todos'); // Filtro de estoque, padrão 'todos'

        // 2. Iniciar a query para produtos com as relações necessárias
        $produtosQuery = Produto::with(['imagens', 'categorias', 'variacoes.cor', 'variacoes.tamanho']);

        // 3. Aplicar filtro de pesquisa por nome do produto
        if ($search) {
            $produtosQuery->where('nome_produto', 'like', '%' . $search . '%');
        }

        // 4. Aplicar filtro de estoque
        if ($stockFilter === 'estoque') {
            // Produtos com estoque: onde pelo menos uma variação tem estoque > 0
            $produtosQuery->whereHas('variacoes', function ($query) {
                $query->where('estoque', '>', 0);
            });
        } elseif ($stockFilter === 'semestoque') {
            // Produtos sem estoque: onde NENHUMA variação tem estoque > 0 (todas são <= 0)
            // E o produto realmente tem variações associadas (para não listar produtos sem variações como 'sem estoque').
            $produtosQuery->whereDoesntHave('variacoes', function ($query) {
                $query->where('estoque', '>', 0);
            })->whereHas('variacoes'); 
        }
        // Se $stockFilter for 'todos', nenhuma condição de estoque é adicionada,
        // exibindo todos os produtos (com e sem estoque).

        // 5. Paginador
        $produtos = $produtosQuery->paginate(10); // 10 produtos por página. Ajuste conforme sua preferência.

        // 6. Passar os dados para a view
        return view('adm.pdtestoque', [
            'produtos' => $produtos,
            'searchQuery' => $search, // Passa o termo de pesquisa para a view manter no input
            'stockFilter' => $stockFilter, // Passa o filtro ativo para a view manter o botão ativo
        ]);
    }

    public function cdtproduto()
    {
        // Buscar tamanhos ordenados por nome
        $tamanhos = Tamanho::orderBy('nome')->get();

        // Pega todas categorias do banco (ou só as que quer)
        $categorias = Categoria::all();

        // Buscar cores ordenadas por nome
        $cores = Cor::orderBy('nome')->get();

        // Buscar todas as categorias para o menu
        $categoriasMenu = Categoria::all();

        // Retornar a view com os dados
        return view('adm.cdtproduto', compact('tamanhos', 'cores', 'categoriasMenu', 'categorias'));
    }

    public function usercadastrado(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso não autorizado. Você não é um administrador.');
        }

        $search = $request->query('search');
        $usersQuery = User::query();
        if ($search) {
            $usersQuery->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%'); // Adicionei busca por email também
        }
        $users = $usersQuery->paginate(10);


        // Dados para o gráfico "Aquisição de usuários"
        $usersByMonth = User::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw("COUNT(*) as total_users")
        )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $userAno = $usersByMonth->pluck('month')->map(function ($month) {
            return Carbon::createFromFormat('Y-m', $month)->translatedFormat('M Y');
        })->toJson();

        $userTotal = $usersByMonth->pluck('total_users')->toJson();

        $userLabel = json_encode(['Novos Usuários']); // Um array JSON para o label

        return view('adm.usercadastrado', compact('users', 'userAno', 'userTotal', 'userLabel', 'search'));
    }


    public function vendas()
    {
        // Calcular dados dos últimos 3 meses para os cards
        $mesesData = [];
        for ($i = 0; $i < 3; $i++) {
            $mes = Carbon::now()->subMonths($i);
            $nomeMes = $mes->translatedFormat('F'); // Nome do mês em português
            $ano = $mes->year;

            $totalVendasMes = Pedido::whereMonth('created_at', $mes->month)
                ->whereYear('created_at', $ano)
                ->count();

            // Considera todos os status que implicam recebimento
            $faturamentoMes = Pedido::whereMonth('created_at', $mes->month)
                ->whereYear('created_at', $ano)
                ->whereIn('status', ['pago', 'processando', 'enviado', 'em_transito', 'saiu_para_entrega', 'entregue', 'reembolso_solicitado', 'reembolso_aprovado', 'reembolso_processando', 'reembolso_concluido'])
                ->sum('total');

            $mesesData[] = [
                'nome' => ucfirst($nomeMes),
                'ano' => $ano,
                'total_recebido' => $faturamentoMes,
                'total_vendas' => $totalVendasMes,
            ];
        }
        $mesesData = array_reverse($mesesData); // Para exibir do mais antigo para o mais recente

        // Dados para os gráficos (últimos 5 meses)
        $labelsGraficos = [];
        $dataVendas = [];
        $dataFaturamento = [];

        for ($i = 4; $i >= 0; $i--) { // De 4 meses atrás até o atual
            $mes = Carbon::now()->subMonths($i);
            $labelsGraficos[] = ucfirst($mes->translatedFormat('F'));

            $vendas = Pedido::whereMonth('created_at', $mes->month)
                ->whereYear('created_at', $mes->year)
                ->count();

            // Considera todos os status que implicam recebimento
            $faturamento = Pedido::whereMonth('created_at', $mes->month)
                ->whereYear('created_at', $mes->year)
                ->whereIn('status', ['pago', 'processando', 'enviado', 'em_transito', 'saiu_para_entrega', 'entregue', 'reembolso_solicitado', 'reembolso_aprovado', 'reembolso_processando', 'reembolso_concluido'])
                ->sum('total');

            $dataVendas[] = $vendas;
            $dataFaturamento[] = $faturamento;
        }

        return view('adm.vendas', compact('mesesData', 'labelsGraficos', 'dataVendas', 'dataFaturamento'));
    }
}
