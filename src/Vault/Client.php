<?php

declare(strict_types=1);

namespace Novadaemon\Skyflow\Vault;

use Novadaemon\Skyflow\Utils\HttpClient;
use Novadaemon\Skyflow\Types\RedactionType;
use Novadaemon\Skyflow\Exceptions\ErrorMessages;
use Novadaemon\Skyflow\Exceptions\SkyflowException;
use Novadaemon\Skyflow\Types\RequestMethodType;

class Client
{
    private $storedToken = '';
    private $vaultUrl;
    private $tokenProvider;

    /**
     * @param string $vaultID
     * @param string $vaultUrl
     * @param callable $tokenProvider
     * @return Client
     * @throws \Novadaemon\Skyflow\Exceptions\SkyflowException;
     */
    public function __construct(private string $vaultID, string $vaultUrl, callable $tokenProvider)
    {
        if (strlen($vaultID) == 0) {
            throw new SkyflowException(ErrorMessages::EMPTY_VAULT_ID->value);
        }

        if (filter_var($vaultUrl, FILTER_VALIDATE_URL) != true) {
            throw new SkyflowException(sprintf(ErrorMessages::INVALID_URL->value, $vaultUrl));
        }

        $this->vaultUrl = rtrim($vaultUrl, '/');
        $this->tokenProvider = $tokenProvider;
    }

    /**
     * Gets the specified records from a table.
     *
     * @param string $table Name of the table that contains the records.
     * @param array|null $ids Values of the records to return. If not specified, this operation returns all records in the table.
     * @param RedactionType|null $redaction Redaction level to enforce for the returned records. Subject to policies assigned to the API caller.
     * @param boolean|null $tokenization If true, this operations returns tokens instead of field values where applicable. Only applicable if skyflow_id values are specified.
     * @param array|null $fields Fields to return for the records. If not specified, all fields are returned.
     * @param string|null $columnName Name of the column. It must be configured as unique in the schema.
     * @param array|null $columnValues Column values of the records to return. column_name is mandatory when providing column_values
     * @param integer|null $offset Record position at which to start receiving data. Defautl 0
     * @param integer|null $limit Number of record to return. Maximum 25. Default 25
     * @return array
     */
    public function getRecords(string $table, ?array $ids = null, ?RedactionType $redaction = RedactionType::DEFAULT, ?bool $tokenization = null, ?array $fields = null, ?string $columnName = null, ?array $columnValues = null, ?int $offset = null, ?int $limit = null): array
    {
        if ($columnName && $ids) {
            throw new SkyflowException(ErrorMessages::INVALID_GET_RECORDS_PARAMS->value);
        }

        if (($columnName && !$columnValues)) {
            throw new SkyflowException(ErrorMessages::INVALID_GET_RECORDS_COLUMN_NAME_PARAM->value);
        }

        if ($tokenization && !$ids) {
            throw new SkyflowException(ErrorMessages::INVALID_GET_RECORDS_TOKENIZATION_PARAMS->value);
        }

        $tokenProvider = $this->tokenProvider;
        $this->storedToken = $tokenProvider($this->storedToken);
        $url = $this->getCompleteVaultUrl();

        $parameters = [
            'skyflow_ids' => $ids,
            'redaction' => $redaction->name,
            'tokenization' => $tokenization,
            'column_name' => $columnName,
            'fields' => $fields,
            'column_values' => $columnValues,
            'offset' => $offset,
            'limit' => $limit
        ];

        return HttpClient::authorizedRequest(
            method: 'GET',
            url: $url . '/' . $table,
            token: $this->storedToken,
            options: [
                'query' => HttpClient::buildQueryString($parameters)
            ]
        );
    }


    /**
     * Returns the specified record from a table.
     *
     * @param string $table Name of the table.
     * @param string $id Skyflow id of the record
     * @param RedactionType|null $redaction Redaction level to enforce for the returned records. Subject to policies assigned to the API caller.
     * @param boolean|null $tokenization If true, this operations returns tokens instead of field values where applicable. Only applicable if skyflow_id values are specified.
     * @param array|null $fields Fields to return for the records. If not specified, all fields are returned.
     * @return array
     */
    public function getRecordById(string $table, string $id, ?RedactionType $redaction = RedactionType::DEFAULT, ?bool $tokenization = null, ?array $fields = null): array
    {
        $tokenProvider = $this->tokenProvider;
        $this->storedToken = $tokenProvider($this->storedToken);
        $url = $this->getCompleteVaultUrl();

        $parameters = [
            'redaction' => $redaction->name,
            'tokenization' => $tokenization,
            'fields' => $fields,
        ];

        return HttpClient::authorizedRequest(
            method: 'GET',
            url: $url . '/' . $table . '/' . $id,
            token: $this->storedToken,
            options: [
                'query' => HttpClient::buildQueryString($parameters)
            ]
        );
    }

    /**
     * Inserts a record in the specified table.
     *
     * @param string $table Name of the table.
     * @param array $records Records to insert.
     * @param boolean|null $tokenization If true, this operation returns tokens instead of field values where applicable.
     * @param string|null $upsert Name of a unique column in the table. Uses upsert operations to check
     * if a record exists based on the unique column's value. If it does, the record updates with the values you provide.
     * If it does not, the upsert operation inserts a new record.
     * @return array
     */
    public function insertRecords(string $table, array $records, ?bool $tokenization = null, ?string $upsert = null): array
    {
        $tokenProvider = $this->tokenProvider;
        $this->storedToken = $tokenProvider($this->storedToken);
        $url = $this->getCompleteVaultUrl();

        $body = [
            'records' => $records,
            'tokenization' => $tokenization,
            'upsert' => $upsert
        ];

        return HttpClient::authorizedRequest(
            method: 'POST',
            url: $url . '/' . $table,
            token: $this->storedToken,
            options: [
                'json' => $body
            ]
        );
    }

    /**
     * Updates the specified record in a table.
     *
     * @param string $table Name of the table.
     * @param string $id Skyflow id of the record
     * @param array $record Fields with new values to update
     * @param boolean|null $tokenization If true, this operation returns tokens instead of field values where applicable.
     * @return array
     */
    public function updateRecord(string $table, string $id, array $record, ?bool $tokenization = null): array
    {
        $tokenProvider = $this->tokenProvider;
        $this->storedToken = $tokenProvider($this->storedToken);
        $url = $this->getCompleteVaultUrl();

        $body = [
            'record' => $record,
            'tokenization' => $tokenization,
        ];

        return HttpClient::authorizedRequest(
            method: 'PUT',
            url: $url . '/' . $table . '/' . $id,
            token: $this->storedToken,
            options: [
                'json' => $body
            ]
        );
    }

    /**
     * Deletes the specified record from a table
     *
     * @param string $table Name of the table.
     * @param string $id Skyflow id of the record
     * @return array
     */
    public function deleteRecord(string $table, string $id): array
    {
        $tokenProvider = $this->tokenProvider;
        $this->storedToken = $tokenProvider($this->storedToken);
        $url = $this->getCompleteVaultUrl();

        return HttpClient::authorizedRequest(
            method: 'DELETE',
            url: $url . '/' . $table . '/' . $id,
            token: $this->storedToken
        );
    }

    /**
     * Deletes the specified records from a table.
     *
     * @param string $table Name of the table.
     * @param string $ids Skyflow id values of the records to delete.
     * If * is specified, this operation deletes all records in the table.
     * @return array
     */
    public function bulkDelete(string $table, array $ids = ['*']): array
    {
        $tokenProvider = $this->tokenProvider;
        $this->storedToken = $tokenProvider($this->storedToken);
        $url = $this->getCompleteVaultUrl();

        return HttpClient::authorizedRequest(
            method: 'DELETE',
            url: $url . '/' . $table,
            token: $this->storedToken,
            options: [
                'json' => [
                    'skyflow_ids' => $ids
                ]
            ]
        );
    }

    /**
     * Returns records that correspond to the specified tokens.
     *
     * @param array $params Detokenization details
     * @return array
     */
    public function detokenize(array $params): array
    {
        $tokenProvider = $this->tokenProvider;
        $this->storedToken = $tokenProvider($this->storedToken);
        $url = $this->getCompleteVaultUrl();

        return HttpClient::authorizedRequest(
            method: 'POST',
            url: $url . '/detokenize',
            token: $this->storedToken,
            options: [
                'json' => [
                    'detokenizationParameters' => $params
                ]
            ]
        );
    }

    /**
     * Returns tokens that correspond to the specified records.
     * Only applicable for fields with deterministic tokenization.
     *
     * @param array $params Tokenization details
     * @return array
     */
    public function tokenize(array $params): array
    {
        $tokenProvider = $this->tokenProvider;
        $this->storedToken = $tokenProvider($this->storedToken);
        $url = $this->getCompleteVaultUrl();

        return HttpClient::authorizedRequest(
            method: 'POST',
            url: $url . '/tokenize',
            token: $this->storedToken,
            options: [
                'json' => [
                    'tokenizationParameters' => $params
                ]
            ]
        );
    }

    /**
     * Returns records for a valid SQL query.
     * While this endpoint retrieves columns under a valid redaction scheme, it can't retrieve tokens.
     * Only supports the SELECT command.
     *
     * @param string $query The SQL query to execute.
     * @return array
     */
    public function query(string $query): array
    {
        $tokenProvider = $this->tokenProvider;
        $this->storedToken = $tokenProvider($this->storedToken);
        $url = $this->getCompleteVaultUrl();

        return HttpClient::authorizedRequest(
            method: 'POST',
            url: $url . '/query',
            token: $this->storedToken,
            options: [
                'json' => [
                    'query' => $query
                ]
            ]
        );
    }

    /**
     * Invoke a connection through Skyflow gateway
     *
     * @param string $connectionUrl The connection URL. Must be the entire url for the route
     * @param RequestMethodType $method The connection route request method
     * @param array|null $headers Connection route request headers
     * @param array|null $body  Connection route request body
     * @param array|null $pathParams Url path variables
     * @param array|null $queryParams Query parameters
     * @return array
     */
    public function invokeConnection(string $connectionUrl, RequestMethodType $method, ?array $headers = null, ?array $body = null, ?array $pathParams = null, ?array $queryParams = null): array
    {
        $tokenProvider = $this->tokenProvider;
        $this->storedToken = $tokenProvider($this->storedToken);
        $options = [
            'headers' => [
                'X-Skyflow-Authorization' => $this->storedToken
            ]
        ];

        if ($headers) {
            $options['headers'] += $headers;
        }


        if ($body) {
            $contentType = 'json';
            if ($headers && array_key_exists('Content-Type', $headers)) {
                $contentType = match ($headers['Content-Type']) {
                    'application/json' => 'json',
                    'application/x-www-form-urlencoded' => 'form_params',
                    'multipart/form-data' => 'multipart',
                    default => 'json'
                };
            }
            $options[$contentType] = $body;
        }

        if ($pathParams) {
            foreach ($pathParams as $key => $value) {
                $connectionUrl .= str_replace("{$key}", $value, $connectionUrl);
            }
        }

        if ($queryParams) {
            $connectionUrl .= '?' + http_build_query($queryParams);
        }

        return HttpClient::request(
            method: $method->name,
            url: $connectionUrl,
            options: $options
        );
    }

    /**
     * Get the complete vault url from given vault url and vault id
     * @return string
     */
    private function getCompleteVaultUrl(): string
    {
        return $this->vaultUrl . '/v1/vaults/' . $this->vaultID;
    }
}
