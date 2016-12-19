<?php

namespace Gao\AdminBundle\Biz\Account;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;
use Gao\AdminBundle\Biz\BizException;
use Gao\AdminBundle\Entity\Admin;
use Gao\AdminBundle\Form\AdminType;

/**
 * Class: DetailBiz.
 */
class DetailBiz
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

    public function main($id = null)
    {
        $adminUser = $this->container->get('security.context')->getToken()->getUser();
        // Get request object.
        $request = $this->container->get('request');

        $admin = new Admin();
        $form = $this->container->get('form.factory')->create(new AdminType(), $admin);
        $form->handleRequest($request);

        // process the form on POST
        if ($request->isMethod('POST')) {
            if ($form->isValid()) {
                if (!$admin->getId() && $this->container->get('admin_service')->isExist($admin->getUsername())) {
                    $session = $request->getSession();
                    $session->getFlashBag()->add('unsuccess', 'Username have been used');
                } else {
                    $this->container->get('admin_service')->saveEntity($admin);

                    $session = $request->getSession();
                    $session->getFlashBag()->add('success', 'An admin have been saved!');

                    $exception = new BizException();
                    $exception->redirect = $this->container->get('router')->generate('gao_admin_account_list');

                    throw $exception;
                }
            }
        }

        return array(
            'form' => $form->createView()
        );
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
