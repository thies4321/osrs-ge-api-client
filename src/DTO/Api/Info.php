<?php

declare(strict_types=1);

namespace OsrsGeApiClient\DTO\Api;

use OsrsGeApiClient\DTO\DTO;
use OsrsGeApiClient\Entity\RuneDate;

final readonly class Info implements DTO
{
    public RuneDate $lastConfigUpdateRuneday;

    public function __construct(RuneDate $lastConfigUpdateRuneday)
    {
        $this->lastConfigUpdateRuneday = $lastConfigUpdateRuneday;
    }
}
