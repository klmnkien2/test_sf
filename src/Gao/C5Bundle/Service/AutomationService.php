<?php

namespace Gao\C5Bundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Gao\C5Bundle\Biz\BizException;
use Gao\C5Bundle\Entity\Pin;
use Gao\C5Bundle\Entity\Users;
use Gao\C5Bundle\Entity\Transaction;

/**
 * AutomationService class.
 *
 * Service for auto create pin, auto matched tran for pd, gd.
 */
class AutomationService
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

    public function createPin() {
        $pin = new Pin();
        $pin->setPinNumber($this->genPinNumber());
        $pin->setUsed(0);

        $this->em->persist($pin);
        $this->em->flush();

        var_dump($pin);
    }

    private function genPinNumber() {
        $hash = hash_init('sha1');
        hash_update($hash, time());
        return substr(hash_final($hash), 0, 10);
    }

    public function createUserForTest($username, $rawPassword) {
        $user = new Users();
        $user->setUsername($username);
        $user->setSalt(uniqid(mt_rand())); // Unique salt for user

        // Set encrypted password
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $password = $encoder->encodePassword($rawPassword, $user->getSalt());
        $user->setPassword($password);

        $user->setCreatorId(1);
        $user->setEmail("$username@c5.com");
        $user->setCLevel(1);
        $user->setEmailVerified(1);
        $user->setBlocked(0);

        $this->em->persist($user);
        $this->em->flush();

        var_dump($user);
    }

    public function createTransaction($pd_id, $pd_user_id, $pd_acc_number, $gd_id, $gd_user_id, $gd_acc_number, $amount) {
        $tran = new Transaction;
        $tran->setPdId($pd_id);
        $tran->setPdUserId($pd_user_id);
        $tran->setPdAccNumber($pd_acc_number);
        $tran->setGdId($gd_id);
        $tran->setGdUserId($gd_user_id);
        $tran->setGdAccNumber($gd_acc_number);
        $tran->setAmount($amount);
        $tran->setStatus(0);

        $this->em->persist($tran);
        $this->em->flush();

        // Update trang thai user cho 2 thang pd va gd
    }
}
