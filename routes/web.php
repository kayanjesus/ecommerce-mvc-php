<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\SiteController;

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
Route::get('/', [ProdutoController::class, 'index']);
Route::resource('produtos', ProdutoController::class);

Route::get('/', [SiteController::class, 'index'])->name('home.index');

Route::get('/produto/{slug}', [SiteController::class, 'details'])->name('home.details');

Route::get('/categoria/{id}', [SiteController::class, 'categoria'])->name('home.categoria');


// Route::get('/', function () {
//     return view('home.index');
// });

// Route::get('/', function () {
//     return view('welcome');
// });

Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/users', function () {
        return view('users');
    })->name('dashboard.users');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


Route::middleware('auth')->group(function () {
    Route::get('cadastro', [PagesController::class, 'cadastro'])
        ->name('cadastro')
        ->middleware('can:access');
});

require __DIR__ . '/auth.php';
