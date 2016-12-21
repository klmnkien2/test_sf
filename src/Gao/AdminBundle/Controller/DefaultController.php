<?php

namespace Gao\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gao\AdminBundle\Biz\BizException;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GaoAdminBundle:Default:index.html.twig');
    }

    public function bankAction()
    {
        return $this->render('GaoAdminBundle:Default:bank_log.html.twig');
    }

    public function ajaxBankAction()
    {
        try {
            $response = $this->get('transaction_service')->getBankLogTable();

            return new JsonResponse($response);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }
}
