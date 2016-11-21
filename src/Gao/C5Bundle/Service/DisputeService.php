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
        $this->em->getRepository('GaoC5Bundle:Dispute')->find($id);
    }

    public function createDispute($userId, $pdId, $gdId, $message) {

        $dispute = new Dispute();
        $dispute->setUserId($userId);
        $dispute->setMessage($message);
        $dispute->setStatus(0);
        if (!empty($pdId)) {
            $dispute->setPdId($pdId);
        }
        if (!empty($gdId)) {
            $dispute->setGdId($gdId);
        }

        $this->em->persist($dispute);
        $this->em->flush();

        return $dispute;
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
    d.id, d.message, d.pd_id, d.gd_id, d.created, d.status 
FROM
    dispute d
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
    public function countDisputeByUser($id)
    {
        $params = array('user_id' => $id);
        try {
            $query = <<<EOT
SELECT
    count(t.id) AS total
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
}
