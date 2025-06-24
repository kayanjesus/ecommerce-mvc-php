<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\FavoritosController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\PedidoController; // Este é o PedidoController para admin actions
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ClientePedidoController; // Este é para o CLIENTE

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [SiteController::class, 'index'])->name('home.index');
Route::get('/produtos-home', [ProdutoController::class, 'index'])->name('produtos.home');
Route::resource('produtos', ProdutoController::class);
Route::get('/produto/{slug}', [SiteController::class, 'details'])->name('home.details');
Route::get('/categoria/{id_categoria}', [SiteController::class, 'categoria'])->name('home.categoria');
Route::get('/temporada/{temporada}', [SiteController::class, 'temporada'])->name('temporada');



// CRUD ADM (Se estas rotas forem exclusivas para ADM, considere movê-las para o grupo 'adm')
Route::get('/produtos/create', [ProdutoController::class, 'create'])->name('adm.pdtestoque');
Route::post('/produtos/store', [ProdutoController::class, 'store'])->name('produtos.store');

// Rotas do Carrinho e Pagamento
Route::middleware(['web'])->group(function () {
    // Rota para adicionar item ao carrinho
    Route::post('/adicionar-carrinho', [CarrinhoController::class, 'adicionaCarrinho'])->name('home.addcarrinho');

    // Esta rota agora é a que exibe o carrinho/página de CEP
    // Renomeei a rota de 'home.carrinho' para 'pagamento.cep' para consistência
    Route::get('/pagamento/cep', [CarrinhoController::class, 'carrinhoLista'])
        ->name('pagamento.cep') // << NOVA ROTA PADRÃO PARA O CARRINHO
        ->middleware('auth');

    // Rotas de remoção, atualização e limpeza, todas redirecionando para 'pagamento.cep' ou 'home.index'
    Route::post('/remover', [CarrinhoController::class, 'removeCarrinho'])->name('home.removecarrinho');
    Route::post('/atualizar', [CarrinhoController::class, 'atualizaCarrinho'])->name('home.atualizacarrinho');
    Route::get('/limpar', [CarrinhoController::class, 'limparCarrinho'])->name('home.limparcarrinho');

    // Rotas para a funcionalidade de CEP e salvamento do endereço
    Route::get('/buscar-cep', [CarrinhoController::class, 'buscarCep'])->name('pagamento.buscar-cep');
    Route::post('/salvar-endereco', [CarrinhoController::class, 'salvarEndereco'])->name('pagamento.salvar-endereco');
    // Se você tiver uma rota para a próxima etapa (confirmação, por exemplo)
    // Route::get('/pagamento/confirmacao', [CarrinhoController::class, 'confirmacaoPagamento'])->name('pagamento.confirmacao');
});

// login/cadastro
Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
        return view('adm.dashboard');
    })->name('adm.dashboard');

    // User Dashboard (cliente)
    Route::get('/user/dashboard', [ProfileController::class, 'userDashboard'])->name('home.dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Favoritos
Route::prefix('favoritos')->group(function () {
    Route::get('/', [FavoritosController::class, 'favoritosLista'])->name('home.favoritos');
    Route::post('/adicionar', [FavoritosController::class, 'adicionaFavoritos'])->name('home.addfavoritos');
    Route::post('/remover', [FavoritosController::class, 'removeFavoritos'])->name('home.removefavoritos');
    Route::get('/limpar', [FavoritosController::class, 'limparFavoritos'])->name('home.limparfavoritos');
});

// Checkout and General Auth Routes
Route::middleware('auth')->group(function () {
    Route::get('cadastro', [PagesController::class, 'cadastro'])->name('cadastro')->middleware('can:access');
    Route::get('/checkout', [CheckoutController::class, 'showSummary'])->name('checkout.summary');
    Route::post('/checkout/processar', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
});

// Webhooks
Route::post('/webhooks/pagseguro', [WebhookController::class, 'handle'])->name('webhooks.pagseguro')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Admin-specific routes (consolidated under 'adm' prefix with 'admin' middleware)
Route::prefix('adm')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard and General Admin Views
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('adm.dashboard');
    Route::get('/pdtestoque', [DashboardController::class, 'pdtestoque'])->name('adm.pdtestoque');
    Route::get('/cdtproduto', [DashboardController::class, 'cdtproduto'])->name('adm.cdtproduto');
    Route::get('/usercadastrado', [DashboardController::class, 'usercadastrado'])->name('adm.usercadastrado');
    Route::get('/vendas', [DashboardController::class, 'vendas'])->name('adm.vendas');

    // Product Management (Assuming ProdutoController methods are for admin)
    Route::get('/produtos/{id}/edit', [ProdutoController::class, 'edit'])->name('adm.edit');
    Route::put('/produtos/{id}', [ProdutoController::class, 'update'])->name('adm.update');

    // Order Management (assuming PedidoController is for admin actions)
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('adm.pedidos'); // Listagem de pedidos ADM
    // ATENÇÃO: A rota abaixo foi removida, pois causava o erro `Method PedidoController::show does not exist.`
    Route::get('/pedidos/{id_pedido}', [PedidoController::class, 'show'])->name('adm.pedidos.detalhes');
    Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->name('adm.pedidos.detalhes');


    // Detalhes de pedido ADM (usando DashboardController::detalhePedido) - mantenha esta!
    Route::get('/pedidos/{id_pedido}', [DashboardController::class, 'detalhePedido'])->name('adm.detalhe_pedido');

    Route::patch('/pedidos/{id}/status', [PedidoController::class, 'alterarStatus'])->name('adm.pedidos.alterar-status');


    // Delivery Status and Tracking (Admin actions)
    Route::post('/pedidos/{id_pedido}/atualizar-status-entrega', [DashboardController::class, 'atualizarStatusEntrega'])->name('adm.atualizar_status_entrega');
    Route::post('/pedidos/{id_pedido}/adicionar-rastreio', [DashboardController::class, 'adicionarRastreio'])->name('adm.adicionar_rastreio');

    // Notifications (Admin)
    Route::get('/notificacoes', [NotificacaoController::class, 'index'])->name('adm.notificacoes');
    Route::post('/notificacoes/marcar-lida', [NotificacaoController::class, 'marcarComoLida'])->name('adm.notificacoes.marcar-lida');
    Route::post('/notificacoes/marcar-todas-lidas', [NotificacaoController::class, 'marcarTodasComoLidas'])->name('adm.notificacoes.marcar-todas-lidas');
    Route::get('/metricas', [NotificacaoController::class, 'metricas'])->name('adm.metricas');
    Route::post('/notificacoes/{notificacao}/marcar-lida', [NotificacaoController::class, 'marcarComoLida'])
        ->name('notificacoes.marcar-lida');
});


// Payment-related routes
Route::prefix('pagamento')->middleware(['auth', 'checkout.session'])->group(function () {
    Route::get('/cep', [PagamentoController::class, 'cep'])->name('pagamento.cep');
    Route::get('/buscar-cep', [PagamentoController::class, 'buscarCep'])->name('pagamento.buscar-cep');
    Route::post('/salvar-endereco', [PagamentoController::class, 'salvarEndereco'])->name('pagamento.salvar-endereco');
    Route::get('/forma-pagamento', [PagamentoController::class, 'formaPagamento'])->name('pagamento.forma-pagamento');
    Route::post('/salvar-forma-pagamento', [PagamentoController::class, 'salvarFormaPagamento'])->name('pagamento.salvar-forma-pagamento');
    Route::get('/revisao', [PagamentoController::class, 'revisao'])->name('pagamento.revisao');
    Route::get('/editar-endereco', [PagamentoController::class, 'editarEndereco'])->name('pagamento.editar-endereco');
    Route::post('/atualizar-endereco', [PagamentoController::class, 'atualizarEndereco'])->name('pagamento.atualizar-endereco');
    Route::post('/finalizar', [PagamentoController::class, 'processarPagamento'])->name('pagamento.finalizar');
    Route::get('/sucesso', [PagamentoController::class, 'sucesso'])->name('pagamento.sucesso');
    Route::get('/pagar/{pedidoId}', [PagamentoController::class, 'pagar'])->name('pagamento.pagar');
    Route::post('/pagar/{pedidoId}/confirmar', [PagamentoController::class, 'confirmarPagamento'])->name('pagamento.confirmar');
    Route::get('/erro', [PagamentoController::class, 'erro'])->name('pagamento.erro');
});


// Rotas de PEDIDOS para o CLIENTE (todas as ações do cliente em relação aos pedidos)
Route::prefix('minha-conta/pedidos')->name('cliente.pedidos.')->middleware('auth')->group(function () {
    // Rota para a listagem de todos os pedidos do cliente (se precisar de uma rota dedicada além do dashboard)
    // Route::get('/', [ClientePedidoController::class, 'meusPedidos'])->name('index');

    // Rota para ver os detalhes de um pedido específico
    Route::get('{pedido}', [ClientePedidoController::class, 'verDetalhesPedido'])->name('verDetalhesPedido');

    // Rota para o cliente cancelar um pedido
    Route::post('{pedido}/cancelar', [ClientePedidoController::class, 'cancelarPedido'])->name('cancelar');

    // Rota para o cliente confirmar o recebimento do pedido
    Route::post('{pedido}/confirmar-entrega', [ClientePedidoController::class, 'confirmarEntrega'])->name('confirmarEntrega');

    // Rota para a view de avaliação
    Route::get('{pedido}/avaliar', [ClientePedidoController::class, 'avaliarView'])->name('avaliar.view');

    // Rota para submeter as avaliações
    Route::post('{pedido}/avaliar', [ClientePedidoController::class, 'salvarAvaliacoes'])->name('avaliar.salvar');

    // Rota para o cliente solicitar reembolso
    Route::post('{pedido}/solicitar-reembolso', [ClientePedidoController::class, 'solicitarReembolso'])->name('solicitarReembolso');
});


require __DIR__ . '/auth.php';

