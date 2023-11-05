<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Entity;

final readonly class Activity
{
    public function __construct(
        public string $name,
        public ?int $rank,
        public ?int $score,
    ) {
    }
}
