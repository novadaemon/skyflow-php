<?php

declare(strict_types=1);

use Novadaemon\Skyflow\Vault\Client;
use Novadaemon\Skyflow\ServiceAccount\Token;
use Novadaemon\Skyflow\Types\RequestMethodType;

require __DIR__ . '/../../vendor/autoload.php';

$tokenProvider = function (string $token): string {
    if (Token::isExpired($token)) {
        $token = Token::generateBearerToken('/Users/jesusgarcia/Downloads/credentials_stripe_connection.json');
        return $token['accessToken'];
    }

    return $token;
};

$client = new Client(
    vaultID: 't095f12b33174cdbb7c545c8c1b7cc6f',
    vaultUrl: 'https://ebfc9bee4242.vault.skyflowapis.com',
    tokenProvider: $tokenProvider
);

$headers = [
    'Content-Type' => 'application/x-www-form-urlencoded',
    'Authorization' => 'Bearer sk_test_51JV0DLGdcZNbShQE3vHQcKHZ6bZkSvjaTkDdagQ1QuGJMBoiHv69Ha9GuX7jwxbCMzFVwbwqAAD23KLML9FIg2DQ00QoxNEjUR'
];

$body = [
    'type' => 'card',
    'card' => [
        'cvc' => 'bedf1b12-5197-433c-a193-083639fef3a2',
        'number' => '2690-9830-0664-3614',
        'exp_month' => '9783271b-da10-47ee-862f-85cd4969c990',
        'exp_year' => 'cf22116b-9135-4579-a1df-d7c29dfe0159',
    ],
    "billing_details" => [
        "address" => [
            "city" => "Guadalajara",
            "country" => "MX",
            "line1" => "Portobelo 345",
            "line2" => "",
            "postal_code" => "3600",
            "state" => "Jalisco"
        ],
        "email" => "malukchdagraaiqbzgbk@pzywgbrybn.com",
        "name" => 'Jesús García',
        "phone" => "076cd12f-a091-4c05-bcca-18324cce7fcc"
    ],
];

$url = 'https://ebfc9bee4242.gateway.skyflowapis.com/v1/gateway/outboundRoutes/cf3c64cb8a6a4c58979f148f51c7e367/v1/payment_methods';

$response = $client->invokeConnection(
    connectionUrl: $url,
    method: RequestMethodType::POST,
    headers: $headers,
    body: $body
);

var_export($response);
