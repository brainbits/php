<?php

declare(strict_types=1);

namespace Tools\Config;

final class ComposerConfig implements HandlerConfig
{
    /** @var string */
    public $createProject;
    /** @var string[] */
    public $require;
    /** @var string */
    public $target;
    /** @var string[] */
    public $registries;
    /** @var string[] */
    public $replace;
    /** @var bool */
    public $ignorePlatformReqs;
    /** @var string */
    public $versionMatch;
    /** @var string */
    public $cmd;

    /**
     * @param mixed[] $data
     *
     * @return ComposerConfig
     */
    public static function fromArray(array $data): Config
    {
        $config = new self();

        $config->createProject = $data['createProject'] ?? null;
        $config->require = $data['require'] ?? null;
        $config->target = $data['target'] ?? null;
        $config->registries = $data['registries'] ?? null;
        $config->replace = $data['replace'] ?? null;
        $config->ignorePlatformReqs = (bool) ($data['ignorePlatformReqs'] ?? false);
        $config->versionMatch = $data['versionMatch'] ?? null;
        $config->cmd = $data['cmd'] ?? null;

        return $config;
    }
}
