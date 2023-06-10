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

$records = [
    // [
    //     "fields" => [
    //         "card_number" => "4242424242424242",
    //         "expiry_month" => "12",
    //         "expiry_year" => "2023",
    //         "card_cvv" => "123",
    //         "card_holder_name" => "Jesús García",
    //     ]
    // ],
    [
        'fields' => [
            'first_name' => 'Jesús',
            'last_name' => 'García',
            'email' => 'jdamian@novadaemon.com',
            'phone' => '+533567890',
            'address' => [
                "street" => "STRING",
                "address" => "STRING",
                "address2" => "STRING",
                "number_ext" => "STRING",
                "number_int" => "STRING",
                "country" => "STRING",
                "country_code" => "STRING",
                "state" => "STRING",
                "city" => "STRING",
                "zip_code" => "STRING",
            ]
        ]
    ]
    // [
    //     "fields" => [
    //         "configurations" => json_encode(
    //             [
    //                 [
    //                     'key' => 'private_key',
    //                     'value' => 'sk_test_51JV0DLGdcZNbShQE3vHQcKHZ6bZkSvjaTkDdagQ1QuGJMBoiHv69Ha9GuX7jwxbCMzFVwbwqAAD23KLML9FIg2DQ00QoxNEjUR'
    //                 ]
    //             ]
    //         ),
    //     ]
    // ],
];

$response = $client->insertRecords(
    table: 'consumers',
    records: $records,
    tokenization: true,
    upsert: 'email'
);

var_export($response);
