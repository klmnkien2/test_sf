<?php

namespace Gao\C5Bundle\Biz;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;
use Gao\C5Bundle\Biz\BizException;

/**
 * Class: PdBiz.
 */
class PdBiz
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
                $mode = 'pin';
                $message = 'Tai khoan chua tung thuc hien PD. Hay thuc hien PD dau tien.';
            } else {
                $mode = 'error';
                $message = 'Tai khoan chua den thoi gian thuc hien PD. Vui long cho';
            }
        } else if ($pdGdState == $allState['PD_Requested']) {
            $mode = 'tran';
            $message = 'Da xac nhan ma PIN. Vui long cho de he thong sap xep giao dich cho ban.';
        } else if ($pdGdState == $allState['PD_Matched']) {
            $mode = 'tran';
            $message = 'Dang trong phien giao dich. Vui long hoan tat cac giao dich duoi day.';
        } else if ($pdGdState == $allState['PD_Done']) {
            $mode = 'error';
            $message = 'Da thuc hien xong giao dich PD. Vui long kiem tra giao dich GD.';
        } else if ($pdGdState == $allState['GD_Requested']) {
            $mode = 'error';
            $message = 'Da thuc hien xong giao dich PD. Vui long kiem tra giao dich GD.';
        } else if ($pdGdState == $allState['GD_Matched']) {
            $mode = 'error';
            $message = 'Da thuc hien xong giao dich PD. Vui long kiem tra giao dich GD.';
        } else if ($pdGdState == $allState['GD_Done']) {
            $mode = 'pin';
            $message = 'Tai khoan den luot PD. Vui long nhap ma pin de hoan tat.';
        }

        // current pd
        $current_pd = $this->container->get('transaction_service')->getCurrentPdByUser($usr->getId());
        if (!empty($current_pd)) {
            $transactionList = $this->container->get('transaction_service')->getTransactionByPd($current_pd->getId());
        } else {
            $transactionList = null;
        }

        return array(
            'mode' => $mode,
            'message' => $message,
            'current_pd' => $current_pd,
            'transactionList' => $transactionList
        );
    }

    private function formProcessPin($usr, $data, &$params) {
        $data['user_id'] = $usr->getId();
        $check = $this->container->get('transaction_service')->checkPinForPd($data);// update pin, tao pd pendding, update user pd_gd_state

        $session = $this->container->get('request')->getSession();
        if (!$check['error']) {
            $params['mode'] = 'tran';
            $params['current_pd'] = $check['pd'];
            $params['message'] = 'Da xac nhan ma PIN. Vui long cho de he thong sap xep giao dich cho ban.';
            $session->getFlashBag()->add('success', $check['message']);
        } else {
            $session->getFlashBag()->add('unsuccess', $check['error']);
        }

    }
}
