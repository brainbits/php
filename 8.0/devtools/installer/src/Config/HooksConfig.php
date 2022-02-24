<?php

declare(strict_types=1);

namespace Tools\Config;

final class HooksConfig implements Config
{
    /** @var mixed[] */
    public $copy;
    /** @var mixed[] */
    public $symlinks;
    /** @var string[][] */
    public $preInstall;
    /** @var string[][] */
    public $postInstall;

    /**
     * @param mixed[] $data
     *
     * @return HooksConfig
     */
    public static function fromArray(array $data): Config
    {
        $config = new self();

        $config->copy = $data['copy'] ?? null;
        $config->symlinks = $data['symlinks'] ?? null;
        $config->preInstall = $data['preInstall'] ?? null;
        $config->postInstall = $data['postInstall'] ?? null;

        return $config;
    }
}
