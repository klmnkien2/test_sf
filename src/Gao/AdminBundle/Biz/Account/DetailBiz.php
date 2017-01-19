<?php

namespace Gao\AdminBundle\Biz\Account;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;
use Gao\AdminBundle\Biz\BizException;
use Gao\AdminBundle\Entity\Admin;
use Gao\AdminBundle\Form\AdminType;

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
            $admin = $this->container->get('admin_service')->getEntity($id);
        }
        if (empty($admin)) {
            $admin = new Admin();
            $admin->setCreatorId($adminUser->getId());
            $admin->setEmailVerified(1);
            $admin->setBlocked(0);
        }
        $form = $this->container->get('form.factory')->create(new AdminType(), $admin);
        $form->handleRequest($request);

        // process the form on POST
        if ($request->isMethod('POST')) {
            if ($form->isValid()) {
                if (!$admin->getId() && $this->container->get('admin_service')->isExist($admin->getUsername())) {
                    $session = $request->getSession();
                    $session->getFlashBag()->add('unsuccess', 'Username have been used');
                } else {
                    if ($admin->getPassword()) {
                        $admin->setSalt(uniqid(mt_rand())); // Unique salt for user

                        // Set encrypted password
                        $encoder = $this->container->get('security.encoder_factory')->getEncoder($admin);
                        $password = $encoder->encodePassword($admin->getPassword(), $admin->getSalt());
                        $admin->setPassword($password);
                    }
                    $this->container->get('admin_service')->saveEntity($admin);

                    $session = $request->getSession();
                    $session->getFlashBag()->add('success', 'An admin have been saved!');

                    $exception = new BizException();
                    $exception->redirect = $this->container->get('router')->generate('gao_admin_account_list');

                    throw $exception;
                }
            }
        }

        return array(
            'form' => $form->createView()
        );
    }
}
