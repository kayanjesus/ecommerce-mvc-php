<?php

return [
    // Credenciais API V2 (legado — mantido só por compatibilidade)
    'email' => env('PAGSEGURO_EMAIL'),
    'token' => env('PAGSEGURO_TOKEN'),

    // Credenciais API V4 (a que o projeto usa para orders/refunds/notifications)
    'bearer_token' => env('PAGSEGURO_BEARER_TOKEN'),

    // true = sandbox, false = produção real.
    // Use esta chave em TODO lugar do código — não crie outra tipo "environment".
    'sandbox' => (bool) env('PAGSEGURO_SANDBOX', true),

    // Chave usada para validar a assinatura HMAC dos webhooks recebidos.
    // Sem ela, o WebhookController recusa processar em produção.
    'webhook_secret' => env('PAGSEGURO_WEBHOOK_SECRET'),

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
];
