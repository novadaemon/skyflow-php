<?php

declare(strict_types=1);

use Novadaemon\Skyflow\Vault\Client;
use Novadaemon\Skyflow\ServiceAccount\Token;
use Novadaemon\Skyflow\Types\RequestMethodType;

require __DIR__ . '/../vendor/autoload.php';

$tokenProvider = function (string $token): string {

    if (Token::isExpired($token)) {
        $token = Token::generateBearerToken('<YOUR_CREDENTIALS_FILE_PATH>');
        return $token['accessToken'];
    }

    return $token;
};

$client = new Client(
    vaultID: '<YOUR_VAULT_ID>', 
    vaultUrl: '<YOUR_VAULT_URL>', 
    tokenProvider: $tokenProvider
);

$headers = [
    'Content-Type' => 'application/json',
    'Authorization' => '<YOUR_CONNECTION_BASIC_AUTH>'
];

$body = [
    '<FIELD_NAME>' => '<RAW_FIELD_VALUE>',
    '<FIELD_NAME>' => '<RAW_FIELD_VALUE>',
    '<FIELD_NAME>' => '<TOKENIZED_FIELD_VALUE>',
];

$url = '<CONNECTION_URL>';

$response = $client->invokeConnection(
    connectionUrl: $url, 
    method: RequestMethodType::POST, 
    headers: $headers, 
    body: $body
);

var_dump($response);
