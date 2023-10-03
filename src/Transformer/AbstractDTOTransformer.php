<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Transformer;

use OsrsGeApiClient\Exception\Transformer\InvalidJson;

use function json_decode;
use function json_last_error;

abstract class AbstractDTOTransformer implements DTOTransformer
{
    /**
     * @throws InvalidJson
     */
    protected function decodeAndValidateJson(string $json): array
    {
        $data = json_decode($json, true);

        if (json_last_error() !== 0) {
            throw InvalidJson::forDecodeErrorCode(json_last_error());
        }

        return $data;
    }
}
