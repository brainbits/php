<?php

declare(strict_types=1);

namespace Tools\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Tools\ConfigLoader\JsonConfigLoader;
use Tools\PackageInstaller\PackageInstaller;
use function assert;
use function file_exists;
use function is_executable;
use function is_string;

class InstallPackagesCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'install-packages';

    protected function configure(): void
    {
        $this
            ->setDescription('Generates tests.')
            ->setHelp('This command allows you to generate tests...')
            ->addArgument('configFile', InputArgument::REQUIRED)
            ->addArgument('buildDir', InputArgument::REQUIRED)
            ->addArgument('composer', InputArgument::REQUIRED)
            ->addOption('dryRun', null, InputOption::VALUE_NONE);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        assert(is_string($input->getArgument('configFile')));
        $configFile = (string) $input->getArgument('configFile');
        assert(is_string($input->getArgument('buildDir')));
        $buildDir = (string) $input->getArgument('buildDir');
        assert(is_string($input->getArgument('composer')));
        $composer = (string) $input->getArgument('composer');

        $output->writeln('install-packages configFile: '.$configFile);
        $output->writeln('install-packages buildDir: '.$buildDir);
        $output->writeln('install-packages composer: '.$composer);

        if (!file_exists($configFile)) {
            $output->writeln('Config file "'.$configFile.'" not found');

            return 1;
        }

        if (!file_exists($composer)) {
            $output->writeln('composer not found at "'.$composer.'"');

            return 1;
        }

        if (!is_executable($composer)) {
            $output->writeln('composer not executable at "'.$composer.'"');

            return 1;
        }

        $dryRun = false;
        if ($input->getOption('dryRun')) {
            $dryRun = true;
        }

        $configLoader = new JsonConfigLoader();
        $configs = $configLoader->load($configFile);

        $options = [
            'apk' => 'apk',
            'composer' => $composer,
            'curl' => 'curl',
        ];

        $installer = new PackageInstaller($buildDir, $options);
        $installer->install($configs, $dryRun);

        return 0;
    }
}
