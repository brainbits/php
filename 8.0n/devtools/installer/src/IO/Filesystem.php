<?php

declare(strict_types=1);

namespace Tools\IO;

interface Filesystem
{
    public function exists(string $file): bool;

    public function chdir(string $dir): void;

    public function dumpFile(string $filename, string $content): void;

    public function mkdir(string $dir, int $mode = 0777): void;

    public function chmod(string $file, int $mode): void;

    public function copy(string $originalFile, string $targetFile): void;

    public function symlink(string $originDir, string $targetDir): void;
}
