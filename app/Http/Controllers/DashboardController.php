<?php

namespace App\Http\Controllers;
use App\Models\Tamanho;
use App\Models\Categoria;
use App\Models\Cor;
use App\Models\Produto;
use App\Models\Pedido;
use App\Models\Entrega;
use App\Models\Rastreio;
use App\Models\User; // Certifique-se que o User model está importado
use DB; // Certifique-se que DB está importado
use Illuminate\Support\Facades\{Auth, Http, Log, };

use Illuminate\Http\Request;
    
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
        // Obter métricas
        $vendasHoje = Pedido::whereDate('created_at', today())->count();
        $valorRecebido = Pedido::whereDate('created_at', today())->sum('total');
        $avaliacoes = 0; // Adicione sua lógica para avaliações se necessário
        $notificacoes = auth()->user()->unreadNotifications()->latest()->take(10)->get();

        // Obter categorias para o menu (se necessário)
        $categoriasMenu = Categoria::all();

        return view('adm.dashboard', compact(
            'vendasHoje',
            'valorRecebido',
            'avaliacoes',
            'notificacoes',
            'categoriasMenu'
        ));
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



    public function pdtestoque()
    {
        // Listagem de produtos com variações, cor e tamanho
        $produtos = Produto::with(['variacoes.cor', 'variacoes.tamanho', 'categorias'])->get();
        return view('adm.pdtestoque', compact('produtos'));
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

    public function usercadastrado()
    {
        // Exemplo: buscar usuários cadastrados
        $users = User::all();
        return view('adm.usercadastrado', compact('users'));
    }

    public function vendas()
    {
        // Lógica para a página de vendas
        return view('adm.vendas');
    }
}