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

    public function userFinishPd($user, $pd) {
        $user->setPdGdState($this->container->getParameter('pd_gd_state')['PD_Done']);
        $user->setLastStateUpdate(new \DateTime("now"));
        $pdCount = $user->getPdCount();
        if (empty($pdCount)) {
            $pdCount = 0;
        }
        $user->setPdCount($pdCount + 1);
        $pdTotal = $user->getPdTotal();
        if (empty($pdTotal)) {
            $pdTotal = 0;
        }
        $user->setPdTotal($pdTotal + $pd->getPdAmount());
        if(empty($user->getFirstPdDone())) {
            $user->setFirstPdDone($pd->getPdAmount());
        }

        $pd->setStatus($this->container->getParameter('pd_status')['done']);

        $this->em->persist($user);
        $this->em->flush();
        $this->em->persist($pd);
        $this->em->flush();
    }

    public function userFinishGd($user, $gd) {
        $user->setPdGdState($this->container->getParameter('pd_gd_state')['GD_Done']);
        $user->setLastStateUpdate(new \DateTime("now"));
        $gdCount = $user->getGdCount();
        if (empty($gdCount)) {
            $gdCount = 0;
        }
        $user->setGdCount($gdCount + 1);
        $gdTotal = $user->getGdTotal();
        if (empty($gdTotal)) {
            $gdTotal = 0;
        }
        $user->setGdTotal($gdTotal + $gd->getGdAmount());
        $user->setOutstandingPd(null);
        $user->setOutstandingGd(null);
        $user->setOutstandingRefAmount(0);

        $gd->setStatus($this->container->getParameter('gd_status')['done']);

        $this->em->persist($user);
        $this->em->flush();
        $this->em->persist($gd);
        $this->em->flush();
    }

}
