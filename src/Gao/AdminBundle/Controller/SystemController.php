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
            if ($request->isXmlHttpRequest()) {
                $post = Request::createFromGlobals();
                $session = $this->container->get('request')->getSession();
                if ($post->request->has('submit')) {
                    $number = $post->request->get('number');
                    $data = ['pin_number' => $pin_number];
                    $this->formProcessPin($usr, $data, $params);
                    $session->getFlashBag()->add('success', "$number pins have been created.");
                }
            }
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }
}
