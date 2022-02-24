<?php

declare(strict_types=1);

namespace Tools\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tools\ConfigLoader\JsonConfigLoader;
use Tools\TestGenerator\TestGenerator;
use function assert;
use function file_exists;
use function is_string;

class GenerateTestsCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'generate-tests';

    protected function configure(): void
    {
        $this
            ->setDescription('Generates tests.')
            ->setHelp('This command allows you to generate tests...')
            ->addArgument('configFile', InputArgument::REQUIRED)
            ->addArgument('buildDir', InputArgument::REQUIRED)
            ->addArgument('outputFile', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        assert(is_string($input->getArgument('buildDir')));
        $buildDir = (string) $input->getArgument('buildDir');
        assert(is_string($input->getArgument('configFile')));
        $configFile = (string) $input->getArgument('configFile');
        assert(is_string($input->getArgument('outputFile')));
        $outputFile = (string) $input->getArgument('outputFile');

        $output->writeln('generate-tests configFile: '.$configFile);
        $output->writeln('generate-tests buildDir: '.$buildDir);
        $output->writeln('generate-tests outputFile: '.$outputFile);

        if (!file_exists($configFile)) {
            $output->writeln('Config file "'.$configFile.'" not found');

            return 1;
        }

        $configLoader = new JsonConfigLoader();
        $configs = $configLoader->load($configFile);

        $tests = new TestGenerator($buildDir);
        $tests->generate($configs, $outputFile);

        return 0;
    }
}
