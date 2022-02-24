<?php

declare(strict_types=1);

namespace Tools\ConfigFilter;

use Tools\Config\PackageConfig;

interface ConfigFilter
{
    /**
     * @param PackageConfig[] $configs
     *
     * @return PackageConfig[]
     */
    public function __invoke(array $configs): array;
}
