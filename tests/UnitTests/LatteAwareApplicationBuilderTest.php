<?php

declare(strict_types=1);

namespace LatteHelper\Tests\UnitTests;

use LatteHelper\Classes\LatteAwareApplicationBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class LatteAwareApplicationBuilderTest extends TestCase
{
    public function testBuild(): void
    {
        $user = $this->createMock(UserInterface::class);
        $latteAwareApplication = LatteAwareApplicationBuilder::build(
            $user,
            $this->getMockContainer()
        );
        $this->assertSame($user, $latteAwareApplication->getUser());
    }

    public function testItThrowsExceptionWhenServiceNotFound(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('request_stack service not found');
        LatteAwareApplicationBuilder::build(
            $this->createMock(UserInterface::class),
            $this->createMock(ContainerInterface::class)
        );
    }

    public function testItSetsSession(): void
    {
        $user = $this->createMock(UserInterface::class);
        $latteAwareApplication = LatteAwareApplicationBuilder::build(
            $user,
            $this->getMockContainer(true)
        );
        $this->assertInstanceOf(RequestStack::class, $latteAwareApplication->getRequestStack());
        $this->assertInstanceOf(SessionInterface::class, $latteAwareApplication->getSession());
    }

    /**
     * @return ContainerInterface&MockObject
     */
    private function getMockContainer(bool $withSession = false): ContainerInterface&MockObject
    {
        $container = $this->createMock(ContainerInterface::class);
        $requestStack = $this->createMock(RequestStack::class);

        if ($withSession) {
            $requestStack->method('getSession')->willReturn($this->createMock(Session::class));
        }

        $container->method('get')
            ->willReturn($requestStack)
            ->with('request_stack');

        return $container;
    }
}
