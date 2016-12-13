<?php

namespace Gao\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        return $this->render(
            'GaoAdminBundle:Security:login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error' => $error,
            )
        );
    }

    public function forgotAction(Request $request)
    {
        $session = $request->getSession();

        $post = Request::createFromGlobals();
        if ($post->request->has('submit')) {
            if (!$this->get('form.csrf_provider')->isCsrfTokenValid('forgot_password', $post->request->get('csrf_token'))) {
                var_dump("token wrong!");die;
            }
            $email = $post->request->get('email');
            $username = $post->request->get('username');

            $session->getFlashBag()->add('success', 'He thong da gui thong tin cap nhat tai khoan cho ban. Vui long kiem tra email cua ban.');

            //$messageTemplate = $this->getParameter('email_forgot_message');
            //$message = sprintf($messageTemplate, $email, $username);
        }

        return $this->render(
            'GaoAdminBundle:Security:forgot.html.twig'
        );
    }

    public function registerAction(Request $request)
    {
        $session = $request->getSession();
    
        $post = Request::createFromGlobals();
        if ($post->request->has('submit')) {
            if (!$this->get('form.csrf_provider')->isCsrfTokenValid('forgot_password', $post->request->get('csrf_token'))) {
                var_dump("token wrong!");die;
            }
            $email = $post->request->get('email');
            $username = $post->request->get('username');
    
            $session->getFlashBag()->add('success', 'He thong da gui thong tin cap nhat tai khoan cho ban. Vui long kiem tra email cua ban.');
    
            //$messageTemplate = $this->getParameter('email_forgot_message');
            //$message = sprintf($messageTemplate, $email, $username);
        }
    
        return $this->render(
            'GaoAdminBundle:Security:register.html.twig'
        );
    }
}
