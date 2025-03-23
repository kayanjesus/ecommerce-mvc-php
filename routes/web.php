<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('site.index');
});



// Route::get('/', function () {
//     return view('welcome');
// });

Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function(){
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



// Route::middleware('auth')->group(function () {

// });
require __DIR__.'/auth.php';
