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

$params =  [
    [
        'token' => '89bf8296-059a-4a49-b0ab-c189835c4f75',
    ]
];

$response = $client->detokenize($params);

var_export($response);
