<?php

declare(strict_types=1);

use Novadaemon\Skyflow\ServiceAccount\Token;
use Novadaemon\Skyflow\Exceptions\SkyflowException;

require __DIR__ . '/../vendor/autoload.php';

try {
    $credentials = [
        'clientID' => '<CLIENT_ID>',
        'clientName' => '<CLIENT_NAME>',
        'tokenURI' => '<TOKEN_URI>',
        'keyID' => '<KEY_ID>',
        'privateKey' => '<PRIVATE_KEY>',
    ];

    $token = Token::generateBearerTokenFromCredentials($credentials);

    var_dump($token);
} catch (SkyflowException $e) {
    echo($e->getMessage());
}
