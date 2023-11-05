<?php

declare(strict_types=1);

namespace OsrsGeApiClient\HttpClient\Util\Converter;

use OsrsGeApiClient\Entity\Item;
use function array_map;
use function json_encode;

final class JsonItems
{
    /**
     * @return Item[]
     */
    public static function decode(string $json): array
    {
        return array_map(function (array $item) {
            return JsonItem::decode(json_encode($item));
        }, JsonArray::decode($json)['items']);
    }
}
