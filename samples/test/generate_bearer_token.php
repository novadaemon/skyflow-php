<?php

declare(strict_types=1);

use Novadaemon\Skyflow\Exceptions\SkyflowException;
use Novadaemon\Skyflow\ServiceAccount\Token;

require __DIR__ . '/../../vendor/autoload.php';

try {
    $path = '/Users/jesusgarcia/Downloads/credentials.json';

    $token = Token::generateBearerToken($path);

    var_export($token);
} catch (SkyflowException $e) {
    echo($e->getMessage());
}
