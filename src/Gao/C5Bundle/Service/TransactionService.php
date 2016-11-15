<?php

namespace Gao\C5Bundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Gao\C5Bundle\Biz\BizException;
use Gao\C5Bundle\Entity\Pin;
use Gao\C5Bundle\Entity\Pd;
use Gao\C5Bundle\Entity\Gd;

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
        $params = array('pd_id' => $id);
        try {
            $query = "SELECT t.id, t.amount, t.status, t.gd_acc_number, approved_date, created, gd_id, gd_user_id, u.username, u.full_name
                    FROM transaction t
                    LEFT JOIN users u ON u.id = t.gd_user_id
                    WHERE t.pd_id = :pd_id;";
            $conn = $this->em->getConnection()->prepare($query);
            $conn->execute($params);
            $list = $conn->fetchAll();

            return $list;
            //None record found exception
        } catch (\Exception $e) {
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
     * get transaction gd
     *
     * @param int $id The id of gd
     *
     * @return array $transactionList Is an array of Transaction
     */
    public function getTransactionByGd($id)
    {
        $params = array('gd_id' => $id);
        try {
            $query = "SELECT t.id, t.amount, t.status, t.pd_acc_number, approved_date, created, pd_id, pd_user_id, u.username, u.full_name
                    FROM transaction t
                    LEFT JOIN users u ON u.id = t.pd_user_id
                    WHERE t.gd_id = :gd_id;";
            $conn = $this->em->getConnection()->prepare($query);
            $conn->execute($params);
            $list = $conn->fetchAll();

            return $list;
            //None record found exception
        } catch (\Exception $e) {
            throw new BizException('No record found...');
            //return false
        }
    }

    public function checkPinForGd($data) {
        // $em instanceof EntityManager
        $response = ['error' => '', 'message' => '', 'gd' => null];
        $this->em->getConnection()->beginTransaction(); // suspend auto-commit
        try {
            $pin = $this->em->getRepository('GaoC5Bundle:Pin')->findBy(array('pinNumber' => $data['pin_number'], 'used' => 0));
            if (empty($pin) || empty($pin[0])) {
                throw new BizException('Ma PIN khong ton tai. Vui long thu lai');
            } else {
                $pin = $pin[0];
            }

            // create new Pd
            $gd = new Gd;
            $gd->setUserId($data['user_id']);
            $gd->setPinId($pin->getId());
            $gd->setPinNumber($pin->getPinNumber());

            //May be need set PD, ref infomation
            $gd->setPdId(-1); // for test only
            $gd->setPdAmount(5000); // pd_amount
            $gd->setRefAmount(0); // ref_amount

            $gd->setStatus($this->container->getParameter('gd_status')['waiting']);
            $this->em->persist($gd);
            $this->em->flush();

            //update pin
            $pin->setUserId($data['user_id']);
            $pin->setGdId($gd->getId());
            $pin->setUsed(1);
            $this->em->persist($pin);
            $this->em->flush();

            // update pd_gd_state of user
            $udpateUser = $this->em->getRepository('GaoC5Bundle:Users')->find($data['user_id']);
            $udpateUser->setPdGdState($this->container->getParameter('pd_gd_state')['GD_Requested']);
            $this->em->persist($udpateUser);
            $this->em->flush();

            $response['message'] = 'Ma PIN dung. Vui long cho de thuc hien tiep';
            $response['gd'] = $gd;

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
     * get transaction from user
     *
     * @param int $id The id of user
     *
     * @return array $transactionList Is an array of Transaction
     */
    public function getTransactionByUser($id, $page, $itemsLimitPerPage, $sort)
    {
        try {
            $query = <<<EOT
SELECT 
    t.id, t.amount, t.status, t.approved_date, t.created,
    t.pd_id, t.pd_user_id, t.pd_acc_number,
    NULL AS gd_id, NULL AS gd_user_id, NULL AS gd_acc_number,
    u.username, u.full_name
FROM
    transaction t
LEFT JOIN
    users u ON u.id = t.pd_user_id
WHERE 
    t.gd_user_id = ?
UNION
SELECT 
    t.id, t.amount, t.status, t.approved_date, t.created,
    NULL AS pd_id, NULL AS pd_user_id, NULL AS pd_acc_number,
    t.gd_id, t.gd_user_id, t.gd_acc_number,
    u.username, u.full_name
FROM 
    transaction t
LEFT JOIN 
    users u ON u.id = t.gd_user_id
WHERE 
    t.pd_user_id = ?
LIMIT ?, ?
EOT;
            $stmt = $this->em->getConnection()->prepare($query);
            $stmt->bindValue(1, $id, \PDO::PARAM_INT);
            $stmt->bindValue(2, $id, \PDO::PARAM_INT);
            $stmt->bindValue(3, ($page-1) * $itemsLimitPerPage, \PDO::PARAM_INT);
            $stmt->bindValue(4, $itemsLimitPerPage, \PDO::PARAM_INT);
            $stmt->execute();

            $list = $stmt->fetchAll();

            return $list;
            //None record found exception
        } catch (\Exception $e) {
            throw $e;//new BizException('No record found...');
            //return false
        }
    }

    /**
     * count transaction from user
     *
     * @param int $id The id of user
     *
     * @return array $transactionList Is an array of Transaction
     */
    public function countTransactionByUser($id)
    {
        $params = array('user_id' => $id);
        try {
            $query = <<<EOT
SELECT
    count(t.id) AS total
FROM
    transaction t
WHERE
    t.gd_user_id = :user_id OR t.pd_user_id = :user_id
EOT;
            $conn = $this->em->getConnection()->prepare($query);
            $conn->execute($params);
            $result = $conn->fetchAll();

            return $result[0]['total'];
            //None record found exception
        } catch (\Exception $e) {
            throw new BizException('can not count transaction ...');
            //return false
        }
    }
}
