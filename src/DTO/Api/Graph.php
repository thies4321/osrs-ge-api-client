<?php

declare(strict_types=1);

namespace OsrsGeApiClient\DTO\Api;

use OsrsGeApiClient\DTO\DTO;

final class Graph implements DTO
{
    public array $daily;
    public array $average;

    public function __construct(array $daily, array $average)
    {
        $this->daily = $daily;
        $this->average = $average;
    }
}
