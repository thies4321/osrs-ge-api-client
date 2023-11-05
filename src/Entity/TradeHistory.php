<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Entity;

final readonly class TradeHistory
{
    public function __construct(
        public array $current,
        public array $today,
        public ?array $day30 = null,
        public ?array $day90 = null,
        public ?array $day180 = null,
    ) {
    }
}
