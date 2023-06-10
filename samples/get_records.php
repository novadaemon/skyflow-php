<?php

declare(strict_types=1);

use Novadaemon\Skyflow\Vault\Client;
use Novadaemon\Skyflow\ServiceAccount\Token;
use Novadaemon\Skyflow\Types\RedactionType;

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


$response = $client->getRecords(
    table: '<TABLE_NAME>',
    ids: ['<SKYFLOW_RECORD_ID>', '<SKYFLOW_RECORD_ID>'],
    redaction: RedactionType::DEFAULT,
    tokenization: true,
    fields: ['<FIELD_NAME>', 'FIELD_NAME']
);

var_dump($response);
