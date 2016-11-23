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
        try {
            // Get request object.
            $request = $this->getRequest();

            // Get query parameter.
            $page = $request->query->get('page', 1);
            $sort = $request->query->get('sort', 'non');

            $usr = $this->get('security.context')->getToken()->getUser();
            //Call biz logic
            $params = $this->get('history_biz')->main($usr, $page, 10, $sort);

            return $this->render('GaoC5Bundle:Default:history.html.twig', $params);
        } catch (\Exception $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function accountAction()
    {
        try {
            //Call biz logic
            $params = $this->get('account_biz')->main();

            return $this->render('GaoC5Bundle:Default:account.html.twig', $params);
        } catch (\Exception $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function disputeAction()
    {
        try {
            // Get request object.
            $request = $this->getRequest();

            //Call biz logic
            $pdId = $request->query->get('pdId');
            $gdId = $request->query->get('gdId');

            $params = $this->get('dispute_biz')->main($pdId, $gdId);

            return $this->render('GaoC5Bundle:Default:dispute.html.twig', $params);
        } catch (\Exception $ex) {
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

            return $this->render('GaoC5Bundle:Default:list_dispute.html.twig', $params);
        } catch (\Exception $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
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
