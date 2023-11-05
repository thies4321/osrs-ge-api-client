<?php

declare(strict_types=1);

namespace OsrsGeApiClient\HttpClient\Util\Converter;

use Exception;
use OsrsGeApiClient\Entity\RuneDate;

final class JsonRuneDate
{
    /**
     * @throws Exception
     */
    public static function decode(string $json): RuneDate
    {
        $data = JsonArray::decode($json);
        return RuneDate::fromRuneDate($data['lastConfigUpdateRuneday']);
    }
}
