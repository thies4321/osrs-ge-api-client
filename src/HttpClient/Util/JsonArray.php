<?php

declare(strict_types=1);

namespace OsrsGeApiClient\HttpClient\Util;

use OsrsGeApiClient\Exception\RuntimeException;

use function get_debug_type;
use function is_array;
use function json_decode;
use function json_encode;
use function json_last_error;
use function json_last_error_msg;

use const JSON_ERROR_NONE;

final class JsonArray
{
    public static function decode(string $json): array
    {
        $data = json_decode($json, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw RuntimeException::forJsonDecodeError(json_last_error_msg());
        }

        if (!is_array($data)) {
            throw RuntimeException::forJsonDecodeNotArray(get_debug_type($data));
        }

        return $data;
    }

    public static function encode(array $value): string
    {
        $json = json_encode($value);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw RuntimeException::forJsonEncodeError(json_last_error_msg());
        }

        return $json;
    }
}
