<?php

declare(strict_types=1);

namespace Tools\IO;

interface Executor
{
    /**
     * @param mixed[] $command
     */
    public function __invoke(array $command): CommandResult;
}
