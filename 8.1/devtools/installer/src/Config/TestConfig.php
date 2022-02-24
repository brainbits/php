<?php

declare(strict_types=1);

namespace Tools\Config;

final class TestConfig implements Config
{
    /** @var string[] */
    public $command;
    /** @var string */
    public $exists;

    /**
     * @param mixed[] $data
     *
     * @return TestConfig
     */
    public static function fromArray(array $data): Config
    {
        $config = new self();

        $config->command = $data['command'] ?? null;
        $config->exists = $data['exists'] ?? null;

        return $config;
    }
}
