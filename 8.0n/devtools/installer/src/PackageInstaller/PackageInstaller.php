<?php

declare(strict_types=1);

namespace Tools\PackageInstaller;

use Tools\Config\ApkConfig;
use Tools\Config\ComposerConfig;
use Tools\Config\CurlConfig;
use Tools\Config\HooksConfig;
use Tools\Config\NoopConfig;
use Tools\Config\PackageConfig;
use Tools\Config\VersionConfig;
use Tools\ConfigFilter\ConstraintsConfigFilter;
use Tools\IO\Dirs;
use Tools\IO\Docs;
use Tools\IO\Logger;
use Tools\IO\Output;
use Tools\IO\SymfonyFilesystem;
use Tools\IO\SymfonyProcessExecutor;
use function array_key_exists;
use function get_class;
use function preg_match;
use function print_r;
use function sprintf;
use function str_repeat;

class PackageInstaller
{
    /** @var mixed[] */
    private $options;
    /** @var Dirs */
    private $dirs;
    /** @var Logger */
    private $logger;
    /** @var SymfonyFilesystem */
    private $filesystem;
    /** @var SymfonyProcessExecutor */
    private $executor;

    /**
     * @param mixed[] $options
     */
    public function __construct(string $buildDir, array $options)
    {
        $this->options = $options;

        $this->logger = new Logger(new Output());
        $this->filesystem = new SymfonyFilesystem($this->logger);
        $this->executor = new SymfonyProcessExecutor($this->logger);
        $this->dirs = new Dirs($this->filesystem, $buildDir, '/usr/bin', '/usr/lib/tools', '/usr/share/tools');
    }

    /**
     * @param PackageConfig[] $configs
     */
    public function install(array $configs, bool $dryRun = false): void
    {
        $docs = new Docs($this->filesystem);

        $configs = (new ConstraintsConfigFilter($this->logger))($configs);

        $handlerMap = [
            // phpcs:ignore Generic.Files.LineLength.TooLong
            ApkConfig::class => new ApkHandler($this->executor, $this->filesystem, $this->dirs, $this->options['curl'], $this->options['apk']),
            // phpcs:ignore Generic.Files.LineLength.TooLong
            ComposerConfig::class => new ComposerHandler($this->executor, $this->filesystem, $this->dirs, $this->options['composer']),
            // phpcs:ignore Generic.Files.LineLength.TooLong
            CurlConfig::class => new CurlHandler($this->executor, $this->filesystem, $this->dirs, $this->options['curl']),
            // phpcs:ignore Generic.Files.LineLength.TooLong
            NoopConfig::class => new NoopHandler(),
        ];

        $installResults = [];

        foreach ($configs as $config) {
            $this->logger->line(str_repeat('-', 120));

            if (!array_key_exists(get_class($config->handler), $handlerMap)) {
                $this->logger->marker(sprintf(
                    'Skipping %s, unknown installer type %s',
                    $config->name,
                    get_class($config->handler)
                ));
                continue;
            }

            $handler = $handlerMap[get_class($config->handler)];

            $this->logger->marker('Installing ' . $config->name .  ' via ' . $handler->getName());

            if (!$dryRun && $config->hooks instanceof HooksConfig && $config->hooks->preInstall) {
                foreach ($config->hooks->preInstall as $commandParts) {
                    $commandParts = $this->dirs->replaceParts($commandParts);

                    ($this->executor)($commandParts);
                }
            }

            $installResult = null;

            if (!$dryRun) {
                $installResult = $handler->handle($config);
            }

            if (!$dryRun && $config->hooks instanceof HooksConfig && $config->hooks->postInstall) {
                foreach ($config->hooks->postInstall as $commandParts) {
                    $commandParts = $this->dirs->replaceParts($commandParts);

                    ($this->executor)($commandParts);
                }
            }

            if (!$dryRun && $config->hooks instanceof HooksConfig && $config->hooks->symlinks) {
                foreach ($config->hooks->symlinks as $target => $source) {
                    $this->filesystem->symlink(
                        $this->dirs->replace((string) $source),
                        $this->dirs->replace((string) $target)
                    );
                }
            }

            if (!$dryRun && $config->hooks instanceof HooksConfig && $config->hooks->copy) {
                foreach ($config->hooks->copy as $target => $source) {
                    $this->filesystem->copy(
                        $this->dirs->replace((string) $source),
                        $this->dirs->replace((string) $target)
                    );
                }
            }

            if (!$dryRun && $installResult) {
                $installResults[] = $this->version($installResult);
            }
        }

        foreach ($installResults as $installResult) {
            $version = $installResult->getVersion();
            if (!$version instanceof Version) {
                continue;
            }

            $this->logger->line($version->getName() . ': ' . $version->getVersion() . ' (' . $version->getSource() . ')');

            $docs->register($version, $installResult->getConfig()->meta);
        }

        $docs->write($this->dirs->replace('%shareDir%/motd'));

        $this->logger->line($docs->getContent());
    }

    private function version(InstallResult $installResult): InstallResult
    {
        $config = $installResult->getConfig();

        $version = null;

        if ($installResult->getVersion()) {
            $version = $installResult->getVersion();
        }

        if (!$version && $config->version instanceof VersionConfig && $config->version->command) {
            $source = 'Command';

            $commandResult = ($this->executor)($this->dirs->replaceParts($config->version->command));

            $version = 'unknown';
            $versionRegex = '/(\d+.\d+.\d+([-.@][a-zA-Z0-9]+)?)/m';
            if (isset($config->version->regex)) {
                $versionRegex = $config->version->regex;
            }

            $this->logger->function(sprintf('preg_match(%s)', $versionRegex));
            if (!preg_match($versionRegex, $commandResult->getOutputAsString(), $match)) {
                echo 'Version command resulted in no version: ';
                print_r($commandResult);
                exit(1);
            }

            $version = new Version($config->name, 'command', $match[1]);
        }

        if (!$version) {
            echo 'No version config';
            print_r($installResult);
            exit(1);
        }

        $this->logger->marker($version->getSource() . ' version: ' . $version->getVersion(), ' ');

        return new InstallResult($config, $version);
    }
}
