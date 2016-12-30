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

    public function beginTransaction()
    {
        $this->em->getConnection()->beginTransaction();
    }

    public function rollbackTransaction()
    {
        $this->em->getConnection()->rollBack();
    }

    public function commitTransaction()
    {
        $this->em->getConnection()->commit();
    }

    public function createPin() {
        $pin = new Pin();
        $pin->setPinNumber($this->genPinNumber());
        $pin->setUsed(0);

        $this->em->persist($pin);
        $this->em->flush();
    }

    private function genPinNumber()
    {
        $hash = hash_init('sha1');
        hash_update($hash, time() + uniqid(mt_rand()));
        return substr(hash_final($hash), 0, 10);
    }

    public function createUserForTest($username, $rawPassword)
    {
        $user = new Users();
        $user->setUsername($username);
        $user->setSalt(uniqid(mt_rand())); // Unique salt for user

        // Set encrypted password
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $password = $encoder->encodePassword($rawPassword, $user->getSalt());
        $user->setPassword($password);

        $user->setCreatorId(1);
        $user->setEmail("$username@c5.com");
        $user->setCLevel(10);
        $user->setEmailVerified(1);
        $user->setBlocked(0);

        $this->em->persist($user);
        $this->em->flush();

        var_dump($user);
    }

    public function createTransactionFromPdGd($pd_id, $gd_id, $amount)
    {
        $pd = $this->em->getRepository('GaoC5Bundle:Pd')->find($pd_id);
        if (empty($pd)) {
            throw new BizException("PD empty.");
        }
        $user_pd = $this->em->getRepository('GaoC5Bundle:Users')->find($pd->getUserId());
        if (empty($user_pd)) {
            throw new BizException("USER PD empty.");
        }
        $gd = $this->em->getRepository('GaoC5Bundle:Pd')->find($pd_id);
        if (empty($gd)) {
            throw new BizException("GD empty.");
        }
        $user_gd = $this->em->getRepository('GaoC5Bundle:Users')->find($gd->getUserId());
        if (empty($user_gd)) {
            throw new BizException("USER GD empty.");
        }

        $tran = new Transaction;
        $tran->setPdId($pd->getId());
        $tran->setPdUserId($pd->getUserId());
        $tran->setGdId($gd->getId());
        $tran->setGdUserId($gd->getUserId());
        $tran->setAmount($amount);
        $tran->setStatus(0);

        $this->em->persist($tran);
        $this->em->flush();

        // Update trang thai user cho 2 thang pd va gd
        $pd->setStatus($this->container->getParameter('pd_status')['sending']);
        $this->em->persist($pd);
        $this->em->flush();

        $user_pd->setPdGdState($this->container->getParameter('pd_gd_state')['PD_Requested']);
        $this->em->persist($user_pd);
        $this->em->flush();

        $gd->setStatus($this->container->getParameter('gd_status')['sending']);
        $this->em->persist($gd);
        $this->em->flush();

        $user_gd->setPdGdState($this->container->getParameter('pd_gd_state')['GD_Requested']);
        $this->em->persist($user_gd);
        $this->em->flush();
    }

    public function getAllWaitPd()
    {
        return $this->em->getRepository('GaoC5Bundle:Pd')->findBy(array('status' => 0));
    }

    public function getAllWaitGd()
    {
        return $this->em->getRepository('GaoC5Bundle:Gd')->findBy(array('status' => 0));
    }

    public function finishRound()
    {
        $this->em->getConnection()->beginTransaction(); // suspend auto-commit
        try {
            // Finish all PD that sending, ban user
            $pd_user_list = $this->em->getRepository('GaoC5Bundle:Users')->findBy(array('blocked' => 0, 'pdGdState' => 'PD_Matched'));
            foreach ($pd_user_list as $pd_user) {
                $pd_user->setBlocked(1);
                $this->em->persist($pd_user);
                $this->em->flush();

                $current_pd = $this->container->get('transaction_service')->getCurrentPdByUser($pd_user->getId());
                if (!empty($current_pd)) {
                    $current_pd->setStatus(2);//DONE
                    $this->em->persist($current_pd);
                    $this->em->flush();
                }
            }

            // Finish all GD that receiving force user to PD
            $gd_user_list = $this->em->getRepository('GaoC5Bundle:Users')->findBy(array('blocked' => 0, 'pdGdState' => 'GD_Matched'));
            foreach ($gd_user_list as $gd_user) {
                $current_gd = $this->container->get('transaction_service')->getCurrentGdByUser($gd_user->getId());
                if (!empty($current_gd)) {
                    $current_gd->setStatus(2);//DONE
                    $this->em->persist($current_gd);
                    $this->em->flush();
                }

                $gd_user->setPdGdState('GD_Done');
                $gd_user->setOutstandingPd(null);
                $gd_user->setOutstandingGd(null);
                $this->em->persist($gd_user);
                $this->em->flush();
            }

            //After all work commit it
            $this->em->getConnection()->commit();
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
            //Rollback everything
            $this->em->getConnection()->rollBack();
        }
    }
}
