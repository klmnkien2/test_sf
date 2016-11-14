<?php

namespace Gao\C5Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gao\C5Bundle\Biz\BizException;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $usr = $this->get('security.context')->getToken()->getUser();
        return $this->render(
            'GaoC5Bundle:Default:index.html.twig',
            array(
                'error' => 1,
            )
        );
    }

    public function pdAction()
    {
        try {
            $usr = $this->get('security.context')->getToken()->getUser();
            //Call biz logic
            $params = $this->get('pd_biz')->main($usr);

            return $this->render('GaoC5Bundle:Default:pd.html.twig', $params);
        } catch (\Exception $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function gdAction()
    {
        try {
            $usr = $this->get('security.context')->getToken()->getUser();
            //Call biz logic
            $params = $this->get('gd_biz')->main($usr);

            return $this->render('GaoC5Bundle:Default:gd.html.twig', $params);
        } catch (\Exception $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function historyAction()
    {
        $usr = $this->get('security.context')->getToken()->getUser();
        return $this->render(
            'GaoC5Bundle:Default:history.html.twig',
            array(
                'error' => 1,
            )
        );
    }

    public function accountAction()
    {
        $usr = $this->get('security.context')->getToken()->getUser();
        return $this->render(
            'GaoC5Bundle:Default:account.html.twig',
            array(
                'error' => 1,
            )
        );
    }

    public function disputeAction()
    {
        $usr = $this->get('security.context')->getToken()->getUser();
        return $this->render(
            'GaoC5Bundle:Default:dispute.html.twig',
            array(
                'error' => 1,
            )
        );
    }

    public function testAction()
    {
        // Add a new User
        //$this->container->get('automation_service')->createUserForTest('test1', '123');
        //$this->container->get('automation_service')->createUserForTest('test2', '123');
        //$this->container->get('automation_service')->createUserForTest('test3', '123');

        // Create pin
        //$this->container->get('automation_service')->createPin();

        // Create transaction
        $this->container->get('automation_service')->createTransaction(1,4, '12345', 1, 5, '90000', 5000);

        return new JsonResponse(array('status' => 'DONE'));
    }
}
