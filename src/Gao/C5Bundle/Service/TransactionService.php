<?php

namespace Gao\C5Bundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Gao\C5Bundle\Biz\BizException;
use Gao\C5Bundle\Entity\Pin;
use Gao\C5Bundle\Entity\Pd;

/**
 * TransactionService class.
 *
 * Service for all pd , gd, pin, transaction query.
 */
class TransactionService
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

    /**
     * ========================
     * BLOCK FOR PD TRANSACTION
     * ========================
     */

    /**
     * Get current pd by user
     * @param $user_id ID of user
     *
     * @return mixed The Pd data.
     */
    public function getCurrentPdByUser($user_id)
    {
        $params = array('userId' => $user_id);
        try {
            $qr = $this->em->getRepository('GaoC5Bundle:Pd')->createQueryBuilder('e');
            $pin = $qr->where('e.userId = :userId')
            ->andWhere('e.status != 2')
            ->setParameters($params)
            ->getQuery()
            ->getSingleResult();

            return $pin;
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     * get transaction pd
     *
     * @param int $id The id of pd
     *
     * @return array $transactionList Is an array of Transaction
     */
    public function getTransactionByPd($id)
    {
        $params = array('pdId' => $id);
        try {
            $qr = $this->em->getRepository('GaoC5Bundle:Transaction')
            ->createQueryBuilder('e');

    
            $list = $qr->where('e.pdId = :pdId')
            ->setParameters($params)
            ->getQuery()
            ->getResult();
    
            return $list;
            //None record found exception
        } catch (\Doctrine\ORM\NoResultException $e) {
            throw new BizException('No record found...');
            //return false
        }
    }

    public function checkPinForPd($data) {
        // $em instanceof EntityManager
        $response = ['error' => '', 'message' => '', 'pd' => null];
        $this->em->getConnection()->beginTransaction(); // suspend auto-commit
        try {
            $pin = $this->em->getRepository('GaoC5Bundle:Pin')->findBy(array('pinNumber' => $data['pin_number'], 'used' => 0));
            if (empty($pin) || empty($pin[0])) {
                throw new BizException('Ma PIN khong ton tai. Vui long thu lai');
            } else {
                $pin = $pin[0];
            }

            // create new Pd
            $pd = new Pd;
            $pd->setUserId($data['user_id']);
            $pd->setPinId($pin->getId());
            $pd->setPinNumber($pin->getPinNumber());
            $pd->setAppliedInterestRate(35);
            $pd->setStatus($this->container->getParameter('pd_status')['waiting']);
            $this->em->persist($pd);
            $this->em->flush();

            //update pin
            $pin->setUserId($data['user_id']);
            $pin->setPdId($pd->getId());
            $pin->setUsed(1);
            $this->em->persist($pin);
            $this->em->flush();

            // update pd_gd_state of user
            $udpateUser = $this->em->getRepository('GaoC5Bundle:Users')->find($data['user_id']);
            $udpateUser->setPdGdState($this->container->getParameter('pd_gd_state')['PD_Requested']);
            $udpateUser->setFirstPdDone(1);
            $this->em->persist($udpateUser);
            $this->em->flush();

            $response['message'] = 'Ma PIN dung. Vui long cho de thuc hien tiep';
            $response['pd'] = $pd;

            //After all work commit it
            $this->em->getConnection()->commit();
        } catch (\Exception $ex) {
            //Rollback everything
            $this->em->getConnection()->rollBack();
            // CHeck error to notify user
            if ($ex instanceof BizException) {
                $response['error'] = $ex->getMessage();
            } else {
                throw $ex;
                $response['error'] = 'Co loi xay ra. Vui long lien he de duoc tu van';
            }
        }

        return $response;
    }

    /**
     * ========================
     * BLOCK FOR GD TRANSACTION
     * ========================
     */

    /**
     * Get current pd by user
     * @param $user_id ID of user
     *
     * @return mixed The Pd data.
     */
    public function getCurrentGdByUser($user_id)
    {
        $params = array('userId' => $user_id);
        try {
            $qr = $this->em->getRepository('GaoC5Bundle:Gd')->createQueryBuilder('e');
            $pin = $qr->where('e.userId = :userId')
            ->andWhere('e.status != 2')
            ->setParameters($params)
            ->getQuery()
            ->getSingleResult();
    
            return $pin;
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
    
    /**
     * get transaction pd
     *
     * @param int $id The id of pd
     *
     * @return array $transactionList Is an array of Transaction
     */
    public function getTransactionByGd($id)
    {
        $params = array('gdId' => $id);
        try {
            $qr = $this->em->getRepository('GaoC5Bundle:Transaction')
            ->createQueryBuilder('e');
    
    
            $list = $qr->where('e.gdId = :gdId')
            ->setParameters($params)
            ->getQuery()
            ->getResult();
    
            return $list;
            //None record found exception
        } catch (\Doctrine\ORM\NoResultException $e) {
            throw new BizException('No record found...');
            //return false
        }
    }
    
    public function checkPinForGd($data) {
        // $em instanceof EntityManager
        $response = ['error' => '', 'message' => '', 'pd' => null];
        $this->em->getConnection()->beginTransaction(); // suspend auto-commit
        try {
            $pin = $this->em->getRepository('GaoC5Bundle:Pin')->findBy(array('pinNumber' => $data['pin_number'], 'used' => 0));
            if (empty($pin) || empty($pin[0])) {
                throw new BizException('Ma PIN khong ton tai. Vui long thu lai');
            } else {
                $pin = $pin[0];
            }
    
            // create new Pd
            $pd = new Pd;
            $pd->setUserId($data['user_id']);
            $pd->setPinId($pin->getId());
            $pd->setPinNumber($pin->getPinNumber());
            $pd->setAppliedInterestRate(35);
            $pd->setStatus($this->container->getParameter('pd_status')['waiting']);
            $this->em->persist($pd);
            $this->em->flush();
    
            //update pin
            $pin->setUserId($data['user_id']);
            $pin->setPdId($pd->getId());
            $pin->setUsed(1);
            $this->em->persist($pin);
            $this->em->flush();
    
            // update pd_gd_state of user
            $udpateUser = $this->em->getRepository('GaoC5Bundle:Users')->find($data['user_id']);
            $udpateUser->setPdGdState($this->container->getParameter('pd_gd_state')['PD_Requested']);
            $udpateUser->setFirstPdDone(1);
            $this->em->persist($udpateUser);
            $this->em->flush();
    
            $response['message'] = 'Ma PIN dung. Vui long cho de thuc hien tiep';
            $response['pd'] = $pd;
    
            //After all work commit it
            $this->em->getConnection()->commit();
        } catch (\Exception $ex) {
            //Rollback everything
            $this->em->getConnection()->rollBack();
            // CHeck error to notify user
            if ($ex instanceof BizException) {
                $response['error'] = $ex->getMessage();
            } else {
                throw $ex;
                $response['error'] = 'Co loi xay ra. Vui long lien he de duoc tu van';
            }
        }
    
        return $response;
    }

}
