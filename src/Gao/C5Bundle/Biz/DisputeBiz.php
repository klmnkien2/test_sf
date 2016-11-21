<?php

namespace Gao\C5Bundle\Biz;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;
use Gao\C5Bundle\Biz\BizException;
use Gao\C5Bundle\Entity\Dispute;
use Gao\C5Bundle\Form\DisputeType;

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

    public function main($id, $pdId, $gdId)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        $post = Request::createFromGlobals();
        if ($post->request->has('submit')) {
            $data = [
                'message' => $post->request->get('message'),
                'attachment' => $post->request->get('attachment'),
                'id' => $id,
                'pdId' => $pdId,
                'gdId' => $gdId
            ];
            $this->formProcess($user, $data);
        }

        $params = $this->prepareData($user, $data);

        return $params;
    }

    public function mainList($usr, $page, $itemsLimitPerPage, $sort)
    {
        try {
            $disputeList = $this->container->get('dispute_service')->getDisputeByUser($usr->getId(), $page, $itemsLimitPerPage, $sort);
            $disputeTotal = $this->container->get('dispute_service')->countDisputeByUser($usr->getId());
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

    private function prepareData($user, $data) {
        if (!empty($data['attachment'])) {
            $attachment_array = $this->container->get('attachment_service')->getAttachmentByIds($data['attachment']);
        }

        $dispute = $this->container->get('dispute_service')->getById($data['id']);

        return array(
            'message' => $dispute->getMessage(),
            'attachment_array' => $attachment_array,
            'id' => $data['id']
        );
    }

    private function formProcess($user, $data) {
        $session = $this->container->get('request')->getSession();
        try {
            // Validate
            if (empty($data['message']) || !$data['message']) {
                $session->getFlashBag()->add('unsuccess', 'Chua nhap thong tin Gia trinh.');
                return;
            }

            if (empty($data['pdId']) || empty($data['gdId'])) {
                $session->getFlashBag()->add('unsuccess', 'Chua gan voi PD, hoac GD nao.');
                return;
            }

            $dispute = $this->container->get('dispute_service')->createDispute($user->getId(), $data['pdId'], $data['gdId'], $data['message']);

            $referId = empty($data['pdId']) ? $data['pdId'] : $data['gdId'];
            $this->container->get('attachment_service')->updateAttachment($userId, $referId, $attachment);

            $session->getFlashBag()->add('success', 'Cap nhat thanh cong.');
        } catch (\Exception $ex) {
            throw  $ex;
            $session->getFlashBag()->add('unsuccess', 'Cap nhat khong thanh cong.');
        }
    }
}
