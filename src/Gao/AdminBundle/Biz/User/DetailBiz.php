<?php

namespace Gao\AdminBundle\Biz\User;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;
use Gao\AdminBundle\Biz\BizException;
use Gao\C5Bundle\Entity\Users;
use Gao\AdminBundle\Form\UserType;

/**
 * Class: DetailBiz.
 */
class DetailBiz
{
    /**
     * Service Container Interface.
     */
    private $container;

    /**
     * __construct.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function main($id = null)
    {
        $adminUser = $this->container->get('security.context')->getToken()->getUser();
        // Get request object.
        $request = $this->container->get('request');

        if ($id) {
            $user = $this->container->get('admin.user_service')->getEntity($id);
        }
        if (empty($user)) {
            $user = new Users();
            $user->setCreatorId($adminUser->getId());
            $user->setCLevel(10); // C1
            $user->setCurrentInterestRate($this->container->getParameter('default_interest_rate'));
            $user->setEmailVerified(1);
            $user->setBlocked(0);
        }
        $form = $this->container->get('form.factory')->create($this->container->get('admin_bundle.form_type.user'), $user);
        $form->handleRequest($request);

        // process the form on POST
        if ($request->isMethod('POST')) {
            if ($form->isValid()) {
                if (!$user->getId() && $this->container->get('admin.user_service')->isExist($user->getUsername())) {
                    $session = $request->getSession();
                    $session->getFlashBag()->add('unsuccess', 'Username have been used');
                } else {
                    $this->container->get('admin.user_service')->saveEntity($user);

                    $session = $request->getSession();
                    $session->getFlashBag()->add('success', 'An user have been saved!');

                    $exception = new BizException();
                    $exception->redirect = $this->container->get('router')->generate('gao_admin_user_list');

                    throw $exception;
                }
            }
        }

        return array(
            'form' => $form->createView()
        );
    }
}
