<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Transformer\DTO;

use OsrsGeApiClient\DTO\Api\Alpha;
use OsrsGeApiClient\DTO\Api\AlphaCollection;
use OsrsGeApiClient\Exception\Transformer\InvalidJson;
use OsrsGeApiClient\Exception\Transformer\MissingData;
use OsrsGeApiClient\Transformer\AbstractDTOTransformer;

final class AlphaCollectionTransformer extends AbstractDTOTransformer
{
    /**
     * @throws InvalidJson
     * @throws MissingData
     */
    public function jsonToDTO(string $json): AlphaCollection
    {
        $data = $this->decodeAndValidateJson($json);

        if (! isset($data['alpha'])) {
            throw MissingData::forInvalidFormat($json);
        }

        $alphas = [];
        foreach ($data['alpha'] as $alpha) {
            $alphas[] = new Alpha($alpha['letter'], $alpha['items']);
        }

        return new AlphaCollection($alphas);
    }
}
