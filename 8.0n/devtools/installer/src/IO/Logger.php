<?php

declare(strict_types=1);

namespace Tools\IO;

use function trim;

class Logger
{
    /** @var Output */
    private $output;

    public function __construct(Output $output)
    {
        $this->output = $output;
    }

    public function line(string $line): void
    {
        $this->output->writeln($line);
    }

    public function marker(string $line, string $marker = '###'): void
    {
        $this->output->writeln($marker.' '.$line);
    }

    public function constraint(string $line): void
    {
        $this->marker($line, '  ===');
    }

    public function command(string $line): void
    {
        $this->marker($line, '  >>>');
    }

    public function commandOutput(string $line): void
    {
        $line = trim($line);
        if (!$line) {
            return;
        }

        if (strlen($line) > 1000) {
            $line = substr($line, 0, 1000) . ' [... '.(strlen($line) - 1000).' more lines]';
        }

        $this->marker($line, '    >>>');
    }

    public function function(string $line): void
    {
        $this->marker($line, '  $$$');
    }

    public function functionOutput(string $line): void
    {
        $line = trim($line);
        if (!$line) {
            return;
        }

        $this->marker($line, '    $$$');
    }
}
