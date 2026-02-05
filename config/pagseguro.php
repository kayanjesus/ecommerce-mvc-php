<?php

return [
    // Credenciais API V2 (para compras e reembolsos)
    'email' => env('PAGSEGURO_EMAIL'),
    'token' => env('PAGSEGURO_TOKEN'),

    // Credenciais API V4 (nova)
    'bearer_token' => env('PAGSEGURO_BEARER_TOKEN'),

    // Ambiente
    'sandbox' => env('PAGSEGURO_SANDBOX', true),

    // URLs
    'urls' => [
        'v2' => [
            'production' => 'https://ws.pagseguro.uol.com.br',
            'sandbox' => 'https://ws.sandbox.pagseguro.uol.com.br',
        ],
        'v4' => [
            'production' => 'https://api.pagseguro.com',
            'sandbox' => 'https://sandbox.api.pagseguro.com',
        ],
    ],

    // Método para obter URL correta
    'getV2Url' => function () {
        $sandbox = config('pagseguro.sandbox', true);
        return $sandbox
            ? config('pagseguro.urls.v2.sandbox')
            : config('pagseguro.urls.v2.production');
    },

    'getV4Url' => function () {
        $sandbox = config('pagseguro.sandbox', true);
        return $sandbox
            ? config('pagseguro.urls.v4.sandbox')
            : config('pagseguro.urls.v4.production');
    },
];