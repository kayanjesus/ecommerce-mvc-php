<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator; // ADICIONE ESTA LINHA
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\Categoria;
use Illuminate\Support\Facades\v; // Se você está usando Validação CPF
use App\Services\ImageService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ImageService::class, function ($app) {
            return new ImageService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive(); // AGORA FUNCIONARÁ

        View::composer('layouts.cabecario', function ($view) {
            $categoriasTopo = Categoria::whereIn('nome_categoria', ['Bebê', 'Menina', 'Menino'])->get();
            $view->with('categoriasTopo', $categoriasTopo);
        });

        // Registrar a validação personalizada
        Validator::extend('cpf', function ($attribute, $value, $parameters, $validator) {
            return v::cpf()->validate($value);
        });

        if (Schema::hasTable('categorias')) {
            $categoriasMenu = Categoria::all();
            // ... resto do seu código
        }
    }
}