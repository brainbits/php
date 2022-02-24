<?php

declare(strict_types=1);

namespace Tools\Config;

final class MetaConfig implements Config
{
    /** @var string */
    public $summary;
    /** @var string */
    public $website;

    /**
     * @param mixed[] $data
     *
     * @return MetaConfig
     */
    public static function fromArray(array $data): Config
    {
        $config = new self();

        $config->summary = $data['summary'] ?? null;
        $config->website = $data['website'] ?? null;

        return $config;
    }
}
