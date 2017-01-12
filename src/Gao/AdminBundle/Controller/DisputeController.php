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

            $params = $this->get('admin.account_detail_biz')->main($id);

            return $this->render('GaoAdminBundle:Dispute:detail.html.twig', $params);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function updateStatusAction()
    {
        try {
            $request = $this->getRequest();
            $token = $request->query->get('token');
            $id = $request->query->get('id');
            $status = $request->query->get('status');

            if (!$this->get('form.csrf_provider')->isCsrfTokenValid('dispute_list', $token)) {
                $this->get('session')->getFlashBag()->add('unsuccess', 'Woops! Token invalid!');
            } else {
                $this->get('admin_service')->removeEntity($id);
                $this->get('session')->getFlashBag()->add('success', 'An admin have been removed.');
            }

            $listPath = $this->container->get('router')->generate('gao_admin_account_list');
            return $this->redirect($listPath);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
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
