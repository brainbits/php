<?php

declare(strict_types=1);

namespace Tools\IO;

use function array_shift;
use function implode;

class MockExecutor implements Executor
{
    /** @var array<array{rc: int, output: string}> */
    private $results = [];
    /** @var mixed[] */
    private $commands = [];

    public function addResult(int $rc, string $output): void
    {
        $this->results[] = ['rc' => $rc, 'output' => $output];
    }

    /**
     * @param string[] $command
     */
    public function __invoke(array $command): CommandResult
    {
        if (!count($this->results)) {
            throw new \RuntimeException('No results');
        }

        $result = array_shift($this->results);
        $rc = $result['rc'];
        $output = $result['output'];

        $result = new CommandResult(implode(' ', $command), $rc, $output);

        $this->commands[$result->getCommand()] = $result;

        return $result;
    }

    /**
     * @return mixed[]
     */
    public function getCommands(): array
    {
        return $this->commands;
    }
}
