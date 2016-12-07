<?php

namespace Gao\C5Bundle\Biz;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;
use Gao\C5Bundle\Biz\BizException;
use Gao\C5Bundle\Entity\Dispute;
use Gao\C5Bundle\Form\DisputeType;
use Proxies\__CG__\Gao\C5Bundle\Entity;

/**
 * Class: DisputeBiz.
 */
class DisputeBiz
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

    public function mainList($usr, $page, $itemsLimitPerPage, $sort)
    {
        try {
            $disputeList = $this->container->get('dispute_service')->getDisputeByUser($usr->getId(), $page, $itemsLimitPerPage, $sort);
            $disputeTotal = $this->container->get('dispute_service')->countDisputeByUser($usr->getId());
            $pagination = null;
            if ($disputeTotal > 0) {
                // Get the paginator service from the container
                $paginator = $this->container->get('paging_paginator');

                // Set information for the paginator.
                $paginator
                ->setItemsInCurrentPage($disputeList)
                ->setTotalNumberOfItems($disputeTotal);

                $paginator->setItemsLimitPerPage($itemsLimitPerPage);
                // Execute pagination to get pagination information.
                $pagination = $paginator->paginate($page);
            }

            return array(
                'pagination' => $pagination,
                'sort' => $sort
            );
        } catch (\Exception $ex) {
            throw new BizException($ex->getMessage());
        }
    }

    private function prepareData($user, $transaction_id) {
        $dispute = null;
        if (!empty($transaction_id)) {
            $dispute = $this->container->get('dispute_service')->getByTransaction($transaction_id);
        }
        if (empty($dispute)) {
            $attachment_array = [];
        } else {
            $attachment_array = $this->container->get('attachment_service')->getAttachmentByRefer($dispute->getId(), $user->getId());
        }

        return array(
            'dispute' => $dispute,
            'attachment_array' => $attachment_array
        );
    }

    private function formProcess($user, $data, &$params) {
        $session = $this->container->get('request')->getSession();

        // Validate
        if (empty($data['message']) || !$data['message']) {
            $session->getFlashBag()->add('unsuccess', 'Chua nhap thong tin Giai trinh.');
            return;
        }

        if (empty($data['transaction_id'])) {
            $session->getFlashBag()->add('unsuccess', 'Hien tai ban khong the thuc hien chuc nang nay');
            return;
        }

        $dispute = $params['dispute'];
        if (empty($dispute)) {
            $dispute = new Dispute();
        }

        $dispute->setUserId($user->getId());
        $dispute->setMessage($data['message']);
        $dispute->setTransactionId($data['transaction_id']);
        $dispute->setStatus(0);
        $this->container->get('dispute_service')->updateDispute($dispute);

        if (empty($dispute) || empty($dispute->getId())) {
            $session->getFlashBag()->add('unsuccess', 'Khong the tao phan hoi cho giao dich nay. Vui long thu lai');
            return;
        }

        $referId = $dispute->getId();
        $attachment_array = $this->container->get('attachment_service')->updateAttachment($user->getId(), $referId, $data['attachment']);

        $session->getFlashBag()->add('success', 'Cap nhat thanh cong.');

        $params['dispute'] = $dispute;
        $params['attachment_array'] = $attachment_array;
    }
}
