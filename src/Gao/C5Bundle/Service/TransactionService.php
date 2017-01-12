<?php

namespace Gao\C5Bundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Gao\C5Bundle\Biz\BizException;
use Gao\C5Bundle\Entity\Pin;
use Gao\C5Bundle\Entity\Pd;
use Gao\C5Bundle\Entity\Gd;
use Gao\C5Bundle\Entity\Transaction;
use Gao\AdminBundle\Service\DataTableService;

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

    public function checkPinForPd($data)
    {
        $response = ['error' => '', 'message' => '', 'pd' => null];
        $this->em->getConnection()->beginTransaction(); // suspend auto-commit
        try {
            $pin = $this->em->getRepository('GaoC5Bundle:Pin')->findBy(array('pinNumber' => $data['pin_number'], 'used' => 0));
            if (empty($pin) || empty($pin[0])) {
                throw new BizException('Ma PIN khong ton tai. Vui long thu lai');
            } else {
                $pin = $pin[0];
            }

            $user = $this->em->getRepository('GaoC5Bundle:Users')->find($data['user_id']);
            if (empty($user)) {
                throw new BizException('User Account khong ton tai. Vui long thu lai');
            }

            // create new Pd
            $pd = new Pd;
            $pd->setUserId($data['user_id']);
            $pd->setPinId($pin->getId());
            $pd->setPinNumber($pin->getPinNumber());
            $pd->setAppliedInterestRate($user->getCurrentInterestRate());
            $pd->setPdAmount($this->container->getParameter('default_pd_amount'));
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
            $user->setPdGdState($this->container->getParameter('pd_gd_state')['PD_Requested']);
            $user->setOutstandingPd($pd->getId());

            $this->em->persist($user);
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

            $user = $this->em->getRepository('GaoC5Bundle:Users')->find($data['user_id']);
            if (empty($user)) {
                throw new BizException('User Account khong ton tai. Vui long thu lai');
            }

            $pd = $this->em->getRepository('GaoC5Bundle:Pd')->find($user->getOutstandingPd());
            if (empty($pd)) {
                throw BizException('Khong tim thay thong tin PD truoc do. Vui long thu lai');
            }

            // create new Gd
            $gd = new Gd;
            $gd->setUserId($data['user_id']);
            $gd->setPinId($pin->getId());
            $gd->setPinNumber($pin->getPinNumber());

            //Update refer information
            $gd->setPdId($pd->getId());
            $gd->setPdAmount($pd->getPdAmount());
            $refAmount = $user->getOutstandingRefAmount()?:0;
            $gd->setRefAmount($refAmount);
            $gd->setGdAmount($pd->getPdAmount() * (100 + $pd->getAppliedInterestRate()) / 100 + $refAmount);

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
            $user->setPdGdState($this->container->getParameter('pd_gd_state')['GD_Requested']);
            $user->setOutstandingGd($gd->getId());
            $this->em->persist($user);
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
     * ===================================
     * BLOCK FOR TRANSACTION OTHER ACTIONS
     * ===================================
     */

    /**
     * get transaction from user
     *
     * @param int $id The id of user
     *
     * @return array $transactionList Is an array of Transaction
     */
    public function getTransactionByUser($id, $condition, $page, $itemsLimitPerPage, $sort)
    {
        try {
            $query_condition = "";
            if (!empty($condition['pd_or_gd'])) {
                if ($condition['pd_or_gd'] == 'pd')
                    $query_condition .= " AND t.pd_user_id = :user_id";
                else if ($condition['pd_or_gd'] == 'gd')
                    $query_condition .= " AND t.gd_user_id = :user_id";
            }
            if ($condition['tran_status'] != "") {
                $query_condition .= " AND t.status = :tran_status";
            }

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
    {$query_condition}
WHERE 
    t.gd_user_id = :user_id
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
    t.pd_user_id = :user_id
    {$query_condition}
LIMIT :start_item, :item_per_page
EOT;

            $stmt = $this->em->getConnection()->prepare($query);
            $stmt->bindValue('user_id', $id, \PDO::PARAM_INT);
            $stmt->bindValue('start_item', ($page-1) * $itemsLimitPerPage, \PDO::PARAM_INT);
            $stmt->bindValue('item_per_page', $itemsLimitPerPage, \PDO::PARAM_INT);
            if ($condition['tran_status'] != "") {
                $stmt->bindValue('tran_status', $condition['tran_status'], \PDO::PARAM_INT);
            }
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
    public function countTransactionByUser($id, $condition)
    {
        $params = array('user_id' => $id);
        $query_condition = "";
        if (!empty($condition['pd_or_gd'])) {
            if ($condition['pd_or_gd'] == 'pd')
                $query_condition .= " AND t.pd_user_id = :user_id";
            else if ($condition['pd_or_gd'] == 'gd')
                $query_condition .= " AND t.gd_user_id = :user_id";
        }
        if ($condition['tran_status'] != "") {
            $params['tran_status'] = $condition['tran_status'];
            $query_condition .= " AND t.status = :tran_status";
        }

        try {
            $query = <<<EOT
SELECT
    count(t.id) AS total
FROM
    transaction t
WHERE
    (t.gd_user_id = :user_id OR t.pd_user_id = :user_id)
    {$query_condition}
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

    public function getEntity($id)
    {
        return $this->em->getRepository('GaoC5Bundle:Transaction')->find($id);
    }

    public function getEntityPd($id)
    {
        return $this->em->getRepository('GaoC5Bundle:Pd')->find($id);
    }

    public function getEntityGd($id)
    {
        return $this->em->getRepository('GaoC5Bundle:Gd')->find($id);
    }

    public function updateEntity($entity) {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function beginTransaction() {
        $this->em->getConnection()->beginTransaction();
    }

    public function rollbackTransaction() {
        $this->em->getConnection()->rollBack();
    }

    public function commitTransaction() {
        $this->em->getConnection()->commit();
    }

    /**
     * Check if an PD can be finished or not, if yes, update user pd_gd_state
     * 
     * @param int $pdId The id of Pd
     *
     * @return bool $finished Aready finish or not
     */
    public function checkPdFinish($pdId) {
        $finished = true;
        $transactionList = $this->getTransactionByPd($pdId);
        foreach ($transactionList as $transtaction) {
            if ($transtaction['status'] != 1) {
                $finished = false;
                break;
            }
        }

        return $finished;
    }

    /**
     * Check if an GD can be finished or not, if yes, update user pd_gd_state
     * 
     * @param int $gdId The id of Pd
     *
     * @return bool $finished Aready finish or not
     */
    public function checkGdFinish($gdId) {
        $finished = true;
        $transactionList = $this->getTransactionByGd($gdId);
        foreach ($transactionList as $transtaction) {
            if ($transtaction['status'] != 1) {
                $finished = false;
                break;
            }
        }
        
        return $finished;
    }

    public function getBankLogTable()
    {
        $total_field = DataTableService::TOTAL_FIELD;
        $where_more = DataTableService::WHERE_MORE;

        $sql = <<<SQL
SELECT 
    vcb_acc_number,
    count(id) as number_of_users,
    sum(pd_count) as pd_count,
    sum(pd_total) as pd_total,
    sum(gd_count) as gd_count,
    sum(gd_total) as gd_total
FROM 
    users 
WHERE 
    vcb_acc_number is not null AND $where_more
GROUP BY vcb_acc_number
SQL;

        $count_sql = <<<SQL
SELECT 
    COUNT(DISTINCT vcb_acc_number) AS $total_field
FROM 
    users 
WHERE 
    vcb_acc_number is not null AND $where_more
SQL;

        // Array of database columns which should be read and sent back to DataTables.
        // The `db` parameter represents the column name in the database, while the `dt`
        // parameter represents the DataTables column identifier. In this case simple
        // indexes
        $columns = array(
            array( 'db' => 'vcb_acc_number',  'dt' => 0 ),
            array( 'db' => 'number_of_users', 'dt' => 1, ),
            array( 'db' => 'pd_count',        'dt' => 2, ),
            array( 'db' => 'pd_total',        'dt' => 3, ),
            array( 'db' => 'gd_count',        'dt' => 4, ),
            array( 'db' => 'gd_total',        'dt' => 5, ),
            array( 
                'db' => 'vcb_acc_number',
                'dt' => 6,
                'formatter' => function( $d, $row ) {
                    return 'View users';
                }
            )
        );
        return DataTableService::getCustomData( $_GET, $this->em->getConnection(), $sql, $count_sql, $columns );
    }
}
