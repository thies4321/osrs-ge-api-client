<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Transformer\DTO;

use OsrsGeApiClient\DTO\Api\Graph;
use OsrsGeApiClient\Exception\Transformer\InvalidJson;
use OsrsGeApiClient\Exception\Transformer\MissingData;
use OsrsGeApiClient\Transformer\AbstractDTOTransformer;

final class GraphTransformer extends AbstractDTOTransformer
{
    /**
     * @throws MissingData
     * @throws InvalidJson
     */
    public function jsonToDTO(string $json): Graph
    {
        $data = $this->decodeAndValidateJson($json);

        if (! isset($data['daily']) || ! isset($data['average'])) {
            throw MissingData::forInvalidFormat($json);
        }

        $daily = [];

        foreach ($data['daily'] as $timestamp => $price) {
            $daily[(int) $timestamp] = $price;
        }

        $average = [];

        foreach ($data['average'] as $timestamp => $price) {
            $average[(int) $timestamp] = $price;
        }

        return new Graph($daily, $average);
    }
}
