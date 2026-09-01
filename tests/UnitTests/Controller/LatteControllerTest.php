<?php

declare(strict_types=1);

namespace LatteHelper\Tests\UnitTests\Controller;

use LatteHelper\Classes\Latte\LatteEngineFactory;
use LatteHelper\Tests\TestController;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class LatteControllerTest extends TestCase
{
    private TestController $controller;

    protected function setUp(): void
    {
        $this->controller = new TestController($this->getMockContainer($this->getMockTokenStorage(), $this->getRequestStack()));
        $this->controller->setLatteFactory($this->createMock(LatteEngineFactory::class));
    }

    public function testItRendersTemplate(): void
    {
        $this->assertSame(200, $this->controller->index()->getStatusCode());
    }

    private function getMockContainer(TokenStorageInterface $tokenStorage, RequestStack $requestStack): ContainerInterface&MockObject
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')
            ->willReturnCallback(function (string $id) {
                return in_array($id, ['security.token_storage', 'request_stack'], true);
            });
        $container->method('get')
            ->willReturnCallback(function (string $id) use ($tokenStorage, $requestStack) {
                return match ($id) {
                    'security.token_storage' => $tokenStorage,
                    'request_stack' => $requestStack,
                    default => throw new \RuntimeException("Service $id not mocked"),
                };
            });

        return $container;
    }

    private function getRequestStack(): RequestStack
    {
        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);

        $requestStack = new RequestStack();
        $requestStack->push($request);

        return $requestStack;
    }

    private function getMockTokenStorage(): TokenStorageInterface&MockObject
    {
        $user = $this->createMock(UserInterface::class);
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($token);

        return $tokenStorage;
    }
}
