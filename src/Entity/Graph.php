<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Entity;

final readonly class Graph
{
    public function __construct(
        public array $daily,
        public array $average,
    ){
    }
}
