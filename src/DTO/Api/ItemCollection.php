<?php

declare(strict_types=1);

namespace OsrsGeApiClient\DTO\Api;

use ArrayIterator;
use OsrsGeApiClient\DTO\DTO;

final class ItemCollection extends ArrayIterator implements DTO
{
    public readonly int $total;

    /**
     * @param Item[] $items
     */
    public function __construct(int $total, array $items)
    {
        $this->total = $total;

        parent::__construct($items);
    }
}
