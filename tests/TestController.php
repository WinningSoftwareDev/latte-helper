<?php

declare(strict_types=1);

namespace LatteHelper\Tests;

use LatteHelper\Controller\AbstractLatteController;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class TestController extends AbstractLatteController
{
    protected string $templateDir = '/tests/templates';

    public function __construct(?ContainerInterface $container = null)
    {
        if ($container !== null) {
            $this->container = $container;
        }
    }

    public function index(): Response
    {
        return $this->renderTemplate('index', [
            'appName' => $this->getApp()->getEnvironmentOption('app_name'),
        ]);
    }
}
