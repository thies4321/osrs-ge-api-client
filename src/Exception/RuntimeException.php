<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Exception;

use RuntimeException as RuntimeExceptionBase;

use function sprintf;

class RuntimeException extends RuntimeExceptionBase implements ExceptionInterface
{
    public static function forJsonEncodeError(string $message): self
    {
        return new self(sprintf('json_encode error: %s', $message));
    }

    public static function forJsonDecodeError(string $message): self
    {
        return new self(sprintf('json_decode error: %s', $message));
    }

    public static function forJsonDecodeNotArray(string $type): self
    {
        return new self(sprintf('json_decode error: Expected JSON of type array, %s given.', $type));
    }

    public static function forPaginationNotSupported(): self
    {
        return new self('Pagination of this endpoint is not supported.');
    }
}
