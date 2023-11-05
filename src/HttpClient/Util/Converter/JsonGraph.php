<?php

declare(strict_types=1);

namespace OsrsGeApiClient\HttpClient\Util\Converter;

use OsrsGeApiClient\Entity\Graph;

final class JsonGraph
{
    public static function decode(string $json): Graph
    {
        $graph = JsonArray::decode($json);
        return new Graph($graph['daily'], $graph['average']);
    }
}
