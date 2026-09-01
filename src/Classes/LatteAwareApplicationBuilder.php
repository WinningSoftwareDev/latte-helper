<?php

declare(strict_types=1);

namespace LatteHelper\Classes;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class LatteAwareApplicationBuilder
{
    /**
     * @param UserInterface|null $user
     * @param ContainerInterface $container
     *
     * @throws \RuntimeException
     *
     * @return LatteAwareApplication
     */
    public static function build(?UserInterface $user, ContainerInterface $container): LatteAwareApplication
    {
        $app = new LatteAwareApplication();
        $app->setUser($user);

        try {
            /**
             * @var RequestStack $requestStack
             */
            $requestStack = $container->get('request_stack');
            $app->setRequestStack($requestStack);
        } catch (ContainerExceptionInterface|\TypeError $e) {
            throw new \RuntimeException('request_stack service not found');
        }

        try {
            /** @var CsrfTokenManagerInterface $csrf */
            $csrf = $container->get('security.csrf.token_manager');
            $app->setCsrfManager($csrf);
        } catch (\Throwable) {
        }

        try {
            /** @var RouterInterface $router */
            $router = $container->get('router');
            $app->setRouter($router);
        } catch (\Throwable) {
        }


        return $app;
    }
}
