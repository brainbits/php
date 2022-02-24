<?php

declare(strict_types=1);

namespace Tools\TestGenerator;

use Tools\Config\PackageConfig;
use Tools\Config\TestConfig;
use Tools\ConfigFilter\ConstraintsConfigFilter;
use Tools\IO\Dirs;
use Tools\IO\Logger;
use Tools\IO\Output;
use Tools\IO\SymfonyFilesystem;
use function implode;
use function sprintf;
use const PHP_EOL;

class TestGenerator
{
    /** @var Logger */
    private $logger;
    /** @var SymfonyFilesystem */
    private $filesystem;
    /** @var Dirs */
    private $dirs;

    public function __construct(string $buildDir)
    {
        $this->logger = new Logger(new Output());
        $this->filesystem = new SymfonyFilesystem($this->logger);
        $this->dirs = new Dirs($this->filesystem, $buildDir, '/usr/bin', '/usr/lib/tools', '/usr/share/tools');
    }

    /**
     * @param PackageConfig[] $configs
     */
    public function generate(array $configs, string $outputFile): void
    {
        $parts = [];

        $parts[] = <<<'EOF'
#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}
EOF;
        $commandTpl = <<<'EOF'
@test "%s execution is successful" {
  run docker run --rm $TAG %s
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}
EOF;

        $existsTpl = <<<'EOF'
@test "%s exists" {
  run docker run --rm $TAG which %s
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}
EOF;

        $configs = (new ConstraintsConfigFilter($this->logger))($configs);

        foreach ($configs as $config) {
            if (!$config->test instanceof TestConfig) {
                continue;
            }

            if ($config->test->command) {
                $command = implode(' ', $config->test->command);
                $parts[] = sprintf($commandTpl, $config->name, $command);
            } elseif ($config->test->exists) {
                $parts[] = sprintf($existsTpl, $config->name, $this->dirs->replace($config->test->exists));
            }
        }

        $output = implode(PHP_EOL, $parts).PHP_EOL;

        $this->filesystem->dumpFile($outputFile, $output);
    }
}
