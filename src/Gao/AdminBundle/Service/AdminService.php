<?php

/**
 * @category Service
 *
 * @author KienDV
 *
 * @version 1.0
 */

namespace Gao\AdminBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Gao\AdminBundle\Entity\Admin;

/**
 * Admin Service.
 */
class AdminService
{
    /**
     * EntityManager.
     */
    protected $em;

    /**
     * Container Interface.
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param EntityManager $em        The EntityManager.
     * @param Container     $container The Container Interface.
     */
    public function __construct(EntityManager $em, Container $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function updateLastLogin(Admin $admin) {

        $admin->setLastLogin($admin->getCurrentLogin());
        $admin->setCurrentLogin(new \DateTime("now"));
        $this->em->persist($admin);
        $this->em->flush();

    }

    public function getEntity($id)
    {
        return $this->em->getRepository('GaoAdminBundle:Admin')->find($id);
    }

    public function updateUser(Admin $admin) {
        $this->em->persist($admin);
        $this->em->flush();
    }
}
