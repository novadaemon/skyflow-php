<?php

declare(strict_types=1);

use Novadaemon\Skyflow\ServiceAccount\Token;
use Novadaemon\Skyflow\Exceptions\SkyflowException;

require __DIR__ . '/../vendor/autoload.php';

try {
    $path = '<YOUR_CREDENTIALS_FILE_PATH>';

    $token = Token::generateBearerToken($path);

    var_dump($token);
} catch (SkyflowException $e) {
    echo($e->getMessage());
}
