<?php

namespace Gao\C5Bundle\Biz;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;
use Gao\C5Bundle\Biz\BizException;

/**
 * Class: GdBiz.
 */
class GdBiz
{
    /**
     * Service Container Interface.
     */
    private $container;

    /**
     * __construct.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * get data & processing data for building business.
     *
     * @param mixed  $id          Id of building
     * @param mixed  $page        Page number
     * @param bool   $is_mobile   Mobile or not mobile
     * @param string $referer_url Referer url
     * @param string $userAgent   User agent send from client.
     *
     * @return mixed
     */
    public function main($usr)
    {
        $params = $this->prepareData($usr);
        $post = Request::createFromGlobals();
        if ($post->request->has('submit')) {
            if ($params['mode'] == 'pin') {
                $pin_number = $post->request->get('pin_number');
                $data = ['pin_number' => $pin_number];
                $this->formProcessPin($usr, $data, $params);
            } else if ($params['mode'] == 'tran') {
                // post hoan thanh giao dich neu co
            } 
        }

        return $params;
    }

    private function prepareData($usr) {
        $mode = 'error';
        $message = null;

        $firstPdDone = $usr->getFirstPdDone();
        $pdGdState = $usr->getPdGdState();

        $allState = $this->container->getParameter('pd_gd_state');
        if (empty($pdGdState) || $pdGdState == $allState['Pending']) {
            if (empty($firstPdDone) || !$firstPdDone) {
                $mode = 'error';
                $message = 'Tai khoan chua tung thuc hien PD. Hay thuc hien PD dau tien.';
                // need update pd_gd_state
            } else {
                $mode = 'error';
                $message = 'Tai khoan chua den thoi gian thuc hien GD. Vui long cho';
            }
        } else if ($pdGdState == $allState['PD_Requested']) {
            $mode = 'error';
            $message = 'Dang thuc hien giao dich PD. Vui long chuyen sang menu Quan ly PD';
        } else if ($pdGdState == $allState['PD_Matched']) {
            $mode = 'error';
            $message = 'Dang thuc hien giao dich PD. Vui long chuyen sang menu Quan ly PD';
        } else if ($pdGdState == $allState['PD_Done']) {
            $mode = 'pin';
            $message = 'Ban da co the yeu cau GD. Vui long dien ma pin';
        } else if ($pdGdState == $allState['GD_Requested']) {
            $mode = 'tran';
            $message = 'Da xac nhan ma PIN. Vui long cho he thong sap xep giao dich GD cho ban.';
        } else if ($pdGdState == $allState['GD_Matched']) {
            $mode = 'tran';
            $message = 'Da trong qua trinh nhan tien. Vui long Click vao nut \'Da nhan\' tuong ung voi ban ghi ban da nhan tien.';
        } else if ($pdGdState == $allState['GD_Done']) {
            $mode = 'error';
            $message = 'Tai khoan den luot PD. Vui long chuyen sang menu Quan ly PD.';
        }

        // current pd
        $current_gd = $this->container->get('transaction_service')->getCurrentGdByUser($usr->getId());
        if (!empty($current_gd)) {
            $transactionList = $this->container->get('transaction_service')->getTransactionByGd($current_gd->getId());
        } else {
            $transactionList = null;
        }

        return array(
            'mode' => $mode,
            'message' => $message,
            'current_gd' => $current_gd,
            'transactionList' => $transactionList
        );
    }

    private function formProcessPin($usr, $data, &$params) {
        $data['user_id'] = $usr->getId();
        $check = $this->container->get('transaction_service')->checkPinForGd($data);// update pin, tao gd pendding, update user pd_gd_state

        $session = $this->container->get('request')->getSession();
        if (!$check['error']) {
            $params['mode'] = 'tran';
            $params['current_gd'] = $check['gd'];
            $params['message'] = 'Da xac nhan ma PIN. Vui long cho de he thong sap xep giao dich cho ban.';
            $session->getFlashBag()->add('success', $check['message']);
        } else {
            $session->getFlashBag()->add('unsuccess', $check['error']);
        }

    }
}
