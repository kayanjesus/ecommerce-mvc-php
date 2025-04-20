<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Respect\Validation\Validator as v;
use Illuminate\Support\Facades\Validator; 
use App\Models\Categoria;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Registrar a validação personalizada
        Validator::extend('cpf', function ($attribute, $value, $parameters, $validator) {
            return v::cpf()->validate($value);
        });


        $categoriasMenu = Categoria::all();
        view()->share('categoriasMenu', $categoriasMenu);

    }
}
