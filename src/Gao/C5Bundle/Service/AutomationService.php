<?php

namespace Gao\C5Bundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Gao\C5Bundle\Biz\BizException;
use Gao\C5Bundle\Entity\Pin;
use Gao\C5Bundle\Entity\Users;
use Gao\C5Bundle\Entity\Pd;
use Gao\C5Bundle\Entity\Gd;
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

    public function getMoreBotGd($wantedNumber, $wantedAmount)
    {
        $created_gd = array();
        $this->em->getConnection()->beginTransaction(); // suspend auto-commit
        try {
            $params = array();
            $params['systemUsers'] = $this->container->getParameter('system_bot_users');
            $params['pd_gd_state'] = $this->container->getParameter('pd_gd_state')['GD_Matched'];
    
            $qr = $this->em->getRepository('GaoC5Bundle:Users')->createQueryBuilder('e');
            $bot_users = $qr->where('e.pdGdState is null or e.pdGdState != :pd_gd_state')
            ->andWhere(
                $qr->expr()->andX(
                    $qr->expr()->in('e.username', ':systemUsers')
                )
            )
            ->setParameters($params)
            ->setMaxResults($wantedNumber)
            ->getQuery()
            ->getResult();

            $gdAmount = $wantedAmount / count($bot_users);

            foreach ($bot_users as $gd_user) {
                $gd = new Gd;
                $gd->setUserId($gd_user->getId());
                $gd->setPinId(1);
                $gd->setPinNumber('bot');

                //Update refer information
                $gd->setPdId(0);
                $gd->setPdAmount(0);
                $gd->setRefAmount(0);
                $gd->setGdAmount($gdAmount);
                $gd->setStatus($this->container->getParameter('gd_status')['waiting']);
                $this->em->persist($gd);

                $gd_user->setPdGdState($this->container->getParameter('pd_gd_state')['GD_Matched']);
                $gd_user->setOutstandingGd($gd->getId());
                $this->em->persist($gd_user);
                $created_gd[] = $gd;
            }
            //After all work commit it
            $this->em->flush();
            $this->em->getConnection()->commit();
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
            //Rollback everything
            $this->em->getConnection()->rollBack();
            $created_gd = null;
        }

        return $created_gd;
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
        $gd = $this->em->getRepository('GaoC5Bundle:Gd')->find($gd_id);
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

        $user_pd->setPdGdState($this->container->getParameter('pd_gd_state')['PD_Matched']);
        $this->em->persist($user_pd);
        $this->em->flush();

        $gd->setStatus($this->container->getParameter('gd_status')['receiving']);
        $this->em->persist($gd);
        $this->em->flush();

        $user_gd->setPdGdState($this->container->getParameter('pd_gd_state')['GD_Matched']);
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

    /**
     * FOR TESTING PURPOSE ONLY
     */
    // assume that all people done right thing
    public function testFinishRound()
    {
        $this->em->getConnection()->beginTransaction(); // suspend auto-commit
        try {
            // Finish all PD that sending, ban user
            $pd_user_list = $this->em->getRepository('GaoC5Bundle:Users')->findBy(array('blocked' => 0, 'pdGdState' => 'PD_Matched'));
            foreach ($pd_user_list as $pd_user) {
                $pd_user->setPdGdState('PD_Done');
                $pd_user->setFirstPdDone(1);
                $this->em->persist($pd_user);
                $this->em->flush();

                $current_pd = $this->container->get('transaction_service')->getCurrentPdByUser($pd_user->getId());
                if (!empty($current_pd)) {
                    $q = $this->em->createQuery('update GaoC5Bundle:Transaction t set t.status = 1, t.approvedDate = :today WHERE t.pdId = :pdId');
                    $q->execute(['today' => new \DateTime(), 'pdId' => $current_pd->getId()]);

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

    public function resetUser()
    {
        $q = $this->em->createQuery('update GaoC5Bundle:Users u set u.blocked = 0, u.pdGdState = NULL, u.firstPdDone = NULL, u.outstandingPd = NULL, u.outstandingGd = NULL');
        $numUpdated = $q->execute();
        echo "User updated: ", $numUpdated, PHP_EOL;
    }

    public function forceRequest() {
        $this->em->getConnection()->beginTransaction(); // suspend auto-commit
        try {
            $systemUsers = $this->container->getParameter('system_bot_users');
            // Finish all PD that sending, ban user
            $pd_user_list1 = $this->em->getRepository('GaoC5Bundle:Users')->findBy(array('blocked' => 0, 'pdGdState' => 'GD_Done'));
            $pd_user_list2 = $this->em->getRepository('GaoC5Bundle:Users')->findBy(array('blocked' => 0, 'pdGdState' => null));
            $pd_user_list = array_merge($pd_user_list1, $pd_user_list2);
            foreach ($pd_user_list as $pd_user) {
                if (in_array($pd_user->getUsername(), $systemUsers)) {
                    continue;
                }
                $pd = new Pd;
                $pd->setUserId($pd_user->getId());
                $pd->setPinId(1);
                $pd->setPinNumber('test');
                $pd->setAppliedInterestRate($pd_user->getCurrentInterestRate());
                $pd->setPdAmount($this->container->getParameter('default_pd_amount'));
                $pd->setStatus($this->container->getParameter('pd_status')['waiting']);
                $this->em->persist($pd);
                $this->em->flush();

                $pd_user->setPdGdState('PD_Requested');
                $pd_user->setOutstandingPd($pd->getId());
                $this->em->persist($pd_user);
                $this->em->flush();
            }

            // Finish all GD that receiving force user to PD
            $gd_user_list = $this->em->getRepository('GaoC5Bundle:Users')->findBy(array('blocked' => 0, 'pdGdState' => 'PD_Done'));
            foreach ($gd_user_list as $gd_user) {
                if (in_array($gd_user->getUsername(), $systemUsers)) {
                    continue;
                }
                $pd = $this->em->getRepository('GaoC5Bundle:Pd')->find($gd_user->getOutstandingPd());

                $gd = new Gd;
                $gd->setUserId($gd_user->getId());
                $gd->setPinId(1);
                $gd->setPinNumber('test');

                //Update refer information
                $gd->setPdId($pd->getId());
                $gd->setPdAmount($pd->getPdAmount());
                $refAmount = $user->getOutstandingRefAmount()?:0;
                $gd->setRefAmount($refAmount);
                $gd->setGdAmount($pd->getPdAmount() * (100 + $pd->getAppliedInterestRate()) / 100 + $refAmount);

                $gd->setStatus($this->container->getParameter('gd_status')['waiting']);
                $this->em->persist($gd);
                $this->em->flush();

                $gd_user->setPdGdState('GD_Requested');
                $gd_user->setOutstandingGd($gd->getId());
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
