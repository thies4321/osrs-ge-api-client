<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Entity;

final readonly class Hiscore
{
    /**
     * @param Skill[] $skills
     * @param Activity[] $activities
     */
    public function __construct(
        public array $skills,
        public array $activities
    ){
    }
}
