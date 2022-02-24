<?php

declare(strict_types=1);

namespace Tools\PackageInstaller;

use Tools\Config\PackageConfig;

interface Handler
{
    public function getName(): string;

    public function handle(PackageConfig $config): InstallResult;
}
