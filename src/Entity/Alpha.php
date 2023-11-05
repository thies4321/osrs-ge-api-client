<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Entity;

final readonly class Alpha
{
    public function __construct(
        public string $letter,
        public int $items,
    ) {
    }
}
