<?php


use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\FavoritosController;
use App\Http\Controllers\DashboardController;
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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [SiteController::class, 'index'])->name('home.index');

Route::get('/produtos-home', [ProdutoController::class, 'index'])->name('produtos.home');
Route::resource('produtos', ProdutoController::class);

Route::get('/produtos/create', [ProdutoController::class, 'create'])->name('adm.pdtestoque');
Route::post('/produtos/store', [ProdutoController::class, 'store'])->name('produtos.store');


Route::get('/produto/{slug}', [SiteController::class, 'details'])->name('home.details');



Route::get('/categoria/{id_categoria}', [SiteController::class, 'categoria'])->name('home.categoria');
Route::get('/temporada/{temporada}', [SiteController::class, 'temporada'])->name('temporada');



// Carrinho
Route::get('/carrinho', [CarrinhoController::class, 'carrinhoLista'])
    ->name('home.carrinho')
    ->middleware('auth');
// Route::get('/carrinho', [CarrinhoController::class, 'carrinhoLista'])->name('home.carrinho');
Route::post('/carrinho', [CarrinhoController::class, 'adicionaCarrinho'])->name('home.addcarrinho');
Route::post('/remover', [CarrinhoController::class, 'removeCarrinho'])->name('home.removecarrinho');
Route::post('/atualizar', [CarrinhoController::class, 'atualizaCarrinho'])->name('home.atualizacarrinho');
Route::get('/limpar', [CarrinhoController::class, 'limparCarrinho'])->name('home.limparcarrinho');

// Favoritos
Route::get('/favoritos/lista', [FavoritosController::class, 'favoritosLista'])
    ->name('home.favoritos')
    ->middleware('auth');

Route::post('/add/favoritos', [FavoritosController::class, 'adicionaFavoritos'])->name('home.addfavoritos');
Route::post('/favoritos/remover', [FavoritosController::class, 'removeFavoritos'])->name('home.removefavoritos');
Route::post('/favoritos/atualizar', [FavoritosController::class, 'atualizaFavoritos'])->name('home.atualizafavoritos');
Route::get('/favoritos/limpar', [FavoritosController::class, 'limparFavoritos'])->name('home.limparfavoritos');


// login/cadastro
Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    // Admin
    Route::get('/admin/dashboard', function () {
        return view('adm.dashboard');
    })->name('adm.dashboard');

    // Usuário
    Route::get('/user/dashboard', function () {
        return view('home.dashboard');
    })->name('home.dashboard');


    // Route::get('/users', function () {
    //     return view('users');
    // })->name('dashboard.users');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


Route::middleware('auth')->group(function () {
    Route::get('cadastro', [PagesController::class, 'cadastro'])
        ->name('cadastro')
        ->middleware('can:access');
});

// Admin
// Em routes/web.php
// Route::view('/adm.sistema', 'adm.sistema')->middleware('auth');


Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('adm.dashboard');
Route::get('/pedidos', [DashboardController::class, 'pedidos'])->name('adm.pedidos');
Route::get('/pdtestoque', [DashboardController::class, 'pdtestoque'])->name('adm.pdtestoque');
Route::get('/cdtproduto', [DashboardController::class, 'cdtproduto'])->name('adm.cdtproduto');
Route::get('/usercadastrado', [DashboardController::class, 'usercadastrado'])->name('adm.usercadastrado');
Route::get('/vendas', [DashboardController::class, 'vendas'])->name('adm.vendas');
require __DIR__ . '/auth.php';
