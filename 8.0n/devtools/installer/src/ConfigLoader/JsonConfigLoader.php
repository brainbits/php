<?php

declare(strict_types=1);

namespace Tools\ConfigLoader;

use Tools\Config\PackageConfig;
use function assert;
use function file_get_contents;
use function is_string;
use function json_decode;

final class JsonConfigLoader implements ConfigLoader
{
    /**
     * @return PackageConfig[]
     */
    public function load(string $filename): array
    {
        $content = file_get_contents($filename);

        assert(is_string($content));

        $data = json_decode($content, true);

        $configs = [];
        foreach ($data['tools'] as $item) {
            $configs[] = PackageConfig::fromArray($item);
        }

        return $configs;
    }
}
