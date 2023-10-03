<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Transformer\DTO;

use OsrsGeApiClient\DTO\Api\Item;
use OsrsGeApiClient\Exception\Transformer\InvalidJson;
use OsrsGeApiClient\Exception\Transformer\MissingData;
use OsrsGeApiClient\Transformer\AbstractDTOTransformer;

final class ItemTransformer extends AbstractDTOTransformer
{
    /**
     * @throws MissingData
     * @throws InvalidJson
     */
    public function jsonToDTO(string $json): Item
    {
        $data = $this->decodeAndValidateJson($json);

        if (! isset($data['item'])) {
            throw MissingData::forInvalidFormat($json);
        }

        return new Item(
            $data['item']['icon'],
            $data['item']['icon_large'],
            $data['item']['id'],
            $data['item']['type'],
            $data['item']['typeIcon'],
            $data['item']['name'],
            $data['item']['description'],
            $data['item']['members'] === 'true',
            $data['item']['current'],
            $data['item']['today'],
            $data['item']['day30'],
            $data['item']['day90'],
            $data['item']['day180']
        );
    }
}
