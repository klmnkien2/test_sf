<?php

namespace Gao\C5Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $usr = $this->get('security.context')->getToken()->getUser();
        return $this->render(
            'GaoC5Bundle:Default:index.html.twig',
            array(
                'error' => 1,
            )
        );
    }

    public function pdAction()
    {
        $usr = $this->get('security.context')->getToken()->getUser();
        return $this->render(
            'GaoC5Bundle:Default:pd.html.twig',
            array(
                'error' => 1,
            )
        );
    }

    public function gdAction()
    {
        $usr = $this->get('security.context')->getToken()->getUser();
        return $this->render(
            'GaoC5Bundle:Default:gd.html.twig',
            array(
                'error' => 1,
            )
        );
    }

    public function historyAction()
    {
        $usr = $this->get('security.context')->getToken()->getUser();
        return $this->render(
            'GaoC5Bundle:Default:history.html.twig',
            array(
                'error' => 1,
            )
        );
    }

    public function accountAction()
    {
        $usr = $this->get('security.context')->getToken()->getUser();
        return $this->render(
            'GaoC5Bundle:Default:account.html.twig',
            array(
                'error' => 1,
            )
        );
    }

    public function disputeAction()
    {
        $usr = $this->get('security.context')->getToken()->getUser();
        return $this->render(
            'GaoC5Bundle:Default:dispute.html.twig',
            array(
                'error' => 1,
            )
        );
    }

    public function testAction()
    {
        // Add a new User
        $user = new \Gao\C5Bundle\Entity\Users();
        $user->setUsername('test');
        $user->setSalt(uniqid(mt_rand())); // Unique salt for user

        // Set encrypted password
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $password = $encoder->encodePassword('123', $user->getSalt());
        $user->setPassword($password);
        var_dump($user);
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse(array('status' => 'DONE'));
    }
}
