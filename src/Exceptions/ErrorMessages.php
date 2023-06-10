<?php

declare(strict_types=1);

namespace Novadaemon\Skyflow\Exceptions;

enum ErrorMessages: string
{
    case INVALID_URL = "Given url '%s' is invalid";
    case INVALID_TOKEN = "The Bearer token is invalid";
    case INVALID_GET_RECORDS_PARAMS = "If you provide columnName parameter, you cannot use ids parameter";
    case INVALID_GET_RECORDS_COLUMN_NAME_PARAM = "If you provide columnValue parameter, you should provide columnName parameter";
    case INVALID_GET_RECORDS_TOKENIZATION_PARAMS = "Tokenization only can be active if you provide ids parameter";
    case EMPTY_VAULT_ID = "Vault ID must not be empty";
}
