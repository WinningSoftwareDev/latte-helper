<?php

declare(strict_types=1);

namespace LatteHelper\Classes\Latte;

use Latte\Engine;
use Latte\Extension;
use Latte\Loaders\FileLoader;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class LatteEngineFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $projectDir
     * @param string[] $extensionsConfigPath
     */
    public function __construct(
        private ContainerInterface $container,
        private string $projectDir,
        private array $extensionsConfigPath = ['/config/latte.php']
    ) {}

    /**
     * @param string $templateDir
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface
     *
     * @return Engine
     */
    public function createEngine(string $templateDir): Engine
    {
        if (!str_starts_with($templateDir, '/')) {
            $templateDir = "/{$templateDir}";
        }

        $engine = new Engine();
        $fileLoader = new FileLoader($this->projectDir . $templateDir);

        $engine->setTempDirectory($this->projectDir . '/var/cache/latte');
        $engine->setLoader($fileLoader);

        foreach ($this->loadConfig() as $class => $args) {
            $resolvedArgs = array_map(function ($arg) {
                return is_string($arg) && str_starts_with($arg, '@')
                    ? $this->container->get(substr($arg, 1))
                    : $arg;
            }, $args);

            $extension = new $class(...$resolvedArgs);

            if ($extension instanceof Extension) {
                $engine->addExtension($extension);
            }
        }

        return $engine;
    }

    /**
     * @return array<string, array<int|string, mixed>>
     */
    private function loadConfig(): array
    {
        $config = [];

        foreach ($this->extensionsConfigPath as $path) {
            $fullPath = $this->projectDir . $path;
            if (!file_exists($fullPath)) {
                continue;
            }

            $loadedConfig = require $fullPath;

            if (is_array($loadedConfig)) {
                foreach ($loadedConfig as $class => $args) {
                    if (is_string($class) && is_array($args)) {
                        $config[$class] = $args;
                    }
                }
            }
        }

        return $config;
    }
}
