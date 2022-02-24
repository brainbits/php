<?php

declare(strict_types=1);

namespace Tools\IO;

use function fwrite;
use const PHP_EOL;
use const STDERR;

class Output
{
    public function writeln(string $line): void
    {
        fwrite(STDERR, $line.PHP_EOL);
    }
}
