<?php

declare(strict_types=1);

namespace LatteHelper\Classes;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class LatteAwareApplication
{
    private ?CsrfTokenManagerInterface $csrfManager = null;
    private RequestStack $requestStack;
    private Session $session;
    private ?UserInterface $user;
    private ?RouterInterface $router = null;

    public function getRequestStack(): RequestStack
    {
        return $this->requestStack;
    }

    public function setRequestStack(RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
        $session = $requestStack->getSession() instanceof Session
            ? $requestStack->getSession()
            : new Session();
        $this->setSession($session);
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public function setSession(Session $session): void
    {
        $this->session = $session;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getCsrf(string $id): string
    {
        if (!$this->csrfManager) {
            throw new \LogicException('CSRF manager not available.');
        }

        return $this->csrfManager->getToken($id)->getValue();
    }

    public function setCsrfManager(CsrfTokenManagerInterface $manager): void
    {
        $this->csrfManager = $manager;
    }

    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
    }

    /**
     * @param string $routeName
     * @param array<string, mixed> $parameters
     * @param int $referenceType
     *
     * @return string
     */
    public function generateUrl(string $routeName, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        if (!$this->router) {
            throw new \LogicException('Router not available.');
        }

        return $this->router->generate($routeName, $parameters, $referenceType);
    }

    /**
     * @param string $type
     *
     * @return array<int, string>
     */
    public function getFlashes(string $type): array
    {
        $flashes = [];

        foreach ($this->session->getFlashBag()->get($type) as $message) {
            if (!is_string($message)) {
                continue;
            }

            $flashes[] = $message;
        }

        return $flashes;
    }

    public function getEnvironmentOption(string $envVarName): mixed
    {
        return $_ENV[strtoupper($envVarName)] ?? null;
    }
}
