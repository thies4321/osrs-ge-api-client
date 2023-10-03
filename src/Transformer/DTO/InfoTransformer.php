<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Transformer\DTO;

use Exception;
use OsrsGeApiClient\DTO\Api\Info;
use OsrsGeApiClient\Entity\RuneDate;
use OsrsGeApiClient\Exception\Transformer\InvalidJson;
use OsrsGeApiClient\Exception\Transformer\MissingData;
use OsrsGeApiClient\Transformer\AbstractDTOTransformer;

final class InfoTransformer extends AbstractDTOTransformer
{
    /**
     * @throws InvalidJson
     * @throws MissingData
     * @throws Exception
     */
    public function jsonToDTO(string $json): Info
    {
        $data = $this->decodeAndValidateJson($json);

        if (! isset($data['lastConfigUpdateRuneday'])) {
            throw MissingData::forInvalidFormat($json);
        }

        $runeDate = RuneDate::fromRuneDate($data['lastConfigUpdateRuneday']);

        return new Info($runeDate);
    }
}
