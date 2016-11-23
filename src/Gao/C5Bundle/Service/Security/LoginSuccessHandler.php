<?php

namespace Gao\C5Bundle\Service\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{

    protected $container;
    protected $router;
    protected $security;

    public function __construct(Container $container, Router $router, SecurityContext $security)
    {
        $this->container = $container;
        $this->router = $router;
        $this->security = $security;
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($this->security->isGranted('ROLE_USER'))
        {
            $this->container->get('security_user_service')->updateLastLogin($token->getUser());
            // redirect the user to where they were before the login process begun.
            // $referer_url = $request->headers->get('referer');

            $response = new RedirectResponse($this->router->generate('gao_c5_homepage'));
        }

        return $response;
    }
}
