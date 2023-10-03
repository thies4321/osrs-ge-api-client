<?php

declare(strict_types=1);

namespace OsrsGeApiClient\DTO\Api;

use OsrsGeApiClient\DTO\DTO;

final readonly class Item implements DTO
{
    public string $icon;
    public string $icon_large;
    public int $id;
    public string $type;
    public string $typeIcon;
    public string $name;
    public string $description;
    public bool $members;
    public array $current;
    public array $today;
    public ?array $day30;
    public ?array $day90;
    public ?array $day180;

    public function __construct(
        string $icon,
        string $icon_large,
        int $id,
        string $type,
        string $typeIcon,
        string $name,
        string $description,
        bool $members,
        array $current,
        array $today,
        ?array $day30 = null,
        ?array $day90 = null,
        ?array $day180 = null
    ) {
        $this->icon = $icon;
        $this->icon_large = $icon_large;
        $this->id = $id;
        $this->type = $type;
        $this->typeIcon = $typeIcon;
        $this->name = $name;
        $this->description = $description;
        $this->members = $members;
        $this->current = $current;
        $this->today = $today;
        $this->day30 = $day30;
        $this->day90 = $day90;
        $this->day180 = $day180;
    }
}
