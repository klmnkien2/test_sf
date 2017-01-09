<?php

namespace Gao\C5Bundle\Biz;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;
use Gao\C5Bundle\Biz\BizException;

/**
 * Class: ReferUserBiz.
 */
class ReferUserBiz
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

    public function main($transaction_id)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (empty($transaction_id)) {
            throw new BizException('transaction id null.');
        }

        $params = $this->prepareData($user, $transaction_id);

        $post = Request::createFromGlobals();
        if ($post->request->has('submit')) {
            $data = [
                'transaction_id' => $transaction_id,
                'message' => $post->request->get('message'),
                'attachment' => $post->request->get('attachment')
            ];
            $this->formProcess($user, $data, $params);
        }

        $dispute = $params['dispute'];
        $attachment_array = $params['attachment_array'];
        return array(
            'message' => $dispute?$dispute->getMessage():null,
            'attachment_array' => $attachment_array,
            'id' => $dispute?$dispute->getId():null
        );
    }

    public function mainList($user_id, $page, $itemsLimitPerPage, $sort)
    {
        if (!$this->container->get('security_user_service')->isReferToLoginUser($user_id)) {
            throw new BizException("You don't have any reference to this user");
        }

        $list = $this->container->get('security_user_service')->getReferUser($user_id, $page, $itemsLimitPerPage, $sort);
        $total = $this->container->get('security_user_service')->countReferUser($user_id);
        $pagination = null;
        if ($total > 0) {
            // Get the paginator service from the container
            $paginator = $this->container->get('paging_paginator');

            // Set information for the paginator.
            $paginator
            ->setItemsInCurrentPage($list)
            ->setTotalNumberOfItems($total);

            $paginator->setItemsLimitPerPage($itemsLimitPerPage);
            // Execute pagination to get pagination information.
            $pagination = $paginator->paginate($page);
        }

        return array(
            'pagination' => $pagination,
            'sort' => $sort
        );
    }
}
