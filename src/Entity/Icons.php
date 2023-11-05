<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Entity;

final readonly class Icons
{
    public function __construct(
        public string $icon,
        public string $iconLarge,
        public string $typeIcon
    ) {
    }
}
