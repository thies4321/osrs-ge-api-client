<?php

declare(strict_types=1);

namespace OsrsGeApiClient\HttpClient\Util\Converter;

use OsrsGeApiClient\Entity\Alpha;
use function array_map;

final class JsonAlphas
{
    /**
     * @return Alpha[]
     */
    public static function decode(string $json): array
    {
        return array_map(function (array $alpha) {
            return new Alpha($alpha['letter'], (int) $alpha['items']);
        }, JsonArray::decode($json)['alpha']);
    }
}
