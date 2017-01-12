<?php

namespace Gao\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gao\AdminBundle\Biz\BizException;
use Symfony\Component\Validator\Constraints;

class DisputeController extends Controller
{
    public function detailAction()
    {
        try {
            // Get request object.
            $request = $this->getRequest();

            $id = $request->query->get('id');

            $dispute = null;
            $attachment_array = [];
            $transactionList = null;
            if (!empty($id)) {
                $dispute = $this->container->get('dispute_service')->getDisputeById($id);
            }
            if (!empty($dispute)) {
                $attachment_array = $this->container->get('attachment_service')->getAttachmentByRefer($dispute['id'], $dispute['user_id']);
                $transactionList = $this->container->get('transaction_service')->getTransactionByPd($dispute['pd_id']);
            }

            $params = array(
                'dispute' => $dispute,
                'attachment_array' => $attachment_array,
                'transactionList' => $transactionList
            );

            return $this->render('GaoAdminBundle:Dispute:detail.html.twig', $params);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function updateStatusAction()
    {
        $request = $this->getRequest();
        $token = $request->query->get('token');
        $id = $request->query->get('id');
        $status = $request->query->get('status');

//             if (!$this->get('form.csrf_provider')->isCsrfTokenValid('dispute_list', $token)) {
//                 $this->get('session')->getFlashBag()->add('unsuccess', 'Woops! Token invalid!');
//             } else {
        $this->container->get('transaction_service')->beginTransaction();
        try {
            // Update dispute
            $dispute = $this->get('dispute_service')->getById($id);
            $dispute->setStatus($status);
            $this->get('dispute_service')->updateDispute($dispute);
            // Update transaction
            $transaction = $this->container->get('transaction_service')->getEntity($dispute->getTransactionId());
            $transaction->setStatus(1);
            $transaction->setApprovedDate(new \DateTime());
            $this->container->get('transaction_service')->updateEntity($transaction);
            // update pd user
            $userPd = $this->container->get('security_user_service')->getEntity($transaction->getPdUserId());
            if ($status == 1) {
                $userPd->setBlocked(0);
            } else {
                $userPd->setBlocked(2);
            }
            // update gd user if approve (block)
            if ($status == 1) {
                $userGd = $this->container->get('security_user_service')->getEntity($transaction->getGdUserId());
                $userGd->setBlocked(1);
            }
            // Notify success
            $this->container->get('transaction_service')->commitTransaction();
            $this->get('session')->getFlashBag()->add('success', 'Thong tin da duoc update thanh cong.');
        } catch (BizException $ex) {
            $this->container->get('transaction_service')->rollbackTransaction();
            $this->get('session')->getFlashBag()->add('unsuccess', 'Yeu cau thuc hien khong thanh cong.');
        }
//             }

        $listPath = $this->container->get('router')->generate('gao_admin.dispute.detail') . "?id=$id";
        return $this->redirect($listPath);
    }

    public function listAction()
    {
        try {
            return $this->render('GaoAdminBundle:Dispute:list.html.twig');
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function ajaxListAction()
    {
        try {
            $adminUser = $this->container->get('security.context')->getToken()->getUser();
            $token = $this->get('form.csrf_provider')->generateCsrfToken('dispute_list');
            $response = $this->get('dispute_service')->getDataTable($adminUser->getId(), $token);

            return new JsonResponse($response);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }
}
