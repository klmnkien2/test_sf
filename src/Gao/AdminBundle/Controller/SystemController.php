<?php

namespace Gao\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gao\AdminBundle\Biz\BizException;
use Symfony\Component\Validator\Constraints;

class SystemController extends Controller
{
    public function toolAction()
    {
        try {
            return $this->render('GaoAdminBundle:System:tool.html.twig');
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function createPinAction()
    {
        try {
            $request = $this->getRequest();
            if ($request->isXmlHttpRequest()) {
                $post = Request::createFromGlobals();
                $number = $post->request->get('number');
                while ($number > 0) {
                    $this->get('automation_service')->createPin();
                    $number -= 1;
                }
                return new JsonResponse(array(
                    'error' => false
                ));
            }
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function matchTransactionAction()
    {
        try {
            $request = $this->getRequest();
            if ($request->isXmlHttpRequest()) {
                $this->get('admin.system_service')->matchTransaction();
                return new JsonResponse(array(
                    'error' => false
                ));
            }
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function cleanUpAction()
    {
        try {
            $request = $this->getRequest();
            if ($request->isXmlHttpRequest()) {
                $this->get('automation_service')->finishRound();
                return new JsonResponse(array(
                    'error' => false
                ));
            }
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function resetUserAction()
    {
        try {
            $request = $this->getRequest();
            if ($request->isXmlHttpRequest()) {
                $this->get('automation_service')->resetUser();
                return new JsonResponse(array(
                    'error' => false
                ));
            }
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function forceRequestAction()
    {
        try {
            $request = $this->getRequest();
            if ($request->isXmlHttpRequest()) {
                $this->get('automation_service')->forceRequest();
                return new JsonResponse(array(
                    'error' => false
                ));
            }
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function forceDoneAction()
    {
        try {
            $request = $this->getRequest();
            if ($request->isXmlHttpRequest()) {
                $this->get('automation_service')->testFinishRound();
                return new JsonResponse(array(
                    'error' => false
                ));
            }
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }
}
