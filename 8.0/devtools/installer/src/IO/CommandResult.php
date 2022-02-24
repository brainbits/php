<?php

declare(strict_types=1);

namespace Tools\IO;

use function explode;
use const PHP_EOL;

class CommandResult
{
    /** @var string */
    private $command;
    /** @var int */
    private $returnCode;
    /** @var string */
    private $output;

    public function __construct(string $command, int $returnCode, string $output)
    {
        $this->command = $command;
        $this->returnCode = $returnCode;
        $this->output = $output;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getReturnCode(): int
    {
        return $this->returnCode;
    }

    /**
     * @return string[]
     */
    public function getOutput(): array
    {
        return explode(PHP_EOL, $this->output);
    }

    public function getOutputAsString(): string
    {
        return $this->output;
    }
}
