# Skyflow-PHP

---
## Description
This PHP SDK is designed to help developers easily implement Skyflow into their php backend.

## Table of Contents
- [Features](#features)
- [Installation](#installation)
    - [Requirements](#requirements)
    - [Install](#install)
- [Examples](#examples) 
    - [Prerequisites](#prerequisites)   
    - [Create the vault](#create-the-vault)   
    - [Create a Service Account](#create-a-service-account)   
- [Service Account Bearer Token Generation](#service-account-bearer-token-generation)
- [Vault API](#vault-api)
    - [Get records](#get-records)
    - [Get record by ID](#get-record-by-id)
    - [Insert records](#insert-records-into-the-vault)
    - [Update record](#update-record)
    - [Delete record](#delete-record)
    - [Bulk delete records](#bulk-delete)
    - [Detokenization](#detokenization)
    - [Tokenization](#detokenization)
    - [Execute SQL Query](#execute-sql-query)
    - [Invoke Connection](#invoke-connection)

## Features

Authentication with a Skyflow Service Account and generation of a bearer token.

Vault API operations to insert, retrieve, update, delete and tokenize sensitive data.

Execute SQL queries into vault scheme.

Invoking connections to call downstream third party APIs without directly handling sensitive data.

## Installation

### Requirements

 - Require PHP 8.1 or above
 - You need to have installed [Composer](https://getcomposer.org/) 

 ### Install  

 Type in your terminal:

`composer require novadaemon/skyflow-php`

## Examples

You can find samples for all the features of the SDK in the `samples` directory. To run a given example:

1. Download the repository using `git clone https://github.com/novadaemon/skyflow-php.git`
2. Run `$ composer install` into directory project.
3. Go to the `samples` directory in your terminal.
3. Change the values enclosed by `<>` for the right values into the example file.
4. Execute the example you want: `$ php get_records.php`

### Prerequisites

- A Skyflow account. If you don't have one, register for one on the
  [Try Skyflow](https://skyflow.com/try-skyflow) page.
- PHP 8.1 or above.
- GIT

### Create the vault

1. In a browser, sign in to Skyflow Studio.
2. Create a vault by clicking **Create Vault** > **Start With a Template** >
   **Quickstart vault**.
3. Once the vault is ready, click the gear icon and select **Edit Vault Details**.
4. Note your **Vault URL** and **Vault ID** values, then click **Cancel**.
   You will need these later.

### Create a Service Account

1. In the side navigation click, **IAM** > **Service Accounts** > **New Service Account**.
2. For **Name**, enter "SDK Sample". For **Roles**, choose **Vault Editor**.
3. Click **Create**. Your browser downloads a **credentials.json** file. Keep
   this file secure, as You will need it for each of the samples.

## Service Account Bearer Token Generation

The [Novadaemon\SkyFlow\ServiceAccount\Token](src/ServiceAccount/Token.php) class is used to generate service account tokens from service account credentials file which is downloaded upon creation of service account. The token generated from this class is valid for 60 minutes and can be used to make API calls to vault services as well as management API(s) based on the permissions of the service account.

The `Token::generateBearerToken($credentialsPath)` static method takes the credentials file path for token generation, alternatively, you can also send the entire credentials as array, by using `Token::generateBearerTokenFromCredentials($credentials)`

[Example using filepath](samples/generate_bearer_token.php):

```php
<?php

declare(strict_types=1);

use Novadaemon\Skyflow\Exceptions\SkyflowException;
use Novadaemon\Skyflow\ServiceAccount\Token;

require __DIR__ . '/../vendor/autoload.php';

try {
    $path = '<YOUR_CREDENTIALS_FILE_PATH>';

    $token = Token::generateBearerToken($path);

    var_dump($token);
} catch (SkyflowException $e) {
    echo($e->getMessage());
}
```

[Example using credentials](samples/generate_bearer_token_from_credentials.php):

```php
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
```

Response:

```php
<?php 

array (
  'accessToken' => 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJhdWQiOiJodHRwczovL21hbmFnZS5za3lmbG93YXBpcy5jb20iLCJjbGkiOiJlNzVkZmNmMzMwN2Q0M2I2YjZlN2JhNTk2ZTU5ODgxNyIsImV4cCI6MTY3ODEyMzI1NCwiaWF0IjoxNjc4MTE5NjU3LCJpc3MiOiJzYS1hdXRoQG1hbmFnZS5za3lmbG93YXBpcy5jb20iLCJqdGkiOiJmZTZkYzRjNGY1YmM0OTllODhjOTc0ZmNjZGRjZGMyZCIsImtleSI6ImJlNjk0YTJlYTFmNjQ3ZDM5N2E1YzIwNWZlYjRmMzUxIiwic2NwIjpudWxsLCJzdWIiOiJUZXN0U2VydmljZSJ9.WXSqZh7fQwx9E1ngKKSD9jfPLNWLzE46CKWuZ0bovbZFTGLKcHy94z-SKb1qxtW-CedcbgbnK7Wjd-H20PusjbLGCcjPcAQt7pghUvdpLabuR8DIW0sbfTwen5JnhVupHp0_3tXwp8jJDGF6s8JExVA9DhMLoyusqC9wD_Gmemdj4dU_r4drKhiVYgnsbD8NgZyc6yfgWXrE51IoqLd9FsZ0rDDrys3ttwEbYLRXnr1NGGsftqI0-y4K2gu090BJ2wtDlQO61ZD8_KZSZSEpxr6ZYIMuYsG7SxvnkR6OMz1fVWbtptf1EzEk-ky2M2r5oGGYH_WFBakVk8wKHQy3og',
  'tokenType' => 'Bearer',
)
```

## Vault API

The [Novadaemon\Skyflow\Vault\Client](src/Vault/Client.php) has all methods to perform operations on the vault such as get records, inserting records, detokenizing tokens, retrieving tokens for a skyflow_id, excute sql query and to invoke a connection.

To use this class, the skyflow client must first be initialized as follows.

```php
<?php

use Novadaemon\Skyflow\Vault\Client;
use Novadaemon\Skyflow\ServiceAccount\Token;

require __DIR__.'/../vendor/autoload.php';

# User defined function to provide access token to the vault apis
$tokenProvider = function (string $token): string {

    if (Token::isExpired($token)) {
        $token = Token::generateBearerToken('<YOUR_CREDENTIALS_FILE_PATH>');
        return $token['accessToken'];
    }

    return $token;
};

#Initializing a Skyflow Client instance with the required constructor parameters
$client = new Client(
    vaultID: '<YOUR_VAULT_ID>', 
    vaultUrl: '<YOUR_VAULT_URL>', 
    tokenProvider: $tokenProvider
);
```

All Vault API endpoints must be invoked using a client instance.

## Get Records

Use the method `Client@getRecords()` to perform bulk operation of retrieve records of table. This method has the following parameters:

| Parameter    | Description                                                                                                                           | Type          | Required? | Default                |
|--------------|---------------------------------------------------------------------------------------------------------------------------------------|---------------|-----------|------------------------|
| table        | Name of the table that contains the records                                                                                           | string        | yes       | none                   |
| ids          | Values of the records to return. If not specified, this operation returns all records in the table.                                   | array         | no        | null                   |
| redaction    | Redaction level to enforce for the returned records. Subject to policies assigned to the API caller.                                  | RedactionType | no        | RedactionType::DEFAULT |
| tokenization | If true, this operations returns tokens instead of field values where applicable. Only applicable if skyflow_id values are specified. | bool          | no        | null                   |
| fields       | Fields to return for the records. If not specified, all fields are returned.                                                          | array         | no        | null                   |
| columnName   | Name of the column. It must be configured as unique in the schema.                                                                    | string        | no        | null                   |
| columnValues | Column values of the records to return. column_name is mandatory when providing column_values                                         | array         | no        | null                   |
| offset       | Record position at which to start receiving data.                                                                                     | int           | no        | 0                      |
| limit        | Number of record to return. Maximum 25.                                                                                               | int           | no        | 25                     |


`Note:` There are parameters that cannot be used together with others. If you pass the getRecords method arguments incorrectly, a SkyflowException is thrown.

`Note:` If the tokenization argument is true, you can set only tokenized field names in the fields parameter. An error is returned if you set the tokenization parameter to true and set a non-tokenized field name in the fields parameter.

An [example](samples/get_records.php) of `Client@getRecords()` call:

```php
<?php

$response = $client->getRecords(
    table: 'cards',
    ids: [
        "1a8ec9d5-9be6-465d-a8ad-2797d7258a10",
        "76c892f4-7523-4e74-ac82-afc7bdea7d74"
    ],
    redaction: RedactionType::PLAIN_TEXT,
    tokenization: false
);
```

Response:

```php
<?php 

array (
  'records' => 
  array (
    0 => 
    array (
      'fields' => 
      array (
        'card_cvv' => '123',
        'card_expiration' => '03/26',
        'card_number' => '4242424242424242',
        'skyflow_id' => '1a8ec9d5-9be6-465d-a8ad-2797d7258a10',
      ),
    ),
    1 => 
    array (
      'fields' => 
      array (
        'card_cvv' => '234',
        'card_expiration' => '12/24',
        'card_number' => '4111111111111111',
        'skyflow_id' => '76c892f4-7523-4e74-ac82-afc7bdea7d74',
      ),
    ),
  ),
)
```

Error:

```php
<?php 

array (
  'error' => 
  array (
    'grpc_code' => 5,
    'http_code' => 404,
    'message' => 'No Records Found',
    'http_status' => 'Not Found',
  ),
)
```

### Get record by id

To retrieve only once record from your Skyflow vault, use the method `Client@getRecordById()`. This method has the following parameters:

| Parameter    | Description                                                                                                                           | Type          | Required? | Default                |
|--------------|---------------------------------------------------------------------------------------------------------------------------------------|---------------|-----------|------------------------|
| table        | Name of the table that contains the records                                                                                           | string        | yes       | none                   |
| id           | Skyflow id of the record                                                                                                              | string        | yes       | none                   |
| redaction    | Redaction level to enforce for the returned records. Subject to policies assigned to the API caller.                                  | RedactionType | no        | RedactionType::DEFAULT |
| tokenization | If true, this operations returns tokens instead of field values where applicable. Only applicable if skyflow_id values are specified. | bool          | no        | null                   |
| fields       | Fields to return for the records. If not specified, all fields are returned.                                                          | array         | no        | null                   |

An [example](samples/get_record_by_id.php) of `Client@getRecordById()` call:

```php
<?php 

$response = $client->getRecordById(
    table: 'consumers',
    id: '76193681-d272-4ffe-80f9-6498398e4c7b',
    fields: [
        'first_name',
        'last_name',
        'email',
        'phone',
    ]
);
```

Response:

```php
<?php 

array (
  'fields' => 
  array (
    'email' => 'j******s@gmail.com',
    'first_name' => '*REDACTED*',
    'last_name' => '*REDACTED*',
    'phone' => 'XXXXXX0000',
  ),
)
```

Error:

```php
<?php 

array (
  'error' => 
  array (
    'grpc_code' => 5,
    'http_code' => 404,
    'message' => 'No Records Found',
    'http_status' => 'Not Found',
  ),
)
```

### Insert records into the vault

To insert data into your vault use the `Cient@insertRecord()` method. The parameters to this method are:

| Parameter    | Description                                                                                                                                                                                                                                         | Type   | Required? | Default |
|--------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|--------|-----------|---------|
| table        | Name of the table that contains the records                                                                                                                                                                                                         | string | yes       | none    |
| records      | Records to insert                                                                                                                                                                                                                                   | array  | yes       | none    |
| tokenization | If true, this operations returns tokens instead of field values where applicable. Only applicable if skyflow_id values are specified.                                                                                                               | bool   | no        | null    |
| upsert       | Name of a unique column in the table. Uses upsert operations to check if a record exists based on the unique column's value. If it does, the record updates with the values you provide. If it does not, the upsert operation inserts a new record. | string | no        | null    |

An [example](samples/insert_records.php) of `Client@insertRecords()` call:

```php
<?php 

$records = [
    [
        "fields" => [
            "card_number" => "5555555555554444",
            "card_expiration" => "12/25",
            "card_cvv" => "111",
        ]
    ],
    [
        "fields" => [
            "card_number" => "5105105105105100",
            "card_expiration" => "04/28",
            "card_cvv" => "123",
        ]
    ],
];

$response = $client->insertRecords(
    table: 'cards',
    records: $records,
    tokenization: true,
    upsert: 'card_number'
);
```

Response:

```php
<?php 

array (
  'records' => 
  array (
    0 => 
    array (
      'skyflow_id' => '76c892f4-7523-4e74-ac82-afc7bdea7d74',
      'tokens' => 
      array (
        'card_cvv' => '19a364b9-0b39-4cad-a544-45c7ba1c52eb',
        'card_expiration' => '1b4ab2f7-fdd8-48f2-8b50-eb97075eda2e',
        'card_number' => '5554-3665-8198-3602',
      ),
    ),
    1 => 
    array (
      'skyflow_id' => '1a8ec9d5-9be6-465d-a8ad-2797d7258a10',
      'tokens' => 
      array (
        'card_cvv' => 'b5bade68-3a0d-4895-8c27-edd911c11e99',
        'card_expiration' => '767eda0a-ed3a-4781-82d5-755207acf1e0',
        'card_number' => '8080-3444-8671-2308',
      ),
    ),
  ),
)
```

Error:

```php
<?php 

array (
  'error' => 
  array (
    'grpc_code' => 9,
    'http_code' => 400,
    'message' => 'Error Inserting Records due to unique constraint violation',
    'http_status' => 'Bad Request',
    'details' => 
    array (
    ),
  ),
)
```

### Update record

To update data in your vault, use the `Client@updateRecord()` method. The parameters to this method are:

| Parameter    | Description                                                                                                                           | Type   | Required? | Default |
|--------------|---------------------------------------------------------------------------------------------------------------------------------------|--------|-----------|---------|
| table        | Name of the table that contains the records                                                                                           | string | yes       | none    |
| id           | Skyflow id of the record                                                                                                              | string | yes       | none    |
| record       | Fields with new values to update                                                                                                      | array  | no        | null    |
| tokenization | If true, this operations returns tokens instead of field values where applicable. Only applicable if skyflow_id values are specified. | bool   | no        | null    |

An [example](samples/update_record.php) of `Client@updateRecord()` call:

```php
<?php 

$record =  [
    'fields' => [
        "email" => 'jhon@fake.com',
        "phone" => '+5478698765',
    ]
];

$response = $client->updateRecord(
    table: 'consumers',
    id: '76193681-d272-4ffe-80f9-6498398e4c7b',
    record: $record,
    tokenization: false
);
```

Response:

```php
<?php 

array (
  'skyflow_id' => '76193681-d272-4ffe-80f9-6498398e4c7b',
  'createdTime' => '',
  'updatedTime' => '',
  'tokens' => 
  array (
    'email' => 'nqmlqdxatzkwhqujzltu@opkfulnxkx.com',
    'phone' => '54df9553-6688-4359-a8ff-df5cbd9cfa55',
  ),
)
```
Error:

```php
<?php 

array (
  'error' => 
  array (
    'grpc_code' => 3,
    'http_code' => 400,
    'message' => 'Invalid field present in JSON invalid-field',
    'http_status' => 'Bad Request',
    'details' => 
    array (
    ),
  ),
)
```

### Delete record

The `Client@deleteRecord()` allow you to delete a record from your Skyflow vault. This method has the following parameters:

| Parameter    | Description                                 | Type   | Required? | Default |
|--------------|---------------------------------------------|--------|-----------|---------|
| table        | Name of the table that contains the records | string | yes       | none    |
| id           | Skyflow id of the record                    | string | yes       | none    |

An [example](samples/delete_record.php) of `Client@deleteRecord()` call:

```php
<?php 

$response = $client->deleteRecord('cards', 'e95834a2-cf1e-40ac-9f0d-0ea2c7920a8d');
```
Response:

```php
<?php 

array (
  'skyflow_id' => 'e95834a2-cf1e-40ac-9f0d-0ea2c7920a8d',
  'deleted' => true,
)
```

Error:

```php
<?php 

array (
  'error' => 
  array (
    'grpc_code' => 5,
    'http_code' => 404,
    'message' => 'No Records Found',
    'http_status' => 'Not Found',
  ),
)
```

### Bulk delete

The `Client@bulkDelete()` allow you to delete specified record from your Skyflow vault. This method has the following parameters:

| Parameter    | Description                                                                                                    | Type          | Required? | Default |
|--------------|----------------------------------------------------------------------------------------------------------------|---------------|-----------|---------|
| table        | Name of the table that contains the records                                                                    | string        | yes       | none    |
| ids          | kyflow id values of the records to delete. If * is specified, this operation deletes all records in the table. | array         | yes       | ['*']   |

An [example](samples/bulk_delete.php) of `Client@bulkDelete()` call:

```php
<?php 

#Delete all records from cards table
$response = $client->bulkDelete('cards');
```
Response:

```php
<?php 

array (
  'RecordIDResponse' => 
  array (
    0 => '*',
  ),
)
```

Error:

```php
<?php 

array (
  'error' => 
  array (
    'grpc_code' => 13,
    'http_code' => 500,
    'message' => 'Couldn\'t load data',
    'http_status' => 'Internal Server Error',
    'details' => 
    array (
    ),
  ),
)
```

## Detokenization

In order to retrieve data from your vault using tokens that you have previously generated for that data, you can use the `Client@detokenize()` method. The parameters to this method are:

| Parameter    | Description                                 | Type  | Required? | Default |
|--------------|---------------------------------------------|-------|-----------|---------|
| params       | Detokenization details                      | array | yes       | none    |

An [example](samples/detokenization.php) of `Client@detokenize()` call:

```php
<?php 

$params =  [
    ['token' => 'aa3cc614-af50-4365-a7ac-82f83a79eef8'],
    ['token' => 'e77674ac-4a21-4318-8bdd-5ad20f1b47c7'],
    ['token' => 'nqmlqdxatzkwhqujzltu@opkfulnxkx.com']
];

$response = $client->detokenize($params);
```

Response:

```php
<?php 

array (
  'records' => 
  array (
    0 => 
    array (
      'token' => 'aa3cc614-af50-4365-a7ac-82f83a79eef8',
      'valueType' => 'STRING',
      'value' => '234',
    ),
    1 => 
    array (
      'token' => 'e77674ac-4a21-4318-8bdd-5ad20f1b47c7',
      'valueType' => 'STRING',
      'value' => '12/24',
    ),
    2 => 
    array (
      'token' => 'nqmlqdxatzkwhqujzltu@opkfulnxkx.com',
      'valueType' => 'STRING',
      'value' => 'jhon@fake.com',
    ),
  ),
)
```

Error:

```php
<?php 

array (
  'error' => 
  array (
    'grpc_code' => 5,
    'http_code' => 404,
    'message' => 'Token not found for invalid-token',
    'http_status' => 'Not Found',
    'details' => 
    array (
    ),
  ),
)
```

`Note:` The structure for each item of the params parameter is:

```php
<?php 

[
  'token' => 'STRING' # The Skyflow token to be detokenized
  'redaction' => 'STRING' # Redaction policy. Not required. Default DEFAULT. Only accept values presente in RedactionType enum: DEFAULT, REDACTED, MASKED, PLAIN_TEXT  
]
```

## Tokenization

The method `Client@tokenize()` method returns tokens that correspond to the specified records. Only applicable for fields with deterministic tokenization. The parameters to this method are:

| Parameter    | Description                                 | Type  | Required? | Default |
|--------------|---------------------------------------------|-------|-----------|---------|
| params       | Tokenization details                        | array | yes       | none    |

`Note:` This endpoint doesn't insert records, it returns tokens for existing values. To insert records and tokenize that new record's values, see [Insert Records](#insert-record-into-the-vault) and the tokenization parameter.

An [example](samples/tokenization.php) of `Client@tokenize()` call:

```php
<?php 

$params =  [
    [
        'value' => '5105105105105100',
        'table' => 'cards',
        'column' => 'card_number'
    ],
];

$response = $client->tokenize($params);
```

Response:

```php
<?php 

array (
  'records' => 
  array (
    0 => 
    array (
      'token' => '0782-1334-4999-6413',
    ),
  ),
)
```

Error:

```php
<?php 

array (
  'error' => 
  array (
    'grpc_code' => 5,
    'http_code' => 404,
    'message' => 'Tokenize returns previously issued tokens for existing data. Provided value(s) do not exist in the provided table.column(s): card_number',
    'http_status' => 'Not Found',
    'details' => 
    array (
    ),
  ),
)
```

## Execute SQL Query

The method `Client@query()` returns record for a valid SQL query. While this endpoint retrieves columns under a valid redaction scheme, it can't retrieve tokens. Only supports the SELECT command. The parameters to this method are:

| Parameter    | Description               | Type   | Required? | Default |
|--------------|---------------------------|--------|-----------|---------|
| query        | The SQL query to execute. | string | yes       | none    |

`Note:` See the [Skyflow API Documentation](https://docs.skyflow.com/record/#QueryService_ExecuteQuery) to know moer about the query restrictions.

An [example](samples/query.php) of `Client@query()` call:

```php
<?php 

$query =  "SELECT * FROM cards WHERE card_number = '5105105105105100'";

$response = $client->query($query);
```

```php
<?php 

array (
  'records' => 
  array (
    0 => 
    array (
      'fields' => 
      array (
        'card_cvv' => '*REDACTED*',
        'card_expiration' => '*REDACTED*',
        'card_number' => '5105105105105100',
        'orders_skyflow_id' => NULL,
        'skyflow_id' => '1084350b-d4a2-45b1-801f-2fd46bef0721',
      ),
    ),
  ),
)
```

Error:

```php
<?php 

array (
  'error' => 
  array (
    'grpc_code' => 13,
    'http_code' => 500,
    'message' => 'ERROR (internal_error): Could not find Field car_number',
    'http_status' => 'Internal Server Error',
    'details' => 
    array (
    ),
  ),
)
```

## Invoke Connection

Using Skyflow Connection, end-user applications can integrate checkout/card issuance flow with their apps/systems. To invoke connection, use the `Client@invokeConnection()` method of the Skyflow client. This method accepts the following parameters:

| Paramater     | Description                                              | Type              | Required? | Default |
|---------------|----------------------------------------------------------|-------------------|-----------|---------|
| connectionUrl | The connection URL. Must be the entire url for the route | string            | yes       | none    |
| method        | The connection route request method                      | RequestMethodType | yes       | none    |
| headers       | Connection route request headers                         | array             | no        | null    |
| body          | Connection route request body                            | array             | no        | null    |
| pathParams    | Url path variables                                       | array             | no        | null    |
| queryParams   | Query parameters                                         | array             | no        | null    |

`Note:` See the [Skyflow API Documentation](https://docs.skyflow.com/connections-overview/) to know more about Connections.

An [example](samples/invoke_connection.php) of `Client@invokeConnection()` call:

```php
<?php 

$headers = [
    'Content-Type' => 'application/json',
    'Authorization' => '<YOUR_CONNECTION_BASIC_AUTH>'
];

$body = [
    'firstName' => 'Jesús',
    'lastName' => 'García',
    'email' => 'nqmlqdxatzkwhqujzltu@opkfulnxkx.com',
    'phone' => '54df9553-6688-4359-a8ff-df5cbd9cfa55',
];

$url = 'https://ebfc9bee4242.gateway.skyflowapis.com/v1/gateway/outboundRoutes/x2f47b94462a46989ac7b37817ef99bf/users/add';

$response = $client->invokeConnection(
    connectionUrl: $url,
    method: RequestMethodType::POST,
    headers: $headers,
    body: $body
);
```

```php
<?php 

array (
    'email' => 'jhon@fake.com',
    'firstName' => 'Jesús',
    'lastName' => 'García',
    'phone' => '+5478698765'
)
```