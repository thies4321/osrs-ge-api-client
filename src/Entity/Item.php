<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Entity;

final readonly class Item
{
    public function __construct(
        public int $id,
        public string $type,
        public string $name,
        public string $description,
        public bool $members,
        public Icons $icons,
        public TradeHistory $tradeHistory,
    ) {
    }
}
