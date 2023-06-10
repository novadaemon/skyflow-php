<?php

declare(strict_types=1);

use Novadaemon\Skyflow\Vault\Client;
use Novadaemon\Skyflow\ServiceAccount\Token;

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

$records = [
    [
        "fields" => [
            "<FIELD_NAME>" => "<FIELD_VALUE>",
            "<FIELD_NAME>" => "<FIELD_VALUE>",
        ]
    ],
    [
        "fields" => [
            "<FIELD_NAME>" => "<FIELD_VALUE>",
            "<FIELD_NAME>" => "<FIELD_VALUE>",
        ]
    ],
];

$response = $client->insertRecords(
    table: '<TABLE_NAME>',
    records: $records,
    tokenization: true,
    upsert: '<UNIQUE_FIELD_NAME>'
);

var_dump($response);
