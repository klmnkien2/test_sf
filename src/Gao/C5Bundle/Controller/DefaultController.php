<?php

namespace Gao\C5Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gao\C5Bundle\Biz\BizException;
use Symfony\Component\Validator\Constraints;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $usr = $this->get('security.context')->getToken()->getUser();
        $pdGdState = $usr->getPdGdState();
        $allState = $this->container->getParameter('pd_gd_state');
        if (in_array($pdGdState, [$allState['PD_Done'], $allState['GD_Requested'], $allState['GD_Matched']])) {
            return $this->gdAction();
        } else {
            return $this->pdAction();
        }
    }

    public function pdAction()
    {
        try {
            $usr = $this->get('security.context')->getToken()->getUser();
            if (!empty($usr->getBlocked()) && $usr->getBlocked()) {
                $session = $this->get('request')->getSession();
                $session->getFlashBag()->add('unsuccess', 'Tai khoan dang bi khoa. Vui long tao bang chung cho cac giao dich duoi day.');
                return $this->redirect($this->generateUrl('gao_c5_history') . '?pd_or_gd=pd&tran_status=0');
            }
            //Call biz logic
            $params = $this->get('pd_biz')->main($usr);

            return $this->render('GaoC5Bundle:Default:pd.html.twig', $params);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function gdAction()
    {
        try {
            $usr = $this->get('security.context')->getToken()->getUser();
            if (!empty($usr->getBlocked()) && $usr->getBlocked()) {
                $session = $this->get('request')->getSession();
                $session->getFlashBag()->add('unsuccess', 'Tai khoan dang bi khoa. Vui long tao bang chung cho cac giao dich duoi day.');
                return $this->redirect($this->generateUrl('gao_c5_history') . '?pd_or_gd=pd&tran_status=0');
            }
            //Call biz logic
            $params = $this->get('gd_biz')->main($usr);

            return $this->render('GaoC5Bundle:Default:gd.html.twig', $params);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function historyAction()
    {
        try {
            // Get request object.
            $request = $this->getRequest();

            // Get query parameter.
            $page = $request->query->get('page', 1);
            $sort = $request->query->get('sort', 'non');
            $pd_or_gd = $request->query->get('pd_or_gd');
            $tran_status = $request->query->get('tran_status');
            $condition = [
                'pd_or_gd' => $pd_or_gd,
                'tran_status' => $tran_status
            ];

            $usr = $this->get('security.context')->getToken()->getUser();
            //Call biz logic
            $params = $this->get('history_biz')->main($usr, $condition, $page, 10, $sort);

            return $this->render('GaoC5Bundle:Default:history.html.twig', $params);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function tranApproveAction()
    {
        $this->container->get('transaction_service')->beginTransaction();
        try {
            $user = $this->get('security.context')->getToken()->getUser();
            $request = $this->getRequest();
            $post = Request::createFromGlobals();
            // response result to client

            $idList = $post->request->get('id');
            foreach ($idList as $id) {
                $entity = $this->container->get('transaction_service')->getEntity($id);

                if (empty($entity)) {
                    throw new BizException('transaction not exsit.');
                }

                // CHeck transaction belong to user_gd or not
                if ($entity->getGdUserId() != $user->getId()) {
                    throw new BizException('transaction gd_id not map current login user');
                }

                $entity->setStatus(1);
                $entity->setApprovedDate(new \DateTime());
                $this->container->get('transaction_service')->updateEntity($entity);

                $finishPd = $this->container->get('transaction_service')->checkPdFinish($entity->getPdId());
                if ($finishPd) {
                    $userPd = $this->container->get('security_user_service')->getEntity($entity->getPdUserId());
                    if (empty($userPd)) {
                        throw new BizException('pd user not exsit with user_id='.$entity->getPdUserId());
                    }
                    $pd = $this->container->get('transaction_service')->getEntityPd($entity->getPdId());
                    if (empty($pd)) {
                        throw new BizException('pd not exsit with pd_id='.$entity->getPdId());
                    }
                    $this->container->get('security_user_service')->userFinishPd($userPd, $pd);
                }

                $finishGd = $this->container->get('transaction_service')->checkGdFinish($entity->getGdId());
                if ($finishGd) {
                    //Cap nhat GD
                    $gd = $this->container->get('transaction_service')->getEntityGd($entity->getGdId());
                    if (empty($gd)) {
                        throw new BizException('gd not exsit with gd_id='.$entity->getGdId());
                    }
                    $this->container->get('security_user_service')->userFinishGd($user, $gd);
                }
            }

            $this->container->get('transaction_service')->commitTransaction();

            $session = $this->get('request')->getSession();
            $session->getFlashBag()->add('success', 'Da xac nhan yeu cau cua ban. Cam on da su dung.');

            return $this->redirect($this->generateUrl('gao_c5_gd'));

        } catch (BizException $ex) {
            $this->container->get('transaction_service')->rollbackTransaction();
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    /**
     * NOT USING NOW
     * @throws BizException
     * @throws NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tranApproveAjaxAction()
    {
        $response = ['status' => 'unsuccess'];
        try {
            $user = $this->get('security.context')->getToken()->getUser();
            $request = $this->getRequest();
            $post = Request::createFromGlobals();
            // response result to client
            if ($request->isXmlHttpRequest()) {
                $id = $post->request->get('id');

                if (empty($id)) {
                    throw new BizException('transaction id null');
                }

                $entity = $this->container->get('transaction_service')->getEntity($id);

                // CHeck transaction belong to user_gd or not
                if ($entity->getGdUserId() != $user->getId()) {
                    throw new BizException('transaction gd_id not map current login user');
                }

                $entity->setStatus(1);
                $entity->setApprovedDate(new \DateTime());
                $this->container->get('transaction_service')->updateEntity($entity);

                $finishPd = $this->container->get('transaction_service')->checkPdFinish($entity->getPdId());
                if ($finishPd) {
                    $userPd = $this->container->get('security_user_service')->getEntity($entity->getPdUserId());
                    $userPd->setPdGdState($this->container->getParameter('pd_gd_state')['PD_Done']);
                    $this->container->get('security_user_service')->updateUser($userPd);
                    //Cap nhat PD 
                }

                $finishGd = $this->container->get('transaction_service')->checkGdFinish($entity->getGdId());
                if ($finishGd) {
                    $user->setPdGdState($this->container->getParameter('pd_gd_state')['GD_Done']);
                    $this->container->get('security_user_service')->updateUser($user);
                }

                $response['status'] = 'success';

            }
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }

        return new JsonResponse($response);
    }

    public function accountAction()
    {
        try {
            //Call biz logic
            $params = $this->get('account_biz')->main();

            return $this->render('GaoC5Bundle:Default:account.html.twig', $params);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function disputeAction()
    {
        try {
            // Get request object.
            $request = $this->getRequest();
            $user = $this->get('security.context')->getToken()->getUser();

            if (!empty($user->getBlocked()) && $user->getBlocked() == 2) {
                throw new BizException('Tài khoản bị khóa hoàn toàn, không thực hiện được chức năng này');
            }

            //Call biz logic
            $transaction_id = $request->query->get('transaction_id');

            $params = $this->get('dispute_biz')->main($transaction_id);

            return $this->render('GaoC5Bundle:Default:dispute.html.twig', $params);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function listDisputeAction()
    {
        try {
            // Get request object.
            $request = $this->getRequest();

            // Get query parameter.
            $page = $request->query->get('page', 1);
            $sort = $request->query->get('sort', 'non');

            $usr = $this->get('security.context')->getToken()->getUser();
            //Call biz logic
            $params = $this->get('dispute_biz')->mainList($usr, $page, 10, $sort);

            return $this->render('GaoC5Bundle:Default:dispute_list.html.twig', $params);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function disputeViewAction()
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

            return $this->render('GaoC5Bundle:Default:dispute_view.html.twig', $params);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function referAction($user_id)
    {
        try {
            // Get request object.
            $request = $this->getRequest();

            // Get query parameter.
            $page = $request->query->get('page', 1);
            $sort = $request->query->get('sort', 'non');

            //Call biz logic
            $params = $this->get('refer_user_biz')->mainList($user_id, $page, 10, $sort);

            return $this->render('GaoC5Bundle:Default:refer_user_list.html.twig', $params);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function testAction()
    {
        $request = $this->getRequest();
        $action = $request->query->get('action');
        if ($action == 'reset-user') {
            $this->get('automation_service')->resetUser();
        }

        if ($action == 'force-request') {
            $this->get('automation_service')->forceRequest();
        }

        if ($action == 'tran-done') {
            $this->get('automation_service')->testFinishRound();
        }

        echo "DONE";die;
    }
}
