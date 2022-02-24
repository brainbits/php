<?php

declare(strict_types=1);

namespace Tools\PackageInstaller;

use Tools\Config\PackageConfig;

class Version
{
    /** @var string */
    private $name;
    /** @var string */
    private $source;
    /** @var string */
    private $version;

    public function __construct(string $name, string $source, string $version)
    {
        $this->name = $name;
        $this->source = $source;
        $this->version = $version;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}
