<?php

declare(strict_types=1);

namespace Tools\ConfigFilter;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Tools\Config\ConstraintsConfig;
use Tools\Config\PackageConfig;
use Tools\IO\Logger;
use function array_filter;
use function json_encode;
use function sprintf;
use const PHP_VERSION_ID;

final class ConstraintsConfigFilter implements ConfigFilter
{
    /** @var Logger */
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param PackageConfig[] $configs
     *
     * @return PackageConfig[]
     */
    public function __invoke(array $configs): array
    {
        $expressionLanguage = new ExpressionLanguage();

        $onlyConfigs = array_filter(
            $configs,
            function (PackageConfig $config) {
                $result = $config->constraints instanceof ConstraintsConfig && $config->constraints->only;

                if ($result) {
                    $this->logger->constraint('Only ' . $config->name);
                }

                return $result;
            }
        );
        if ($onlyConfigs) {
            $configs = $onlyConfigs;
        }

        $configs = array_filter(
            $configs,
            function (PackageConfig $config) {
                if (!$config->constraints instanceof ConstraintsConfig || !$config->constraints->skip) {
                    return true;
                }

                $this->logger->constraint('Skipping ' . $config->name);

                return false;
            }
        );

        $configs = array_filter(
            $configs,
            function (PackageConfig $config) use ($expressionLanguage) {
                if (!$config->constraints instanceof ConstraintsConfig || !$config->constraints->skipIf) {
                    return true;
                }

                $data = ['php_version_id' => PHP_VERSION_ID];

                $result = $expressionLanguage->evaluate(
                    $config->constraints->skipIf,
                    $data
                );

                if ($result) {
                    $this->logger->constraint(sprintf(
                        'skipIf: Skipping %s, expression "%s" evaluated to false with %s',
                        $config->name,
                        $config->constraints->skipIf,
                        json_encode($data)
                    ));
                } else {
                    $this->logger->constraint(sprintf(
                        'skipIf: Install %s, expression "%s" evaluated to true with %s',
                        $config->name,
                        $config->constraints->skipIf,
                        json_encode($data)
                    ));
                }

                return !$result;
            }
        );

        return $configs;
    }
}
