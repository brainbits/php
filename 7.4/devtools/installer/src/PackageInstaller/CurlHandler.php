<?php

declare(strict_types=1);

namespace Tools\PackageInstaller;

use Tools\Config\CurlConfig;
use Tools\Config\PackageConfig;
use Tools\IO\Dirs;
use Tools\IO\Executor;
use Tools\IO\Filesystem;
use function assert;
use function dirname;
use function end;
use function json_decode;
use function preg_match;
use function str_contains;
use const PHP_EOL;

class CurlHandler implements Handler
{
    /** @var Executor */
    private $executor;
    /** @var Dirs */
    private $dirs;
    /** @var Filesystem */
    private $filesystem;
    /** @var string */
    private $curlCmd;

    public function __construct(Executor $executor, Filesystem $filesystem, Dirs $dirs, string $curlCmd)
    {
        $this->executor = $executor;
        $this->filesystem = $filesystem;
        $this->dirs = $dirs;
        $this->curlCmd = $curlCmd;
    }

    public function getName(): string
    {
        return 'curl';
    }

    public function handle(PackageConfig $config): InstallResult
    {
        assert($config->handler instanceof CurlConfig);

        $target = $this->dirs->replace($config->handler->target);

        $this->filesystem->mkdir(dirname($target));

        $url = $config->handler->url;
        $version = null;

        if (str_contains($url, 'https://api.github.com/')) {
            $arch = strtolower(php_uname('m'));
            $os = strtolower(php_uname('s'));
            $matches = [];

            foreach ($config->handler->match as $matchValue) {
                $matches[] = str_replace(
                    ['%arch%', '%os%'],
                    [$arch, $os],
                    $matchValue,
                );
            }

            foreach ($config->handler->matchMap as $from => $to) {
                foreach ($matches as $index => $match) {
                    if ($match === $from) {
                        $matches[$index] = $to;
                    }
                }
            }

            if (!count($matches)) {
                throw new \RuntimeException('No matches configured');
            }

            $commandParts = [$this->curlCmd, '-Ls', $url];
            $commandResult = ($this->executor)($commandParts);
            $data = (array) json_decode($commandResult->getOutputAsString(), true);
            $versions = [];
            foreach ($data as $versionData) {
                $versions[$versionData['name']] = $versionData;
            }

            ksort($versions);
            $versionData = end($versions);
            $url = null;
            foreach ($versionData['assets'] as $asset) {
                $hasMatch = true;
                foreach ($matches as $match) {
                    if (!preg_match('/'.$match.'/', $asset['name'])) {
                        echo 'no match: '.$asset['name'].' due to '.$match.PHP_EOL;
                        $hasMatch = false;
                    }
                }

                if ($hasMatch) {
                    $url = $asset['browser_download_url'];
                    break;
                }
            }

            if (!$url) {
                throw new \RuntimeException('no url');
            }

            $version = new Version(
                $config->name,
                'curl',
                $versionData['name'][0] === 'v' ? substr($versionData['name'], 1) : $versionData['name']
            );
        }

        $commandParts = [$this->curlCmd, '-LsS', '-o', $target, $url];
        $commandResult = ($this->executor)($commandParts);

        $this->filesystem->chmod($target, 0755);

        if ($config->handler->fixedVersion) {
            $version = new Version($config->name, 'curl', $config->handler->fixedVersion);
        }

        return new InstallResult($config, $version);
    }
}
