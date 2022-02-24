<?php

declare(strict_types=1);

namespace Tools\Config;

final class PackageConfig implements Config
{
    /** @var string */
    public $name;
    /** @var MetaConfig */
    public $meta;
    /** @var HandlerConfig */
    public $handler;
    /** @var ConstraintsConfig|null */
    public $constraints;
    /** @var HooksConfig|null */
    public $hooks;
    /** @var TestConfig|null */
    public $test;
    /** @var VersionConfig|null */
    public $version;

    /**
     * @param mixed[] $data
     *
     * @return PackageConfig
     */
    public static function fromArray(array $data): Config
    {
        $config = new self();

        $type = $data['handler']['type'];

        switch ($type) {
            case 'apk':
                $config->handler = ApkConfig::fromArray($data['handler']);
                break;

            case 'composer':
                $config->handler = ComposerConfig::fromArray($data['handler']);
                break;

            case 'curl':
                $config->handler = CurlConfig::fromArray($data['handler']);
                break;

            case 'noop':
                $config->handler = NoopConfig::fromArray($data['handler']);
                break;

            default:
                throw new \RuntimeException('Unknown installer type ' . $type);
        }

        $config->name = $data['name'];
        $config->meta = MetaConfig::fromArray($data['meta']);
        $config->version = isset($data['version']) ? VersionConfig::fromArray($data['version']) : null;
        $config->test = isset($data['test']) ? TestConfig::fromArray($data['test']) : null;
        $config->hooks = isset($data['hooks']) ? HooksConfig::fromArray($data['hooks']) : null;
        $config->constraints = isset($data['constraints']) ? ConstraintsConfig::fromArray($data['constraints']) : null;

        return $config;
    }
}
