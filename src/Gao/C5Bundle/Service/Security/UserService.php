<?php

/**
 * @category Service
 *
 * @author KienDV
 *
 * @version 1.0
 */

namespace Gao\C5Bundle\Service\Security;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Gao\C5Bundle\Entity\Users;

/**
 * DisputeService class.
 *
 * Common Service.
 */
class UserService
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

    public function updateLastLogin(Users $user) {

        $user->setLastLogin($user->getCurrentLogin());
        $user->setCurrentLogin(new \DateTime("now"));
        $this->em->persist($user);
        $this->em->flush();

    }

    public function getEntity($id)
    {
        return $this->em->getRepository('GaoC5Bundle:Users')->find($id);
    }

    public function updateUser(Users $user) {
        $this->em->persist($user);
        $this->em->flush();
    }
}
