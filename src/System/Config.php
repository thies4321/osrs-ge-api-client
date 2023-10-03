<?php

declare(strict_types=1);

namespace OsrsGeApiClient\System;

use OsrsGeApiClient\Exception\ConfigException;
use Symfony\Component\Yaml\Yaml;

use function explode;

final readonly class Config
{
    private const DEFAULT_CLIENT_CONFIG_PATH = __DIR__ . '/../../config/client.yaml';

    private array $config;

    public function __construct(?array $config = null)
    {
        $this->config = $config ?? Yaml::parseFile(self::DEFAULT_CLIENT_CONFIG_PATH);
    }

    /**
     * @throws ConfigException
     */
    public function get(string $configKey = null, bool $required = false): int|float|string|bool|array|null
    {
        $subset = $this->config;

        if ($configKey === null) {
            return $subset;
        }

        $keys = explode('.', $configKey);

        foreach ($keys as $key) {
            if ($required === true && !isset($subset[$key])) {
                throw ConfigException::forMissingKey($configKey);
            }

            $subset = $subset[$key] ?? [];
        }

        return $subset;
    }
}
