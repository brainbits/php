<?php

declare(strict_types=1);

namespace Tools\Config;

final class CurlConfig implements HandlerConfig
{
    /** @var string */
    public $url;
    /** @var string */
    public $target;
    /** @var string */
    public $fixedVersion;
    /** @var string[] */
    public $match;
    /** @var array<string,string> */
    public $matchMap;

    /**
     * @param mixed[] $data
     *
     * @return CurlConfig
     */
    public static function fromArray(array $data): Config
    {
        $config = new self();

        $config->url = $data['url'] ?? null;
        $config->target = $data['target'] ?? null;
        $config->fixedVersion = $data['fixedVersion'] ?? null;
        $config->match = $data['match'] ?? [];
        $config->matchMap = $data['matchMap'] ?? [];

        return $config;
    }
}
