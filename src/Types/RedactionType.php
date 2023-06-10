<?php

declare(strict_types=1);

namespace Novadaemon\Skyflow\Types;

enum RedactionType
{
    case DEFAULT;
    case PLAIN_TEXT;
    case MASKED;
    case REDACTED;
}
