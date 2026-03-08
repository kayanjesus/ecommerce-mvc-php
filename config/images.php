<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações de Conversão de Imagens
    |--------------------------------------------------------------------------
    */

    'webp_quality' => env('WEBP_QUALITY', 80),

    'max_width' => env('IMAGE_MAX_WIDTH', 1920),

    'max_height' => env('IMAGE_MAX_HEIGHT', 1080),

    'keep_original' => env('KEEP_ORIGINAL_IMAGES', false),

    'allowed_mimes' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/bmp',
    ],
];