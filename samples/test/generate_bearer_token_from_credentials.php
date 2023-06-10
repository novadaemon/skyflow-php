<?php

declare(strict_types=1);

use Novadaemon\Skyflow\ServiceAccount\Token;
use Novadaemon\Skyflow\Exceptions\SkyflowException;

require __DIR__ . '/../../vendor/autoload.php';

try {
    $credentials = [
        "clientID" => "e75dfcf3307d43b6b6e7ba596e598817",
        "clientName" => "TestService",
        "tokenURI" => "https://manage.skyflowapis.com/v1/auth/sa/oauth/token",
        "keyID" => "be694a2ea1f647d397a5c205feb4f351",
        "privateKey" => "-----BEGIN PRIVATE KEY-----\nMIIEwAIBADANBgkqhkiG9w0BAQEFAASCBKowggSmAgEAAoIBAQC+3ayEqSDab/6z\nWw+Gm9u0mWzllBnetPQrF/tlAU8HK6Z6vgOqWtBTh/HLhDmfzf4bDLJJyn6fsS/y\n5+2FFRfp0xlFY3QXS6R8sAtEv1Lf2wl55QiUHV6sBWNn5jt4W3VKO/mZZt9f+aAd\nWNVtIsywJGHRcmGTGsF5WQ3TtMIZQw/DFOie0rerPeaMOU7NYtZq6f9i6p51HIh2\nEGfVKqozgf+NhvUih45wRYf7vOdO4lqpzNp6pEappZ4unIiUV2NTYNvXeIhsgreR\nKsljBdXb7Z7bg3fj56zjwHPQU3PbaK4ZXWKHMamkROXfZSStlPfhWgatVSDsMj6g\nRkkndxvlAgMBAAECggEBAKlMz/5MVwvrU62cFEV+cn/bp7BskhXtxLeE6kVJx+PA\nYEd3zKCLNUciyuOPQd3yRneP54V0zKSx8qov94uYjkGmMkBOW2defWTsctJkMwrF\nq74n3lgjRRqpHOfIXPABkCRs1pWvnmvvbIsbhRTtTUrgurOiFdL6ZKtSxuUG7TSh\nsNx02i1LY2lz1CC9sRKbGbX3Boh+9KiXbBydS6ck8qLm+gndEn8wVb0qkdC4MfQu\njoXiJee2lnPdYGydVOHwEmo8cNjtIUjG8lyLACaIDGHlKrfi00YQjGYDRcqMANnl\nHpRkLomeNWVFFw5M6O6v9SJI6hEplxIy6frZzj9LLnECgYEA99+b7rGqQyP7WNts\ns/I04F8PQW6EjEMCU4XwdF3nJn7bwTv+NWuVWgpyJuPAFNWRovv4wrWBFDm4RNx4\nbLCRAlYZiIghYhEssXXMf81QxyrfMymbIe7q6Sc7QMCF/5iV1xojDTags1kYA2kw\nuiqwLy8bbIQMYRWp1hD9bi2ZjkMCgYEAxR+aWGL6egnGAgSjlskolk27TAE7yi/v\nc67IxddDZqQ1GP0J3ai8YvKjqhyBfLjVQlNNNxBaCnUx3syX52Znd51VlH9QsWbf\ndeT8C4moXJg2VXFFYeDjq3ydPrKBUwxZXTeeg9QGOuOa9be7poKSnELa+EB8GKd5\nZkwVgKieTrcCgYEAlQt46MMd3PdS2HBAYcde8hdWxgJdYzWbvXtJRb99EVGwc/XN\n+nMxQA4N68KAqkZtJvKZ9wJKlk3KtmVXaAuXdi8tdUJdyGkJVb++co6TZt8pjMsU\nxAe0avOzFlFtgW5PkyLdNsHFSXJmFQ74RMkMXaLWu55/DxWGpPhcWxbCQZ8CgYEA\niWmHWXfd7XakrkF3nCQsA+K53ri5ai9SN40u7iMqHAH59apm1zrJ4BwOlQX8Cep6\n1xpkqC7g5YtesVZjye4r0ElAIB1ELfZtbayrOovCbpG4xoUIfbucWa2rm8optq/U\nF9QuzuzdTu3c1s82o1NASmecZxPkrfcI/JOrJZ3lhS8CgYEAoacBB3EqHuXGaJPS\nnWEy4CCZVlyAxxuUwWcpbYLyrKA85C+onbUlhUrp5xqFEFUnjrS5X2dl7qq7mhgH\nQQOsfPE8pFL315E+knFeT+KmoH6B0GduXrQbX9QjmSXmFW8Mf73Ckec4PqkFZNa3\ncWMYAgp9Qwaqqqo+7G9/CnJEQNA=\n-----END PRIVATE KEY-----\n",
    ];

    $token = Token::generateBearerTokenFromCredentials($credentials);

    var_export($token);
} catch (SkyflowException $e) {
    echo($e->getMessage());
}
