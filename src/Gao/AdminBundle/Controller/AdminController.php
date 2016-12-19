<?php

namespace Gao\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gao\AdminBundle\Biz\BizException;
use Symfony\Component\Validator\Constraints;

class AdminController extends Controller
{
    public function newAction()
    {
        try {
            $params = $this->get('admin.account_detail_biz')->main();

            return $this->render('GaoAdminBundle:Admin:detail.html.twig', $params);
        } catch (BizException $ex) {
            if ($ex->redirect) {
                return $this->redirect($ex->redirect);
            }
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function editAction()
    {
        try {
            // Get request object.
            $request = $this->getRequest();

            $id = $request->query->get('id');

            $params = $this->get('admin.account_detail_biz')->main($id);

            return $this->render('GaoAdminBundle:Admin:detail.html.twig', $params);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function listAction()
    {
        try {
            return $this->render('GaoAdminBundle:Admin:list.html.twig');
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function ajaxListAction()
    {
        try {
            $response = $this->get('admin_service')->getAdminByUserId(null, null);

            return new JsonResponse($response);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }
}
