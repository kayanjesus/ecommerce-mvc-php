<?php

$email = 'kayandasilvajesus7@gmail.com';
$token = '62a0f5aa-eb1e-4d3c-ad5d-b0e2230dd44d26fa0d7e462ea977fa349b0b9a3dfed1645c-91e0-4ffc-8266-504973839419';

echo "Testando conexão DIRETA com PagSeguro Sandbox...\n\n";

// Teste 1: API V2
echo "=== API V2 ===\n";
$url = "https://ws.sandbox.pagseguro.uol.com.br/v2/sessions";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'email' => $email,
    'token' => $token
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // TRUE para verificar SSL
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_CAINFO, 'C:/laragon/etc/ssl/cacert.pem'); // Ajuste o caminho

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

echo "HTTP Code: $httpCode\n";
echo "cURL Error: " . ($curlError ?: 'Nenhum') . "\n";
echo "Response: $response\n\n";

curl_close($ch);