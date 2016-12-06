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

    public function main($pdId, $gdId)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (empty($pdId) && empty($gdId)) {
            // Check current state and current pd, gd
            $allState = $this->container->getParameter('pd_gd_state');
            $pdGdState = $user->getPdGdState();
            if ($pdGdState == $allState['PD_Matched']) {
                $current_pd = $this->container->get('transaction_service')->getCurrentPdByUser($user->getId());
                if (!empty($current_pd)) {
                    $pdId = $current_pd->getId();
                }
            } else if ($pdGdState == $allState['GD_Requested']) {
                $current_gd = $this->container->get('transaction_service')->getCurrentGdByUser($user->getId());
                if (!empty($current_gd)) {
                    $gdId = $current_gd->getId();
                }
            }
        }

        if (!empty($pdId) || !empty($gdId)) {
            $params = $this->prepareData($user, $pdId, $gdId);
        } else {
            $params = [
                'message' => '',
                'attachment_array' => []
            ];
        }

        $post = Request::createFromGlobals();
        if ($post->request->has('submit')) {
            $data = [
                'message' => $post->request->get('message'),
                'attachment' => $post->request->get('attachment'),
                'pdId' => $pdId,
                'gdId' => $gdId
            ];
            $this->formProcess($user, $data, $params);
        }

        return $params;
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

    private function prepareData($user, $pdId, $gdId) {

        if (!empty($pdId)) {
            $dispute = $this->container->get('dispute_service')->getByPd($pdId);
        } else {
            $dispute = $this->container->get('dispute_service')->getByGd($gdId);
        }
        if (empty($dispute)) {
            $dispute = new Dispute;
            $attachment_array = [];
        } else {
            $attachment_array = $this->container->get('attachment_service')->getAttachmentByRefer($dispute->getId(), $user->getId());
        }

        return array(
            'message' => $dispute->getMessage(),
            'attachment_array' => $attachment_array,
            'id' => $dispute->getId()
        );
    }

    private function formProcess($user, $data, &$params) {
        $session = $this->container->get('request')->getSession();

        // Validate
        if (empty($data['message']) || !$data['message']) {
            $session->getFlashBag()->add('unsuccess', 'Chua nhap thong tin Gia trinh.');
            return;
        }

        if (empty($data['pdId']) && empty($data['gdId'])) {
            $session->getFlashBag()->add('unsuccess', 'Hien tai ban khong trong qua trinh thuc hien giao dich. Khong the thuc hien chuc nang nay');
            return;
        }

        $dispute = $this->container->get('dispute_service')->createDispute($user->getId(), $data['pdId'], $data['gdId'], $data['message']);

        $referId = empty($data['pdId']) ? $data['pdId'] : $data['gdId'];
        $attachment_array = $this->container->get('attachment_service')->updateAttachment($user->getId(), $referId, $data['attachment']);

        $session->getFlashBag()->add('success', 'Cap nhat thanh cong.');

        $params['message'] = $dispute->getMessage();
        $params['attachment_array'] = $attachment_array;
        $params['id'] = $dispute->getId();
    }
}
