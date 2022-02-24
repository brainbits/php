<?php

declare(strict_types=1);

namespace Tools\Config;

final class VersionConfig implements Config
{
    /** @var string[] */
    public $command;

    /**
     * @param mixed[] $data
     *
     * @return VersionConfig
     */
    public static function fromArray(array $data): Config
    {
        $config = new self();

        $config->command = $data['command'] ?? null;

        return $config;
    }
}
