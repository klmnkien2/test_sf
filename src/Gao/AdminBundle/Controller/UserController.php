<?php

namespace Gao\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gao\AdminBundle\Biz\BizException;
use Symfony\Component\Validator\Constraints;

class UserController extends Controller
{
    public function newAction()
    {
        try {
            $params = $this->get('admin.user_detail_biz')->main();

            return $this->render('GaoAdminBundle:User:detail.html.twig', $params);
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

            $params = $this->get('admin.user_detail_biz')->main($id);

            return $this->render('GaoAdminBundle:User:detail.html.twig', $params);
        } catch (BizException $ex) {
            if ($ex->redirect) {
                return $this->redirect($ex->redirect);
            }
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function deleteAction()
    {
        try {
            $request = $this->getRequest();
            $token = $request->query->get('token');
            $id = $request->query->get('id');

            if (!$this->get('form.csrf_provider')->isCsrfTokenValid('user_list', $token)) {
                $this->get('session')->getFlashBag()->add('unsuccess', 'Woops! Token invalid!');
            } else {
                $this->get('admin.user_service')->removeEntity($id);
                $this->get('session')->getFlashBag()->add('success', 'An user have been removed.');
            }

            $listPath = $this->container->get('router')->generate('gao_admin_user_list');
            return $this->redirect($listPath);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function listUpdateAction()
    {
        try {
            $request = $this->getRequest();
            $token = $request->query->get('token');
            $action = $request->query->get('action');
            $ids = $request->request->get('user_ids');

            if (!$this->get('form.csrf_provider')->isCsrfTokenValid('user_list_action', $token)) {
                $this->get('session')->getFlashBag()->add('unsuccess', 'Woops! Token invalid!');
            } else {
                if ($action == 'delete') {
                    $this->get('admin.user_service')->deleteUsers($ids);
                } else if ($action == 'blocked') {
                    $blocked = $request->query->get('blocked');
                    $this->get('admin.user_service')->blockedUsers($ids, $blocked);
                } 
                $this->get('session')->getFlashBag()->add('success', 'Users have been updated.');
            }
    
            $listPath = $this->container->get('router')->generate('gao_admin_user_list');
            return $this->redirect($listPath);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function blockStatusAction()
    {
        try {
            $request = $this->getRequest();
            $token = $request->query->get('token');
            $id = $request->query->get('id');
            $blocked = $request->query->get('blocked');

//             if (!$this->get('form.csrf_provider')->isCsrfTokenValid('user_list', $token)) {
//                 $this->get('session')->getFlashBag()->add('unsuccess', 'Woops! Token invalid!');
//             } else {
            $user = $this->get('admin.user_service')->getEntity($id);
            $user->setBlocked($blocked);
            $this->get('security_user_service')->updateUser($user);
            $this->get('session')->getFlashBag()->add('success', 'An user have been updated.');
//             }
    
            $listPath = $this->container->get('router')->generate('gao_admin_user_list');
            return $this->redirect($listPath);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function listAction()
    {
        try {
            // Get request object.
            $request = $this->getRequest();
            $search_query = $request->query->get('q');
            $search_conditions = explode(",", $search_query);
            $global_filters = array();
            foreach ($search_conditions as $condition) {
                list($key, $value) = explode(':', $condition);
                $global_filters[] = array('key' => $key, 'value' => $value);
            }
            $this->get('form.csrf_provider')->generateCsrfToken('user_list_action');
            return $this->render('GaoAdminBundle:User:list.html.twig', array('global_filters' => $global_filters));
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    public function ajaxListAction()
    {
        try {
            $adminUser = $this->container->get('security.context')->getToken()->getUser();
            $token = $this->get('form.csrf_provider')->generateCsrfToken('user_list');
            $response = $this->get('admin.user_service')->getDataTable($adminUser->getId(), $token);

            return new JsonResponse($response);
        } catch (BizException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }
}
