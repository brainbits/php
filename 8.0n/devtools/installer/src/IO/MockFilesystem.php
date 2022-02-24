<?php

declare(strict_types=1);

namespace Tools\IO;

use function in_array;

class MockFilesystem implements Filesystem
{
    /** @var string[] */
    private $existingFiles = [];

    public function addExisting(string $file): void
    {
        $this->existingFiles[] = $file;
    }

    public function remove(string $file): void
    {
    }

    public function exists(string $file): bool
    {
        return in_array($file, $this->existingFiles);
    }

    public function chdir(string $dir): void
    {
    }

    public function dumpFile(string $filename, string $content): void
    {
    }

    public function mkdir(string $dir, int $mode = 0777): void
    {
    }

    public function chmod(string $file, int $mode): void
    {
    }

    public function copy(string $originalFile, string $targetFile): void
    {
    }

    public function symlink(string $originDir, string $targetDir): void
    {
    }
}
