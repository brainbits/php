<?php

declare(strict_types=1);

namespace Tools\PackageInstaller;

use Tools\Config\NoopConfig;
use Tools\Config\PackageConfig;
use function assert;

class NoopHandler implements Handler
{
    public function getName(): string
    {
        return 'noop';
    }

    public function handle(PackageConfig $config): InstallResult
    {
        assert($config->handler instanceof NoopConfig);

        return new InstallResult($config, null);
    }
}
