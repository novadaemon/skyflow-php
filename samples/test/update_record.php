<?php

declare(strict_types=1);

use Novadaemon\Skyflow\Vault\Client;
use Novadaemon\Skyflow\ServiceAccount\Token;

require __DIR__ . '/../../vendor/autoload.php';

$tokenProvider = function (string $token): string {
    if (Token::isExpired($token)) {
        $token = Token::generateBearerToken('/Users/jesusgarcia/Downloads/credentials.json');
        return $token['accessToken'];
    }

    return $token;
};

$client = new Client(
    vaultID: 't095f12b33174cdbb7c545c8c1b7cc6f',
    vaultUrl: 'https://ebfc9bee4242.vault.skyflowapis.com',
    tokenProvider: $tokenProvider
);


$record =  [
    'fields' => [
        "email" => 'jdamian@novadaemon.com'
    ]
];

$response = $client->updateRecord(
    table: 'consumers',
    id: '76193681-d272-4ffe-80f9-6498398e4c7b',
    record: $record,
    // tokenization: true
);

var_export($response);
