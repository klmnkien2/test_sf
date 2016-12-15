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
            $params = $this->get('admin_detail_biz')->main();

            return $this->render('GaoAdminBundle:Admin:detail.html.twig', $params);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function editAction()
    {
        try {
            // Get request object.
            $request = $this->getRequest();

            $id = $request->query->get('id');

            $params = $this->get('admin_detail_biz')->main($id);

            return $this->render('GaoAdminBundle:Admin:detail.html.twig', $params);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function listAction()
    {
        try {
            // Get request object.
            $request = $this->getRequest();

            // Get query parameter.
            $page = $request->query->get('page', 1);
            $sort = $request->query->get('sort', 'non');

            //Call biz logic
            $params = $this->get('admin_list_biz')->mainList($usr, $page, 10, $sort);

            return $this->render('GaoAdminBundle:Admin:list.html.twig', $params);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }
}
