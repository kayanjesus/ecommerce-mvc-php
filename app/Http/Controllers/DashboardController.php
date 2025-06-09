<?php

namespace App\Http\Controllers;
use App\Models\Tamanho;
use App\Models\Categoria;
use App\Models\Cor;
use App\Models\Produto;
use App\Models\Pedido;
use App\Models\User; // Certifique-se que o User model está importado
use DB; // Certifique-se que DB está importado

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
        // Busca o pedido pelo ID com todas as relações necessárias
        $pedido = Pedido::with([
            'usuario',
            'itens.produto',
            // 'itens.cor',    // Já removemos estas, o que está correto
            // 'itens.tamanho',// Já removemos estas, o que está correto
            'pagamentoCheckout' // <--- AQUI ESTÁ A MUDANÇA!
        ])->findOrFail($id_pedido);

        // Passa o pedido para a view
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