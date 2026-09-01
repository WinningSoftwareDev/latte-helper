<?php

declare(strict_types=1);

namespace LatteHelper\Classes\Latte\Trait;

use Latte\Engine;
use LatteHelper\Classes\Latte\LatteEngineFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

trait LatteFactoryTrait
{
    private ?LatteEngineFactory $latteFactory = null;

    public function setLatteFactory(LatteEngineFactory $factory): void
    {
        $this->latteFactory = $factory;
    }

    protected function getLatteFactory(): LatteEngineFactory
    {
        if (!$this->latteFactory) {
            throw new \LogicException('LatteEngineFactory not set. Did you configure the trait properly?');
        }

        return $this->latteFactory;
    }

    /**
     * @param string $templateDir
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface
     *
     * @return Engine
     */
    protected function getEngine(string $templateDir): Engine
    {
        return $this->getLatteFactory()->createEngine($templateDir);
    }
}
