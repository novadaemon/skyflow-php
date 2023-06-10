<?php

declare(strict_types=1);

namespace Novadaemon\Skyflow\Types;

enum RequestMethodType
{
    case GET;
    case POST;
    case PUT;
    case PATCH;
    case DELETE;
}
