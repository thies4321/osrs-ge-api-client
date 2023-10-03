<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Transformer;

use OsrsGeApiClient\DTO\DTO;

interface DTOTransformer
{
    public function jsonToDTO(string $json): DTO;
}
