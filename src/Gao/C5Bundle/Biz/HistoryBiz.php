<?php

namespace Gao\C5Bundle\Biz;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;
use Gao\C5Bundle\Biz\BizException;

/**
 * Class: HistoryBiz.
 */
class HistoryBiz
{
    /**
     * Service Container Interface.
     */
    private $container;

    /**
     * __construct.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * get data & processing data for building business.
     *
     * @param mixed  $id          Id of building
     * @param mixed  $page        Page number
     * @param bool   $is_mobile   Mobile or not mobile
     * @param string $referer_url Referer url
     * @param string $userAgent   User agent send from client.
     *
     * @return mixed
     */
    public function main($usr, $page, $itemsLimitPerPage, $sort)
    {
        try {
            $transactionList = $this->container->get('transaction_service')->getTransactionByUser($usr->getId(), $page, $itemsLimitPerPage, $sort);
            $transactionTotal = $this->container->get('transaction_service')->countTransactionByUser($usr->getId());
            // Get the paginator service from the container
            $paginator = $this->container->get('paging_paginator');

            // Set information for the paginator.
            $paginator
            ->setItemsInCurrentPage($transactionList)
            ->setTotalNumberOfItems($transactionTotal);

            $paginator->setItemsLimitPerPage($itemsLimitPerPage);
            // Execute pagination to get pagination information.
            $pagination = $paginator->paginate($page);

            return array(
                'pagination' => $pagination,
                'sort' => $sort
            );
        } catch (\Exception $ex) {
            throw new BizException($ex->getMessage());
        }
    }
}
