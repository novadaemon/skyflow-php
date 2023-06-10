<?php

declare(strict_types=1);

use Novadaemon\Skyflow\Vault\Client;
use Novadaemon\Skyflow\ServiceAccount\Token;
use Novadaemon\Skyflow\Types\RedactionType;

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


$response = $client->getRecords(
    table: 'psp_configurations',
    ids: [
        'ab819b41-cb16-40cb-916f-bcaac3715707'
    ],
    // redaction: RedactionType::MASKED,
    tokenization: true
);

var_export($response);
