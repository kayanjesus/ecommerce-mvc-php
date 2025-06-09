<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Categoria; // Certifique-se de que este é o caminho correto para seu modelo Categoria

class SharedDataServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Este View Composer garante que $categoriasTopo esteja disponível em 'layouts.app'
        View::composer('layouts.app', function ($view) {
            // Adapte esta linha para como você busca suas categorias de topo
            $categoriasTopo = Categoria::all();
            $view->with('categoriasTopo', $categoriasTopo);
        });
    }
}

