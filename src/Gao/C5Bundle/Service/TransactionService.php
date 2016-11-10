<?php

namespace Gao\C5Bundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

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
     * Get current pd by user
     * @param $user_id ID of user
     *
     * @return mixed The Pd data.
     */
    public function getCurrentPdByUser($user_id)
    {
        $query = "select pd_amount, applied_interest_rate, pin_id, pin_number, status, created from pd where user_id=:user_id AND status <> 2";
        $conn = $this->em->getConnection()->prepare($query);
        $param = array('user_id' => $user_id);
        $conn->execute($param);
        $result = $conn->fetchAll();

        return $result;
    }

    /**
     * Get list gd.
     * @param $user_id ID of user
     *
     * @return mixed The Pd data.
     */
    public function getCurrentGdByUser($user_id)
    {
        $query = "select pd_amount, applied_interest_rate, pin_id, pin_number, status, created from pd where user_id=:user_id AND status <> 2";
        $conn = $this->em->getConnection()->prepare($query);
        $param = array('user_id' => $user_id);
        $conn->execute($param);
        $result = $conn->fetchAll();
    
        return empty($result) ? null : $result[0];
    }
}
