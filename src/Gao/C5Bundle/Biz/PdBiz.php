<?php

namespace Gao\C5Bundle\Biz;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Class: BuildingBiz.
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
        $mode = 'error';
        $message = NULL;

        $firstPdDone = $usr->getFirstPdDone();
        $pdGdState = $usr->getPdGdState();

        if (empty($firstPdDone) || !$firstPdDone) {
            $mode = 'active';
            $message = 'Tai khoan chua tung thuc hien PD. Hay thuc hien PD dau tien.';
            // need update pd_gd_state
        } else {
            $allState = $this->container->getParameter('pd_gd_state');
            if (empty($pdGdState) || $pdGdState == $allState['Pending']) {
                $mode = 'error';
                $message = 'Tai khoan chua den thoi gian thuc hien PD.';
            } else if ($pdGdState == $allState['PD_Requested']) {
                $mode = 'active';
                $message = 'Tai khoan den luot PD. Hay nhap ma pin de hoan tat.';
            } else if ($pdGdState == $allState['PD_Matched']) {
                $mode = 'wait';
                $message = 'Da xac nhan ma pin. Vui long doi confirm tu GD';
            } else if ($pdGdState == $allState['PD_Done']) {
                $mode = 'wait';
                $message = 'Da xac nhan hoan tat PD. Vui long doi de duoc thuc hien GD.';
            } else if ($pdGdState == $allState['GD_Requested']) {
                $mode = 'error';
                $message = 'Dang trong qua trinh thuc hien GD. Vui long kiem tra menu GD.';
            } else if ($pdGdState == $allState['GD_Matched']) {
                $mode = 'error';
                $message = 'Dang trong qua trinh thuc hien GD. Vui long kiem tra menu GD.';
            } else if ($pdGdState == $allState['GD_Done']) {
                $mode = 'error';
                $message = 'Da xac nhan hoan tat GD. Chua den thoi gian thuc hien PD tiep theo. Vui long cho.';
            }
        }

        return array(
            'mode' => $mode,
            'message' => $message
        );
    }

}
