<?php

declare(strict_types=1);

namespace OsrsGeApiClient\HttpClient\Util;

use function array_keys;
use function array_map;
use function count;
use function implode;
use function is_array;
use function range;
use function rawurlencode;
use function sprintf;

final class QueryStringBuilder
{
    public static function build(array $query): string
    {
        return sprintf('?%s', implode('&', array_map(function ($value, $key): string {
            return self::encode($value, $key);
        }, $query, array_keys($query))));
    }

    private static function encode(mixed $query, bool|float|int|string $prefix): string
    {
        if (!is_array($query)) {
            return self::rawUrlEncode($prefix).'='.self::rawUrlEncode($query);
        }

        $isList = self::isList($query);

        return implode('&', array_map(function ($value, $key) use ($prefix, $isList): string {
            $prefix = $isList ? $prefix.'[]' : $prefix.'['.$key.']';

            return self::encode($value, $prefix);
        }, $query, array_keys($query)));
    }

    private static function isList(array $query): bool
    {
        if (0 === count($query) || !isset($query[0])) {
            return false;
        }

        return array_keys($query) === range(0, count($query) - 1);
    }

    private static function rawUrlEncode(mixed $value): string
    {
        if (false === $value) {
            return '0';
        }

        return rawurlencode((string) $value);
    }
}
