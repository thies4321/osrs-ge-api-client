<?php

declare(strict_types=1);

namespace OsrsGeApiClient\DTO\Api;

use ArrayIterator;
use OsrsGeApiClient\DTO\DTO;

final class AlphaCollection extends ArrayIterator implements DTO
{
    /**
     * @param Alpha[] $alphas
     */
    public function __construct(array $alphas)
    {
        parent::__construct($alphas);
    }
}
