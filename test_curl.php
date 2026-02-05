<?php

$email = 'kayandasilvajesus7@gmail.com';
$token = '62a0f5aa-eb1e-4d3c-ad5d-b0e2230dd44d26fa0d7e462ea977fa349b0b9a3dfed1645c-91e0-4ffc-8266-504973839419';

$url = 'https://ws.sandbox.pagseguro.uol.com.br/v2/sessions';

echo "Testando PagSeguro...\n";
echo "URL: $url\n\n";

// Usando file_get_contents
$data = http_build_query([
    'email' => $email,
    'token' => $token
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => $data,
        'ignore_errors' => true
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
    ]
]);

$response = @file_get_contents($url, false, $context);

if ($response === false) {
    echo "ERRO: Não foi possível conectar ao PagSeguro\n";
    echo "Último erro: " . error_get_last()['message'] . "\n";
} else {
    echo "Resposta:\n";
    echo "----------------------------------------\n";
    echo $response . "\n";
    echo "----------------------------------------\n";
    
    // Tentar parse XML
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($response);
    
    if ($xml !== false) {
        if (isset($xml->id)) {
            echo "\n✅ SUCESSO! Session ID: " . $xml->id . "\n";
        } elseif (isset($xml->error)) {
            echo "\n❌ ERRO: " . $xml->error->message . " (Code: " . $xml->error->code . ")\n";
        }
    } else {
        echo "\n❌ Não é um XML válido. Possíveis problemas:\n";
        echo "1. Token inválido\n";
        echo "2. Email errado\n";
        echo "3. Problema de rede/firewall\n";
    }
}