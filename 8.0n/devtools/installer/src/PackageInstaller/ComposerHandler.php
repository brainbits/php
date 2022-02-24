<?php

declare(strict_types=1);

namespace Tools\PackageInstaller;

use Tools\Config\ComposerConfig;
use Tools\Config\PackageConfig;
use Tools\IO\CommandResult;
use Tools\IO\Dirs;
use Tools\IO\Executor;
use Tools\IO\Filesystem;
use function assert;
use function is_string;
use function json_encode;
use function preg_match;
use function strpos;
use const JSON_FORCE_OBJECT;
use const JSON_UNESCAPED_SLASHES;

class ComposerHandler implements Handler
{
    /** @var Executor */
    private $executor;
    /** @var Filesystem */
    private $filesystem;
    /** @var Dirs */
    private $dirs;
    /** @var string */
    private $composerCmd;

    public function __construct(Executor $executor, Filesystem $filesystem, Dirs $dirs, string $composerCmd)
    {
        $this->executor = $executor;
        $this->filesystem = $filesystem;
        $this->dirs = $dirs;
        $this->composerCmd = $composerCmd;
    }

    public function getName(): string
    {
        return 'composer';
    }

    public function handle(PackageConfig $config): InstallResult
    {
        assert($config->handler instanceof ComposerConfig);

        $composerCmd = $this->composerCmd;
        if ($config->handler->cmd) {
          $composerCmd = $config->handler->cmd;
        }

        $targetDir = $this->dirs->replace($config->handler->target);

        if ($this->filesystem->exists($targetDir)) {
            return new InstallResult($config, null);
        }

        if ($config->handler->require) {
            $this->filesystem->mkdir($targetDir);
            $this->filesystem->chdir($targetDir);

            $json = [
                'require' => [],
            ];

            if ($config->handler->registries) {
                $json['repositories'] = $config->handler->registries;
            }

            if ($config->handler->replace) {
                $json['replace'] = $config->handler->replace;
            }

            $json = json_encode($json, JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT);
            assert(is_string($json));

            $this->filesystem->dumpFile('composer.json', $json);

            $commandParts = [$composerCmd, 'require', '--no-interaction', '--no-progress', '--prefer-dist', '--prefer-stable', '--optimize-autoloader'];
            if ($config->handler->ignorePlatformReqs) {
                $commandParts[] = '--ignore-platform-reqs';
            }
            foreach ($config->handler->require as $require) {
                $commandParts[] = $require;
            }

            $commandResult = ($this->executor)($commandParts);
        } elseif ($config->handler->createProject) {
            $commandParts = [$composerCmd, 'create-project', '--no-interaction', '--no-progress', '--prefer-dist'];
            if ($config->handler->ignorePlatformReqs) {
                $commandParts[] = '--ignore-platform-reqs';
            }

            $commandParts[] = $config->handler->createProject;
            $commandParts[] = $targetDir;

            $commandResult = ($this->executor)($commandParts);

            $this->filesystem->chdir($targetDir);
            $commandParts = [$composerCmd, 'update'];
            ($this->executor)($commandParts);
        } else {
            throw new \RuntimeException('Composer installation type has to be either require or createProject');
        }

        if (!$config->handler->versionMatch) {
            throw new \RuntimeException('Need composer handler versionMatch');
        }

        $packageName = $config->handler->versionMatch;

        $version = null;
        foreach ($commandResult->getOutput() as $line) {
            if (strpos($line, $packageName) !== false && preg_match('/\(v?(\d+.\d+.\d+.*?)\)/', $line, $matches)) {
                $version = new Version($config->name, 'composer', $matches[1]);

                break;
            }
        }

        return new InstallResult($config, $version);
    }
}
