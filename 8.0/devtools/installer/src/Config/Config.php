<?php

declare(strict_types=1);

namespace Tools\Config;

interface Config
{
    /**
     * @param mixed[] $data
     */
    public static function fromArray(array $data): Config;
}
