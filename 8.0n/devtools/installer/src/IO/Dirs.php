<?php

declare(strict_types=1);

namespace Tools\IO;

use function array_pad;
use function array_shift;
use function count;
use function explode;
use function implode;
use function is_dir;
use function rtrim;
use function sprintf;
use function str_replace;

class Dirs
{
    /** @var string */
    private $buildDir;
    /** @var string */
    private $binDir;
    /** @var string */
    private $finalBinDir;
    /** @var string */
    private $libDir;
    /** @var string */
    private $finalLibDir;
    /** @var string */
    private $shareDir;
    /** @var string */
    private $finalShareDir;
    /** @var string */
    private $binDirToLibDir;

    public function __construct(
        Filesystem $filesystem,
        string $buildDir,
        string $binDir,
        string $libDir,
        string $shareDir
    ) {
        $this->buildDir = $buildDir;
        $this->binDir = sprintf('%s%s', $buildDir, $binDir);
        $this->finalBinDir = $binDir;
        $this->libDir = sprintf('%s%s', $buildDir, $libDir);
        $this->finalLibDir = $libDir;
        $this->shareDir = sprintf('%s%s', $buildDir, $shareDir);
        $this->finalShareDir = $shareDir;
        $this->binDirToLibDir = rtrim($this->getRelativePath($binDir.'/', $libDir.'/'), '/');

        $filesystem->mkdir($this->binDir, 0755);
        $filesystem->mkdir($this->libDir, 0755);
        $filesystem->mkdir($this->shareDir, 0755);
    }

    public function buildDir(): string
    {
        return $this->buildDir;
    }

    public function binDir(): string
    {
        return $this->binDir;
    }

    /**
     * @param string[] $items
     *
     * @return string[]
     */
    public function replaceParts(array $items): array
    {
        $newItems = [];
        foreach ($items as $item) {
            $newItems[] = $this->replace($item);
        }

        return $newItems;
    }

    public function replace(string $item): string
    {
        return str_replace(
            [
                '%buildDir%',
                '%libDir%',
                '%finalLibDir%',
                '%shareDir%',
                '%finalShareDir%',
                '%binDir%',
                '%finalBinDir%',
                '%binDirToLibDir%',
            ],
            [
                $this->buildDir,
                $this->libDir,
                $this->finalLibDir,
                $this->shareDir,
                $this->finalShareDir,
                $this->binDir,
                $this->finalBinDir,
                $this->binDirToLibDir,
            ],
            $item
        );
    }

    private function getRelativePath(string $from, string $to): string
    {
        // some compatibility fixes for Windows paths
        $from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
        $to = is_dir($to) ? rtrim($to, '\/') . '/'   : $to;
        $from = str_replace('\\', '/', $from);
        $to = str_replace('\\', '/', $to);

        $from = explode('/', $from);
        $to = explode('/', $to);
        $relPath = $to;

        foreach ($from as $depth => $dir) {
            // find first non-matching dir
            if ($dir === $to[$depth]) {
                // ignore this directory
                array_shift($relPath);
            } else {
                // get number of remaining dirs to $from
                $remaining = count($from) - $depth;
                if ($remaining > 1) {
                    // add traversals up to first matching dir
                    $padLength = (count($relPath) + $remaining - 1) * -1;
                    $relPath = array_pad($relPath, $padLength, '..');
                    break;
                }

                $relPath[0] = './' . $relPath[0];
            }
        }

        return implode('/', $relPath);
    }
}
