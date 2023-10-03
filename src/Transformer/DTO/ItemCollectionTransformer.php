<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Transformer\DTO;

use OsrsGeApiClient\DTO\Api\Item;
use OsrsGeApiClient\DTO\Api\ItemCollection;
use OsrsGeApiClient\Exception\Transformer\InvalidJson;
use OsrsGeApiClient\Exception\Transformer\MissingData;
use OsrsGeApiClient\Transformer\AbstractDTOTransformer;

final class ItemCollectionTransformer extends AbstractDTOTransformer
{
    /**
     * @throws MissingData
     * @throws InvalidJson
     */
    public function jsonToDTO(string $json): ItemCollection
    {
        $data = $this->decodeAndValidateJson($json);

        if (! isset($data['items']) || ! isset($data['total'])) {
            throw MissingData::forInvalidFormat($json);
        }

        $items = [];
        foreach ($data['items'] as $item) {
            $items[] = new Item(
                $item['icon'],
                $item['icon_large'],
                $item['id'],
                $item['type'],
                $item['typeIcon'],
                $item['name'],
                $item['description'],
                $item['members'] === 'true',
                $item['current'],
                $item['today'],
                $item['day30'] ?? null,
                $item['day90'] ?? null,
                $item['day180'] ?? null
            );
        }

        return new ItemCollection($data['total'], $items);
    }
}
