<?php

declare(strict_types=1);

namespace Tools\Config;

final class NoopConfig implements HandlerConfig
{
    /**
     * @param mixed[] $data
     *
     * @return NoopConfig
     */
    public static function fromArray(array $data): Config
    {
        return new self();
    }
}
