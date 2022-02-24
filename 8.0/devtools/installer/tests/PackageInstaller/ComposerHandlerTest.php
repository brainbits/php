<?php

declare(strict_types=1);

namespace ToolsTest\PackageInstaller;

use PHPUnit\Framework\TestCase;
use Tools\Config\ComposerConfig;
use Tools\Config\PackageConfig;
use Tools\IO\Dirs;
use Tools\IO\MockExecutor;
use Tools\IO\MockFilesystem;
use Tools\PackageInstaller\ComposerHandler;

// phpcs:disable Generic.Files.LineLength.TooLong

class ComposerHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $executor = new MockExecutor();
        $filesystem = new MockFilesystem();
        $dirs = new Dirs($filesystem, 'aa', 'bb', 'cc', 'dd');

        $executor->addResult(
            0,
            <<<EOL
Using version ^0.6.0 for dave-liddament/sarb
./composer.json has been created
Loading composer repositories with package information
Updating dependencies (including require-dev)
Package operations: 14 installs, 0 updates, 0 removals
  - Installing symfony/polyfill-ctype (v1.15.0): Loading from cache
  - Installing webmozart/assert (1.7.0): Loading from cache
  - Installing webmozart/path-util (2.3.0): Loading from cache
  - Installing symfony/yaml (v5.0.7): Loading from cache
  - Installing symfony/process (v5.0.7): Loading from cache
  - Installing psr/container (1.0.0): Loading from cache
  - Installing symfony/service-contracts (v2.0.1): Loading from cache
  - Installing symfony/dependency-injection (v5.0.7): Loading from cache
  - Installing symfony/polyfill-php73 (v1.15.0): Loading from cache
  - Installing symfony/polyfill-mbstring (v1.15.0): Loading from cache
  - Installing symfony/console (v5.0.7): Loading from cache
  - Installing symfony/filesystem (v5.0.7): Loading from cache
  - Installing symfony/config (v5.0.7): Loading from cache
  - Installing dave-liddament/sarb (0.6.0): Loading from cache
symfony/dependency-injection suggests installing symfony/finder (For using double-star glob patterns or when GLOB_BRACE portability is required)
symfony/dependency-injection suggests installing symfony/expression-language (For using expressions in service container configuration)
symfony/dependency-injection suggests installing symfony/proxy-manager-bridge (Generate service proxies to lazy load them)
symfony/console suggests installing symfony/event-dispatcher
symfony/console suggests installing symfony/lock
symfony/console suggests installing psr/log (For using the console logger)
Writing lock file
Generating optimized autoload files
9 packages you are using are looking for funding.
Use the `composer fund` command to find out more!
EOL
        );

        $packageConfig = new PackageConfig();
        $packageConfig->name = 'sarb';
        $packageConfig->handler = new ComposerConfig();
        $packageConfig->handler->target = '/tools';
        $packageConfig->handler->require = ['sarb'];
        $packageConfig->handler->versionMatch = 'dave-liddament/sarb';

        $handler = new ComposerHandler($executor, $filesystem, $dirs, 'composer');
        $result = $handler->handle($packageConfig);

        $this->assertSame($packageConfig, $result->getConfig());
        $this->assertSame('sarb', $result->getVersion()->getName());
        $this->assertSame('composer', $result->getVersion()->getSource());
        $this->assertSame('0.6.0', $result->getVersion()->getVersion());
    }

    public function testHandle2(): void
    {
        $executor = new MockExecutor();
        $filesystem = new MockFilesystem();
        $dirs = new Dirs($filesystem, 'aa', 'bb', 'cc', 'dd');

        $executor->addResult(
            0,
            <<<EOL
Using version ^0.7.0 for dave-liddament/sarb
./composer.json has been updated
Loading composer repositories with package information
Updating dependencies (including require-dev)
Package operations: 14 installs, 0 updates, 0 removals
  - Installing symfony/polyfill-ctype (v1.15.0): Loading from cache
  - Installing webmozart/assert (1.7.0): Loading from cache
  - Installing webmozart/path-util (2.3.0): Loading from cache
  - Installing symfony/yaml (v5.0.7): Loading from cache
  - Installing symfony/process (v5.0.7): Loading from cache
  - Installing psr/container (1.0.0): Loading from cache
  - Installing symfony/service-contracts (v2.0.1): Loading from cache
  - Installing symfony/dependency-injection (v5.0.7): Loading from cache
  - Installing symfony/polyfill-php73 (v1.15.0): Loading from cache
  - Installing symfony/polyfill-mbstring (v1.15.0): Loading from cache
  - Installing symfony/console (v5.0.7): Loading from cache
  - Installing symfony/filesystem (v5.0.7): Loading from cache
  - Installing symfony/config (v5.0.7): Loading from cache
  - Installing dave-liddament/sarb (0.7.0): Downloading (100%)
symfony/dependency-injection suggests installing symfony/finder (For using double-star glob patterns or when GLOB_BRACE portability is required)
symfony/dependency-injection suggests installing symfony/expression-language (For using expressions in service container configuration)
symfony/dependency-injection suggests installing symfony/proxy-manager-bridge (Generate service proxies to lazy load them)
symfony/console suggests installing symfony/event-dispatcher
symfony/console suggests installing symfony/lock
symfony/console suggests installing psr/log (For using the console logger)
Writing lock file
Generating optimized autoload files
3 packages you are using are looking for funding.
Use the `composer fund` command to find out more!
EOL
        );

        $packageConfig = new PackageConfig();
        $packageConfig->name = 'sarb';
        $packageConfig->handler = new ComposerConfig();
        $packageConfig->handler->target = '/tools';
        $packageConfig->handler->require = ['sarb'];
        $packageConfig->handler->versionMatch = 'dave-liddament/sarb';

        $handler = new ComposerHandler($executor, $filesystem, $dirs, 'composer');
        $result = $handler->handle($packageConfig);

        $this->assertSame($packageConfig, $result->getConfig());
        $this->assertSame('sarb', $result->getVersion()->getName());
        $this->assertSame('composer', $result->getVersion()->getSource());
        $this->assertSame('0.7.0', $result->getVersion()->getVersion());
    }
}
