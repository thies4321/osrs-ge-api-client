<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Exception;

use Exception;

use function sprintf;

final class ConfigException extends Exception
{
    public static function forMissingKey(string $key): self
    {
        return new self(sprintf('Key [%s] not found in config', $key));
    }
}
