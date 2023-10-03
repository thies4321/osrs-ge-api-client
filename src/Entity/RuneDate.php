<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Entity;

use DateTimeImmutable;
use DateTimeZone;
use Exception;

class RuneDate extends DateTimeImmutable
{
    private const RUNESCAPE_EPOCH_TIMESTAMP = 1014768000;

    public function __construct(string $datetime = "now", ?DateTimeZone $timezone = new DateTimeZone('UTC'))
    {
        parent::__construct($datetime, $timezone);
    }

    /**
     * @throws Exception
     */
    public static function fromRuneDate(int $runeDate): self
    {
        $epoch = new DateTimeImmutable(sprintf('@%d', self::RUNESCAPE_EPOCH_TIMESTAMP), new DateTimeZone('UTC'));

        return new self(sprintf('@%d', $epoch->modify(sprintf('+%d days', $runeDate))->getTimestamp()));
    }

    /**
     * @throws Exception
     */
    public function toRuneDate(): int
    {
        $epoch = new DateTimeImmutable(sprintf('@%d', self::RUNESCAPE_EPOCH_TIMESTAMP), new DateTimeZone('UTC'));

        return $epoch->diff($this)->days;
    }
}
