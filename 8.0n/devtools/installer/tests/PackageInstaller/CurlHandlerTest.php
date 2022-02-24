<?php

declare(strict_types=1);

namespace ToolsTest\PackageInstaller;

use PHPUnit\Framework\TestCase;
use Tools\Config\CurlConfig;
use Tools\Config\PackageConfig;
use Tools\IO\Dirs;
use Tools\IO\MockExecutor;
use Tools\IO\MockFilesystem;
use Tools\PackageInstaller\CurlHandler;

class CurlHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $executor = new MockExecutor();
        $filesystem = new MockFilesystem();
        $dirs = new Dirs($filesystem, 'foo', 'bar', 'baz', 'gna');

        $executor->addResult(0, 'bar');

        $packageConfig = new PackageConfig();
        $packageConfig->handler = new CurlConfig();
        $packageConfig->handler->target = 'bli';
        $packageConfig->handler->url = 'blo';

        $handler = new CurlHandler($executor, $filesystem, $dirs, 'curl');
        $result = $handler->handle($packageConfig);

        $this->assertSame($packageConfig, $result->getConfig());
        $this->assertSame(null, $result->getVersion());
    }
}
