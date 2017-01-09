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

    /**
     * check if user refer to logined user or not
     * 
     * @param $user_id Id of refer user
     * 
     * @return boolean
     */
    public function isReferToLoginUser($user_id)
    {
        return true;
    }

    /**
     * get refer user
     *
     * @param int $id The id of user
     *
     * @return array $userList Is an array of Transaction
     */
    public function getReferUser($id, $page, $itemsLimitPerPage, $sort)
    {
        try {
            $query = <<<EOT
SELECT
    u.id, u.username, u.full_name, u.phone, u.c_level, u.pd_total
FROM
    users u
WHERE
    u.ref_id = :ref_id
LIMIT 
    :start_record, :limit_record
EOT;
            $stmt = $this->em->getConnection()->prepare($query);
            $stmt->bindValue('ref_id', $id, \PDO::PARAM_INT);
            $stmt->bindValue('start_record', ($page-1) * $itemsLimitPerPage, \PDO::PARAM_INT);
            $stmt->bindValue('limit_record', $itemsLimitPerPage, \PDO::PARAM_INT);
            $stmt->execute();
    
            $list = $stmt->fetchAll();
    
            return $list;
            //None record found exception
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    /**
     * count refer user
     *
     * @param int $id The id of user
     *
     * @return int $total
     */
    public function countReferUser($id)
    {
        $params = array('ref_id' => $id);
        try {
            $query = <<<EOT
SELECT
    count(u.id) AS total
FROM
    users u
WHERE
    u.ref_id = :ref_id
EOT;
            $conn = $this->em->getConnection()->prepare($query);
            $conn->execute($params);
            $result = $conn->fetchAll();

            return $result[0]['total'];
        } catch (\Exception $e) {
            throw $e;
        }
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

        $this->triggerInterestDirect($user, $pd->getPdAmount());
        $this->triggerInterestMediate();
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

    private function triggerInterestDirect($user, $amount)
    {
        $refer_id = $user->getRefId();
        if (empty($refer_id)) {
            return;
        }
        $ref_user = $this->getEntity($refer_id);
        $refAmount = $ref_user->getOutstandingRefAmount()?:0;
        $ref_user->setOutstandingRefAmount($refAmount + $amount);
        $this->em->persist($ref_user);
        $this->em->flush();

        
    }

    private function triggerInterestMediate()
    {
    
    }
}
