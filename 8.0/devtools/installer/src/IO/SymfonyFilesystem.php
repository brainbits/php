<?php

declare(strict_types=1);

namespace Tools\IO;

use Symfony\Component\Filesystem\Filesystem as BaseFilesystem;
use function chdir;
use function sprintf;

class SymfonyFilesystem implements Filesystem
{
    /** @var Logger */
    private $logger;
    /** @var BaseFilesystem */
    private $filesystem;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
        $this->filesystem = new BaseFilesystem();
    }

    public function remove(string $file): void
    {
        $this->filesystem->remove($file);
    }

    public function exists(string $file): bool
    {
        return $this->filesystem->exists($file);
    }

    public function chdir(string $dir): void
    {
        chdir($dir);
    }

    public function dumpFile(string $filename, string $content): void
    {
        $this->logger->function(sprintf('dumpFile(%s)', $filename));

        $this->filesystem->dumpFile($filename, $content);
    }

    public function mkdir(string $dir, int $mode = 0777): void
    {
        $this->logger->function(sprintf('mkdir(%s, 0%o)', $dir, $mode));

        $this->filesystem->mkdir($dir, $mode);
    }

    public function chmod(string $file, int $mode): void
    {
        $this->logger->function(sprintf('chmod(%s, 0%o)', $file, $mode));

        $this->filesystem->chmod($file, $mode);
    }

    public function copy(string $originalFile, string $targetFile): void
    {
        $this->logger->function(sprintf('copy(%s, %s)', $originalFile, $targetFile));

        $this->filesystem->copy($originalFile, $targetFile, true);
    }

    public function symlink(string $originDir, string $targetDir): void
    {
        $this->logger->function(sprintf('symlink(%s, %s)', $originDir, $targetDir));

        if ($this->filesystem->exists($targetDir)) {
            $this->filesystem->remove($targetDir);
        }

        $this->filesystem->symlink($originDir, $targetDir);
    }
}
