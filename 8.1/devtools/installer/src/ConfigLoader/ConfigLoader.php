<?php

declare(strict_types=1);

namespace Tools\ConfigLoader;

use Tools\Config\PackageConfig;

interface ConfigLoader
{
    /**
     * @return PackageConfig[]
     */
    public function load(string $filename): array;
}
