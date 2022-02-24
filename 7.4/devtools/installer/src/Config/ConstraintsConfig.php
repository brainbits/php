<?php

declare(strict_types=1);

namespace Tools\Config;

final class ConstraintsConfig implements Config
{
    /** @var bool */
    public $only;
    /** @var bool */
    public $skip;
    /** @var string */
    public $skipIf;

    /**
     * @param mixed[] $data
     *
     * @return ConstraintsConfig
     */
    public static function fromArray(array $data): Config
    {
        $config = new self();

        $config->only = $data['only'] ?? null;
        $config->skip = $data['skip'] ?? null;
        $config->skipIf = $data['skipIf'] ?? null;

        return $config;
    }
}
