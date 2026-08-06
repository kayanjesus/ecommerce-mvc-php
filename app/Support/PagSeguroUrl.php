<?php

namespace App\Support;

/**
 * Resolve as URLs base da API do PagSeguro (V2 e V4) de acordo com o
 * modo sandbox/produção configurado em config('pagseguro.sandbox').
 *
 * Isso substitui as Closures que existiam dentro de config/pagseguro.php.
 * Closures dentro de arrays de config NÃO podem ser serializadas pelo
 * `php artisan config:cache`, então quebravam o deploy em produção.
 */
class PagSeguroUrl
{
    public static function v2(): string
    {
        return config('pagseguro.sandbox')
            ? config('pagseguro.urls.v2.sandbox')
            : config('pagseguro.urls.v2.production');
    }

    public static function v4(): string
    {
        return config('pagseguro.sandbox')
            ? config('pagseguro.urls.v4.sandbox')
            : config('pagseguro.urls.v4.production');
    }
}
