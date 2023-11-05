<?php

namespace OsrsGeApiClient\Entity;

final readonly class Skill
{
    public function __construct(
        public string $name,
        public ?int $rank,
        public int $level,
        public int $experience,
    ) {
    }
}
