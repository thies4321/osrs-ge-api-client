<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Exception\Transformer;

use Exception;

use function sprintf;

final class InvalidJson extends Exception
{
    public static function forDecodeErrorCode(int $errorCode): self
    {
        return new self(sprintf('Attempt to decode json but error code [%d] was returned', $errorCode));
    }
}
