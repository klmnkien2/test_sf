<?php

namespace Gao\C5Bundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoggedInUserListener
{
    private $router;
    private $container;

    public function __construct($router, $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $container = $this->container;
        if( $container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            // authenticated (NON anonymous)
            $routeName = $container->get('request')->get('_route');
            if ($routeName == "login") {
                $url = $this->router->generate("gao_c5_homepage");
                $event->setResponse(new RedirectResponse($url));
                return;
            }

            // If hard block
            $user = $container->get('security.context')->getToken()->getUser();
            if (!empty($user->getBlocked()) && $user->getBlocked() == 2) {
                $url = $this->router->generate("logout");
                $event->setResponse(new RedirectResponse($url));
                return;
            }
        }
    }
}