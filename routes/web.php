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
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\CheckoutController;

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

// Route::get('/', function () {
//     return view('home.index');
// });

// Route::get('/', function () {F
//     return view('welcome');
// });


Route::get('/', [SiteController::class, 'index'])->name('home.index');

Route::get('/produtos-home', [ProdutoController::class, 'index'])->name('produtos.home');
Route::resource('produtos', ProdutoController::class);

Route::get('/produto/{slug}', [SiteController::class, 'details'])->name('home.details');


Route::get('/categoria/{id_categoria}', [SiteController::class, 'categoria'])->name('home.categoria');
Route::get('/temporada/{temporada}', [SiteController::class, 'temporada'])->name('temporada');




// CRUD ADM
Route::get('/produtos/create', [ProdutoController::class, 'create'])->name('adm.pdtestoque');
Route::post('/produtos/store', [ProdutoController::class, 'store'])->name('produtos.store');



// Carrinho

Route::middleware(['web'])->group(function () {
    Route::post('/adicionar-carrinho', [CarrinhoController::class, 'adicionaCarrinho'])->name('home.addcarrinho');
    Route::get('/carrinho', [CarrinhoController::class, 'carrinhoLista'])
        ->name('home.carrinho')
        ->middleware('auth');
    Route::post('/remover', [CarrinhoController::class, 'removeCarrinho'])->name('home.removecarrinho');
    Route::post('/atualizar', [CarrinhoController::class, 'atualizaCarrinho'])->name('home.atualizacarrinho');
    Route::get('/limpar', [CarrinhoController::class, 'limparCarrinho'])->name('home.limparcarrinho');

});

// login/cadastro
Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    // Admin
    Route::get('/admin/dashboard', function () {
        return view('adm.dashboard');
    })->name('adm.dashboard');

    // Usuário
    // ALTERADO: Aponta para o método userDashboard do ProfileController
    // Adicionado parâmetro opcional 'show' para alternar entre pedidos e favoritos
    Route::get('/user/dashboard', [ProfileController::class, 'userDashboard'])->name('home.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Favoritos (Estas rotas permanecem as mesmas para adicionar/remover/limpar)
Route::prefix('favoritos')->group(function () {
    Route::get('/', [FavoritosController::class, 'favoritosLista'])
        ->name('home.favoritos'); // Esta rota ainda pode ser usada se você quiser uma página de favoritos standalone
    // Mas o dashboard agora também pode mostrá-los.

    Route::post('/adicionar', [FavoritosController::class, 'adicionaFavoritos'])
        ->name('home.addfavoritos');

    Route::post('/remover', [FavoritosController::class, 'removeFavoritos'])
        ->name('home.removefavoritos');

    Route::get('/limpar', [FavoritosController::class, 'limparFavoritos'])
        ->name('home.limparfavoritos');

});


Route::middleware('auth')->group(function () {
    Route::get('cadastro', [PagesController::class, 'cadastro'])
        ->name('cadastro')
        ->middleware('can:access');
    Route::get('/checkout', [CheckoutController::class, 'showSummary'])->name('checkout.summary');
    Route::post('/checkout/processar', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
});


Route::post('/webhooks/pagseguro', [WebhookController::class, 'handle'])->name('webhooks.pagseguro')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Rotas para produtos
Route::resource('produtos', ProdutoController::class)->except(['show']);
Route::prefix('adm')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('adm.dashboard');
    Route::get('/pedidos', [DashboardController::class, 'pedidos'])->name('adm.pedidos');
    // NOVA ROTA AQUI
    Route::get('/pedidos/{id_pedido}', [DashboardController::class, 'detalhePedido'])->name('adm.detalhe_pedido');
    Route::get('/pdtestoque', [DashboardController::class, 'pdtestoque'])->name('adm.pdtestoque');
    Route::get('/cdtproduto', [DashboardController::class, 'cdtproduto'])->name('adm.cdtproduto');
    Route::get('/usercadastrado', [DashboardController::class, 'usercadastrado'])->name('adm.usercadastrado');
    Route::get('/vendas', [DashboardController::class, 'vendas'])->name('adm.vendas');
    Route::get('/produtos/{id}/edit', [ProdutoController::class, 'edit'])->name('adm.edit');
    Route::put('/produtos/{id}', [ProdutoController::class, 'update'])->name('adm.update');
});

// Rotas de pagamento (acessíveis a usuários comuns)
Route::prefix('pagamento')->middleware(['auth', 'checkout.session'])->group(function () {
    Route::get('/cep', [PagamentoController::class, 'cep'])->name('pagamento.cep');
    Route::get('/buscar-cep', [PagamentoController::class, 'buscarCep'])->name('pagamento.buscar-cep');
    Route::post('/salvar-endereco', [PagamentoController::class, 'salvarEndereco'])->name('pagamento.salvar-endereco');
    Route::get('/forma-pagamento', [PagamentoController::class, 'formaPagamento'])->name('pagamento.forma-pagamento');
    Route::post('/salvar-forma-pagamento', [PagamentoController::class, 'salvarFormaPagamento'])->name('pagamento.salvar-forma-pagamento');
    Route::get('/revisao', [PagamentoController::class, 'revisao'])->name('pagamento.revisao');
    Route::get('/editar-endereco', [PagamentoController::class, 'editarEndereco'])->name('pagamento.editar-endereco');
    Route::post('/atualizar-endereco', [PagamentoController::class, 'atualizarEndereco'])->name('pagamento.atualizar-endereco');

    // ESTA É A LINHA QUE VOCÊ VAI ALTERAR:
    Route::post('/finalizar', [PagamentoController::class, 'processarPagamento'])
        ->name('pagamento.finalizar');

    Route::get('/sucesso', [PagamentoController::class, 'sucesso'])
        ->name('pagamento.sucesso')
        ->middleware('auth'); // Remova o checkout.session aqui (já está feito, bom!)

    Route::get('/pagar/{pedidoId}', [PagamentoController::class, 'pagar'])
        ->name('pagamento.pagar');

    // Atualize a rota de confirmação fictícia
    Route::post('/pagar/{pedidoId}/confirmar', [PagamentoController::class, 'confirmarPagamento'])
        ->name('pagamento.confirmar');

    Route::get('/erro', [PagamentoController::class, 'erro'])->name('pagamento.erro');
});


// Rotas de administração de pedidos
Route::prefix('adm')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('adm.pedidos');
    Route::patch('/pedidos/{id}/status', [PedidoController::class, 'alterarStatus'])->name('adm.pedidos.alterar-status');
    Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->name('adm.pedidos.detalhes');
    // Notificações
    Route::get('/notificacoes', [NotificacaoController::class, 'index'])->name('adm.notificacoes');
    Route::post('/notificacoes/marcar-lida', [NotificacaoController::class, 'marcarComoLida'])->name('adm.notificacoes.marcar-lida');
    Route::post('/notificacoes/marcar-todas-lidas', [NotificacaoController::class, 'marcarTodasComoLidas'])->name('adm.notificacoes.marcar-todas-lidas');
    Route::get('/metricas', [NotificacaoController::class, 'metricas'])->name('adm.metricas');
    Route::post('/notificacoes/{notificacao}/marcar-lida', [NotificacaoController::class, 'marcarComoLida'])
        ->name('notificacoes.marcar-lida')
        ->middleware('auth');
});

require __DIR__ . '/auth.php';
