<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Exception\Transformer;

use Exception;

use function sprintf;

final class MissingData extends Exception
{
    public static function forInvalidFormat(string $data): self
    {
        return new self(sprintf('Response has invalid format [%s]', $data));
    }
}
