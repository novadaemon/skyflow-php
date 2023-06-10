<?php


declare(strict_types=1);

namespace Novadaemon\Skyflow\ServiceAccount;

use DateTime;
use Firebase\JWT\JWT;
use Novadaemon\Skyflow\Exceptions\ErrorMessages;
use Novadaemon\Skyflow\Exceptions\SkyflowException;
use Novadaemon\Skyflow\Utils\HttpClient;

abstract class Token
{
    /**
     * This function is used to get the access token for skyflow Service Accounts.
     * `credentialsFilePath` is the file path in string of the credentials file that is downloaded after Service Account creation.
     * Response Token is a named tupe with two attributes:
     *  1. accessToken: The access token
     *  2. tokenType: The type of access token (eg: Bearer)
     *
     * @param string $credentialsPath Path to json file with Service Account credentials
     * @return array
     * @throws \Novadaemon\Skyflow\Exceptions\SkyflowException
     */
    public static function generateBearerToken(string $credentialsPath): array
    {
        $content =  file_get_contents($credentialsPath);
        $credentials = json_decode($content, true);

        return self::getSABearerToken($credentials);
    }

    /**
     * This function is used to get the access token for skyflow Service Accounts.
     * `credentials` arg takes the content of the credentials file that is downloaded after Service Account creation.
     * Response Token is a named tupe with two attributes:
     *  1. accessToken: The access token
     *  2. tokenType: The type of access token (eg: Bearer)
     *
     * @param array $json JSON with Service Accounts credentials
     * @return array
     * @throws \Novadaemon\Skyflow\Exceptions\SkyflowException
     */
    public static function generateBearerTokenFromCredentials(array $credentials): array
    {
        return self::getSABearerToken($credentials);
    }

    /**
     * Generate a signed JWT using credentials
     *
     * @param string $clientID
     * @param string $keyID
     * @param string $tokenURI
     * @param string $privateKey
     * @return string
     */
    private static function getSignedJW(string $clientID, string $keyID, string $tokenURI, string $privateKey): string
    {
        $payload = [
            "iss" => $clientID,
            "key" => $keyID,
            "aud" => $tokenURI,
            "sub" => $clientID,
            "exp" => time() + 3600
        ];

        return JWT::encode($payload, $privateKey, 'RS256');
    }

    /**
     * Make the request to generate Bearer token
     *
     * @param string $url
     * @param string $jwt
     * @return array
     */
    private static function sendRequestWithToken(string $url, string $jwt): array
    {
        $options = [
            'json' => [
                "grant_type" => "urn:ietf:params:oauth:grant-type:jwt-bearer",
                "assertion" =>  $jwt
            ]
        ];

        return HttpClient::request(method: 'POST', url: $url, options: $options);
    }

    /**
     * @param array $credentials
     * @return array
     */
    private static function getSABearerToken(array $credentials): array
    {
        $jwt = self::getSignedJW($credentials['clientID'], $credentials['keyID'], $credentials['tokenURI'], $credentials['privateKey']);

        $token = self::sendRequestWithToken($credentials['tokenURI'], $jwt);

        if (!array_key_exists('accessToken', $token)) {
            throw new SkyflowException(ErrorMessages::INVALID_TOKEN->value);
        }

        return $token;
    }

    /**
     * Check if stored token is not expired, if not return a new token,
     * if the token has expiry time before 5min of current time, call returns False
     *
     * @param string $token
     * @return bool
     */
    public static function isExpired(string $token): bool
    {
        if (strlen($token) == 0) {
            return true;
        }

        list($header, $payload, $signature) = explode(".", $token);

        $json = base64_decode($payload);

        $decode = json_decode($json, true);

        $expire = date_timestamp_set(new DateTime(), (int) $decode['exp']);

        if ((new DateTime('now')) < $expire) {
            return false;
        }

        return true;
    }
}
