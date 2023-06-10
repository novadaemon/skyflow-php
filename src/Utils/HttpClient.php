<?php

declare(strict_types=1);

namespace Novadaemon\Skyflow\Utils;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Novadaemon\Skyflow\Exceptions\SkyflowException;

abstract class HttpClient
{
    /**
     * Make an HTTP Request
     *
     * @param string method
     * @param string url
     * @param string array|null $query
     * @param string array|null $body
     * @param string array|null $headers
     * @return array
     * @throws \Novadaemon\Skyflow\Exceptions\SkyflowException
     */
    public static function request(string $method, string $url, ?array $options = null): array
    {
        try {
            $client = new Client();
            $response = $client->request($method, $url, $options);
            return json_decode((string) $response->getBody(), true);
        } catch (BadResponseException $e) {
            $response = json_decode((string) $e->getResponse()->getBody(), true);

            if (array_key_exists('error', $response)) {
                return $response;
            }

            throw new SkyflowException((string) $e->getResponse()->getBody());
        }
    }

    /**
     * Make a request with Authorization header
     *
     * @param string $method
     * @param string $url
     * @param string $token
     * @param array $options
     * @return array
     */
    public static function authorizedRequest(string $method, string $url, string $token, array $options = []): array
    {
        if (array_key_exists('headers', $options)) {
            $options['headers']['Authorization'] = 'Bearer ' . $token;
        } else {
            $options['headers'] = ['Authorization' => 'Bearer ' . $token];
        }
        return self::request($method, $url, $options);
    }

    public static function buildQueryString(array $parameters): string
    {
        $query = http_build_query($parameters);

        return preg_replace('/%5B[0-9]+%5D/', '', $query);
    }
}
