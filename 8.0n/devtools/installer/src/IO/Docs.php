<?php

declare(strict_types=1);

namespace Tools\IO;

use Tools\Config\MetaConfig;
use Tools\PackageInstaller\Version;
use function implode;
use function ksort;
use function sprintf;
use const PHP_EOL;

class Docs
{
    /** @var Filesystem */
    private $filesystem;
    /** @var string[] */
    private $docs = [];

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function register(Version $version, MetaConfig $meta): void
    {
        $this->docs[$version->getName()] = sprintf('* %s %s - %s - %s', $version->getName(), $version->getVersion(), $meta->summary, $meta->website);
    }

    public function getContent(): string
    {
        return implode(PHP_EOL, $this->docs);
    }

    public function write(string $path): void
    {
        ksort($this->docs);

        $this->filesystem->dumpFile($path, $this->getContent());
    }
}
