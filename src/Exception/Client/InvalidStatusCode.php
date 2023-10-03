<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Exception\Client;

use Exception;

use function sprintf;

final class InvalidStatusCode extends Exception
{
    public static function forStatusCode(int $statusCode): self
    {
        return new self(sprintf('HTTP status code response [%d] is not valid response for request', $statusCode));
    }
}
