<?php

declare(strict_types=1);

namespace OsrsGeApiClient\DTO\Api;

use OsrsGeApiClient\DTO\DTO;

final readonly class Alpha implements DTO
{
    public string $letter;
    public int $items;

    public function __construct(string $letter, int $items)
    {
        $this->letter = $letter;
        $this->items = $items;
    }
}
