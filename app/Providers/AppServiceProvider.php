<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Respect\Validation\Validator as v;
use Illuminate\Support\Facades\Validator;
use App\Models\Categoria;
use Illuminate\Support\Facades\Schema;


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


        if (Schema::hasTable('categorias')) {
            $categoriasMenu = Categoria::all();
            view()->share('categoriasMenu', $categoriasMenu);
        }

        // Garante que a sessão persista para rotas de checkout
        $this->app->bind('checkout.session', function () {
            $session = app('session');

            // Rotas que devem manter a sessão
            if (request()->is('pagamento/*')) {
                $session->setName('checkout_session');
                $session->start();
            }

            return $session;
        });
    }
}
