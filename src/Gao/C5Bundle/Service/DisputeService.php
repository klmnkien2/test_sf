<?php

/**
 * @category Service
 *
 * @author KienDV
 *
 * @version 1.0
 */

namespace Gao\C5Bundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Gao\C5Bundle\Entity\Dispute;
use Gao\C5Bundle\Biz\BizException;
use Gao\AdminBundle\Service\DataTableService;

/**
 * DisputeService class.
 *
 * Common Service.
 */
class DisputeService
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

    public function getById($id) {
        return $this->em->getRepository('GaoC5Bundle:Dispute')->find($id);
    }

    public function getByTransaction($id) {
        $result = $this->em->getRepository('GaoC5Bundle:Dispute')->findBy(array('transactionId' => $id));
        if (!empty($result) && is_array($result)) {
            return $result[0];
        } else {
            return null;
        }
    }

    public function updateDispute($dispute) {

        $this->em->persist($dispute);
        $this->em->flush();

    }

    /**
     * get transaction from user
     *
     * @param int $id The id of user
     *
     * @return array $transactionList Is an array of Transaction
     */
    public function getDisputeByUser($id, $page, $itemsLimitPerPage, $sort)
    {
        try {
            $query = <<<EOT
SELECT
    d.id, d.message, d.transaction_id, d.created, d.status, t.pd_user_id, t.gd_user_id
FROM
    dispute d
LEFT JOIN transaction t ON t.id = d.transaction_id
WHERE
    d.user_id = ?
LIMIT ?, ?
EOT;
            $stmt = $this->em->getConnection()->prepare($query);
            $stmt->bindValue(1, $id, \PDO::PARAM_INT);
            $stmt->bindValue(2, ($page-1) * $itemsLimitPerPage, \PDO::PARAM_INT);
            $stmt->bindValue(3, $itemsLimitPerPage, \PDO::PARAM_INT);
            $stmt->execute();

            $list = $stmt->fetchAll();

            return $list;
            //None record found exception
        } catch (\Exception $e) {
            //throw $e;
            throw new BizException('No record found...');
        }
    }
    
    /**
     * count transaction from user
     *
     * @param int $id The id of user
     *
     * @return array $transactionList Is an array of Transaction
     */
    public function countDisputeByUser($id)
    {
        $params = array('user_id' => $id);
        try {
            $query = <<<EOT
SELECT
    count(d.id) AS total
FROM
    dispute d
WHERE
    d.user_id = :user_id
EOT;
        $conn = $this->em->getConnection()->prepare($query);
        $conn->execute($params);
        $result = $conn->fetchAll();

            return $result[0]['total'];
        } catch (\Exception $e) {
            throw new BizException('can not count dispute ...');
            //return false
        }
    }



    /**
     * get transaction from user
     *
     * @param int     $accountId The id of admin user
     * @param string  $token     Token to do action
     *
     * @return Array results
     */
    public function getDataTable($accountId, $token)
    {
        $total_field = DataTableService::TOTAL_FIELD;
        $where_more = DataTableService::WHERE_MORE;

        $sql = <<<SQL
SELECT
    d.id,
    u.username,
    d.created,
    d.status
FROM 
    dispute d
LEFT JOIN users u ON u.id = d.user_id 
WHERE 
    $where_more
SQL;

        $count_sql = <<<SQL
SELECT 
    COUNT(id) AS $total_field
FROM 
    dispute 
WHERE 
    $where_more
SQL;

        // Array of database columns which should be read and sent back to DataTables.
        // The `db` parameter represents the column name in the database, while the `dt`
        // parameter represents the DataTables column identifier. In this case simple
        // indexes
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'username',  'dt' => 1 ),
            array(
                'db'        => 'created',
                'dt'        => 2,
                'formatter' => function( $d, $row ) {
                    return date( 'jS M y', strtotime($d));
                }
            ),
            array(
                'db'        => 'status',
                'dt'        => 3,
                'formatter' => function( $d, $row ) {
                    if ($d == 0) return '<span style="background-color:yellow">Waiting</span>';
                    else if ($d == 0) return '<span style="background-color:green">Approved</span>';
                    else if ($d == 0) return '<span style="background-color:red">Rejected</span>';
                    else return 'N/A';
                }
            ),
            array(
                'db'        => 'id',
                'dt'        => 4,
                'formatter' => function( $d, $row ) use ($token) {
                    return $this->actionFormatter($d, $token);
                }
            )
        );
        return DataTableService::getCustomData( $_GET, $this->em->getConnection(), $sql, $count_sql, $columns );
    }

    public function actionFormatter($id, $token)
    {
        $detaillink = $this->container->get('router')->generate('gao_admin.dispute.detail') . "?id=$id";
        $approvelink = $this->container->get('router')->generate('gao_admin.dispute.update_status') . "?id=$id&status=1&token=$token";
        $rejectlink = $this->container->get('router')->generate('gao_admin.dispute.update_status') . "?id=$id&status=2&token=$token";
        return "<a href='$detaillink' class='editlink'>[Detail]</a> " .
            "<a href='$approvelink' style='color:green'>[Approve]</a> " .
            "<a href='$rejectlink' class='deletelink'>[Reject]</a> ";
    }
}
