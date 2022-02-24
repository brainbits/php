<?php

declare(strict_types=1);

namespace Tools\PackageInstaller;

use Tools\Config\PackageConfig;

class InstallResult
{
    /** @var PackageConfig */
    private $config;
    /** @var Version|null */
    private $version;

    public function __construct(PackageConfig $config, ?Version $version)
    {
        $this->config = $config;
        $this->version = $version;
    }

    public function getConfig(): PackageConfig
    {
        return $this->config;
    }

    public function getVersion(): ?Version
    {
        return $this->version;
    }
}
