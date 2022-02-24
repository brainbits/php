<?php

declare(strict_types=1);

namespace Tools\IO;

use Symfony\Component\Process\Process;

class SymfonyProcessExecutor implements Executor
{
    /** @var Logger */
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string[] $command
     */
    public function __invoke(array $command): CommandResult
    {
        $output = null;
        $rc = null;

        $process = new Process($command);
        $this->logger->command($process->getCommandLine());
        $rc = $process->run();

        if (!$process->isSuccessful()) {
            $this->logger->commandOutput('ERROR: ' . $process->getOutput());
            $this->logger->commandOutput('ERROR: ' . $process->getErrorOutput());
            throw new \RuntimeException('Process failed');
        }

        $this->logger->commandOutput($process->getOutput());
        $this->logger->commandOutput($process->getErrorOutput());

        return new CommandResult($process->getCommandLine(), $rc, $process->getOutput().$process->getErrorOutput());
    }
}
