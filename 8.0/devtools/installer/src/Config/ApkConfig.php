<?php

declare(strict_types=1);

namespace Tools\Config;

final class ApkConfig implements HandlerConfig
{
    /** @var string */
    public $url;
    /** @var string */
    public $fixedVersion;
    /** @var string[] */
    public $match;
    /** @var array<string,string> */
    public $matchMap;

    /**
     * @param mixed[] $data
     *
     * @return ApkConfig
     */
    public static function fromArray(array $data): Config
    {
        $config = new self();

        $config->url = $data['url'] ?? null;
        $config->fixedVersion = $data['fixedVersion'] ?? null;
        $config->match = $data['match'] ?? [];
        $config->matchMap = $data['matchMap'] ?? [];

        return $config;
    }
}
