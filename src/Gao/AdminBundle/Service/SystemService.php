<?php

/**
 * @category Service
 *
 * @author KienDV
 *
 * @version 1.0
 */

namespace Gao\AdminBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * System Service.
 */
class SystemService
{
    /**
     * EntityManager.
     */
    protected $em;

    /**
     * Container Interface.
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param EntityManager $em        The EntityManager.
     * @param Container     $container The Container Interface.
     */
    public function __construct(EntityManager $em, Container $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function matchTransaction()
    {
        $this->output = '';
        try {
            $this->output .= ( "========START========" . "<br>" );
            $this->init_data();
            $this->container->get('automation_service')->beginTransaction();
            $this->match_job();
            $this->container->get('automation_service')->commitTransaction();
            $this->output .= ( "========SUCCESS=======" . "<br>" );
        } catch (\Exception $ex) {
            $this->container->get('automation_service')->rollbackTransaction();
            $this->output .= ( "=========[ERROR]=======" . "<br>" . $ex->getMessage() . "<br>" );
        }

        return $this->output;
    }

    private function init_data()
    {
        $wait_pd = $this->container->get('automation_service')->getAllWaitPd();
        $this->send_amounts = array();
        foreach ($wait_pd as $pd){
            $this->send_amounts[$pd->getId()] = $pd->getPdAmount()?($pd->getPdAmount()/10000):0;
        }

        $wait_gd = $this->container->get('automation_service')->getAllWaitGd();
        $this->receive_amounts = array();
        foreach ($wait_gd as $gd){
            $this->receive_amounts[$gd->getId()] = $gd->getGdAmount()?($gd->getGdAmount()/10000):0;
        }

//         $this->output .= ( "SEND DATA"  . "<br>" );
//         print_r($this->send_amounts);
//         $this->output .= ( "RECEIVE DATA"  . "<br>" );
//         print_r($this->receive_amounts);
    }
    
    private function send_money($from, $to, $money)
    {
        if ($money == 0) {
            $this->output .= ( "Bug found. send money = 0." . "<br>" );
            return;
        }

        $this->send_amounts[$from] -= $money;
        $this->receive_amounts[$to] -= $money;

        $this->output .= ( "PD[$from] => GD[$to] : $money" . "0000<br>" );

        // Create transaction
        $this->container->get('automation_service')->createTransactionFromPdGd($from, $to, $money * 10000);
    }
    
    private function match_job()
    {
        $base_amount = $this->container->getParameter('default_tran_base_amount')/10000;
    
        $total_send = array_sum($this->send_amounts);
        $send_up_to = 0;
        $last_receive_id = null;
        $total_receive = 0;
        foreach($this->receive_amounts as $key => $value) {
            if ($total_receive <= $total_send && $send_up_to < count($this->receive_amounts)) {
                $total_receive += $value;
                $send_up_to ++;
                $last_receive_id = $key;
            } else {
                unset($this->receive_amounts[$key]);
            }
        }
        if ($send_up_to == count($this->receive_amounts) && $total_receive < $total_send) {
            $this->output .= ( "[NOTICE] Lack PD. Push BOT to GD stack" . "<br>" );
            $more_gd = $this->container->get('automation_service')->getMoreBotGd(3, $total_send - $total_receive);
            foreach ($more_gd as $gd){
                $this->receive_amounts[$gd->getId()] = $gd->getGdAmount()?:0;
                $total_receive += $gd->getGdAmount()?:0;
            }
        }

        if ($total_receive == $total_send) {
            // already good!
        } else {
            $total_receive -= $this->receive_amounts[$last_receive_id];
            unset($this->receive_amounts[$last_receive_id]);
            // CREATE BOT RECEIVE MONEY
            $more_gd = $this->container->get('automation_service')->getMoreBotGd(1, $total_send - $total_receive);
            foreach ($more_gd as $gd){
                $this->receive_amounts[$gd->getId()] = $gd->getGdAmount()?:0;
                $total_receive += $gd->getGdAmount()?:0;
            }
        }

        $this->output .= ( "TOTAL SEND: " . $total_send  . "0000<br>" );
        $this->output .= ( "TOTAL RECEIVE: " . $total_receive  . "0000<br>" );
        asort($this->send_amounts);
        asort($this->receive_amounts);

        //$this->output .= ( "SEND DATA: "  . "<br>" );
        //print_r($this->send_amounts);
        //$this->output .= ( "RECEIVE DATA: "  . "<br>" );
        //print_r($this->receive_amounts);

        /**
         * START ALGORITHM
        */
        $receive_keys = array_keys($this->receive_amounts);
        $send_keys = array_keys($this->send_amounts);
        $send_full_to = - 1;

        $reach_manual = false;
        for ($i = 0; $i < count($this->send_amounts); $i ++) {
            if ($reach_manual)
                continue;
            // now try to process this person send amount
            $id = $send_keys[$i];
            // $this->output .= ( "Process $id\r\n";
            while ($this->send_amounts[$id] >= 1.5 * $base_amount) {
                if ($this->receive_amounts[$receive_keys[$send_full_to + 1]] <= 1.5 * $base_amount) {
                    $send_full_to ++;
                    // $this->output .= ( "Finalize ".$receive_keys[$send_full_to]."\r\n";
                    if ($this->send_amounts[$id] >= $this->receive_amounts[$receive_keys[$send_full_to]]) {
                        $this->send_money($id, $receive_keys[$send_full_to], $this->receive_amounts[$receive_keys[$send_full_to]]);
                        // $this->output .= ( "$id => ".$receive_keys[$send_full_to].": ".$this->receive_amounts[$receive_keys[$send_full_to]]." \r\n";
                        // $this->send_amounts[$id] -= $this->receive_amounts[$receive_keys[$send_full_to]];
                    } else {
                        $nid = $send_keys[$i + 1];
                        if ($this->send_amounts[$nid] - $this->receive_amounts[$receive_keys[$send_full_to]] >= $this->send_amounts[$id]) {
                            $this->send_money($nid, $receive_keys[$send_full_to], $this->receive_amounts[$receive_keys[$send_full_to]]);
                            // $this->output .= ( "$nid => ".$receive_keys[$send_full_to].": ".$this->receive_amounts[$receive_keys[$send_full_to]]." \r\n";
                            // $this->send_amounts[$nid] -= $this->receive_amounts[$receive_keys[$send_full_to]];
                        } else {
                            $this->send_money($id, $receive_keys[$send_full_to], $this->send_amounts[$id]);
                            $this->send_money($nid, $receive_keys[$send_full_to], $this->receive_amounts[$receive_keys[$send_full_to]] - $this->send_amounts[$id]);
                            // $this->output .= ( "$id => ".$receive_keys[$send_full_to].": ".$this->send_amounts[$id]." \r\n";
                            // $this->output .= ( "$nid => ".$receive_keys[$send_full_to].": ".($this->receive_amounts[$receive_keys[$send_full_to]]-$this->send_amounts[$id])." \r\n";
                            // $this->send_amounts[$nid] -= ($this->receive_amounts[$receive_keys[$send_full_to]]-$this->send_amounts[$id]);
                            // $this->send_amounts[$id] = 0;
                        }
                    }
                    if ($send_full_to == count($this->receive_amounts) - 1) {
                        $reach_manual = true;
                        break;
                    }
                } else {
                    // $this->output .= ( "Send full $id\r\n";
                    $n = floor($this->send_amounts[$id] / $base_amount);
                    if ($n + 1 > count($this->receive_amounts) - $send_full_to - 1) {
                        // we can stop here and do manual if needed
                        // $reach_manual = true;
                        // break;
                        while (true) {
                            $need_receive_left = (count($this->receive_amounts) - $send_full_to - 1);
                            $last_base_amount = floor($this->send_amounts[$id] / $need_receive_left);
                            $cnt_plus_1 = $this->send_amounts[$id] - ($last_base_amount * $need_receive_left);
                            $first_need_plus_1 = ($cnt_plus_1 > 0) ? 1 : 0;
                            if ($last_base_amount + $first_need_plus_1 > $this->receive_amounts[$receive_keys[$send_full_to + 1]]) {
                                $send_full_to ++;
                                $this->send_money($id, $receive_keys[$send_full_to], $this->receive_amounts[$receive_keys[$send_full_to]]);
                                continue;
                            }
                            $receive_full_this_round = 0;
                            for ($j = 1; $j <= $cnt_plus_1; $j ++) {
                                $this->send_money($id, $receive_keys[$send_full_to + $j], $last_base_amount + 1);
                                if ($this->receive_amounts[$receive_keys[$send_full_to + $j]] == 0)
                                    $receive_full_this_round ++;
                            }
                            for ($j = $cnt_plus_1 + 1; $j <= $need_receive_left; $j ++) {
                                $this->send_money($id, $receive_keys[$send_full_to + $j], $last_base_amount);
                                if ($this->receive_amounts[$receive_keys[$send_full_to + $j]] == 0)
                                    $receive_full_this_round ++;
                            }
                            $send_full_to += $receive_full_this_round;
                            break;
                        }
                    } else {
                        if (($n + 0.5) * $base_amount <= $this->send_amounts[$id]) {
                            for ($j = 1; $j <= $n; $j ++) {
                                $this->send_money($id, $receive_keys[$send_full_to + $j], $base_amount);
                                // $this->output .= ( "$id => ".$receive_keys[$send_full_to+$j].": ".$base_amount." \r\n";
                                // $this->send_amounts[$id] -= $base_amount;
                                // $this->receive_amounts[$receive_keys[$send_full_to+$j]] -= $base_amount;
                            }
                            // $this->output .= ( "Problem??\r\n";
                            $this->send_money($id, $receive_keys[$send_full_to + $j], $this->send_amounts[$id]);
                            // $this->output .= ( "$id => ".$receive_keys[$send_full_to+$j].": ".$this->send_amounts[$id]." \r\n";
                            // $this->receive_amounts[$receive_keys[$send_full_to+$j]] -= $this->send_amounts[$id];
                            // $this->send_amounts[$id] = 0;
                        } else {
                            // $this->output .= ( "Problem??\r\n";
                            for ($j = 2; $j <= $n; $j ++) {
                                $this->send_money($id, $receive_keys[$send_full_to + $j], $base_amount);
                                // $this->output .= ( "$id => ".$receive_keys[$send_full_to+$j].": ".$base_amount." \r\n";
                                // $this->send_amounts[$id] -= $base_amount;
                                // $this->receive_amounts[$receive_keys[$send_full_to+$j]] -= $base_amount;
                            }
                            $this->send_money($id, $receive_keys[$send_full_to + 1], $this->send_amounts[$id]);
                            // $this->output .= ( "$id => ".$receive_keys[$send_full_to+1].": ".$this->send_amounts[$id]." \r\n";
                            // $this->receive_amounts[$receive_keys[$send_full_to+1]] -= $this->send_amounts[$id];
                            // $this->send_amounts[$id] = 0;
                        }
                    }
                }
            }
            if ($reach_manual)
                continue;
            if ($this->send_amounts[$id] == 0)
                continue;
            while ($this->send_amounts[$id] >= $this->receive_amounts[$receive_keys[$send_full_to + 1]]) {
                $send_full_to ++;
                $this->send_money($id, $receive_keys[$send_full_to], $this->receive_amounts[$receive_keys[$send_full_to]]);
                if ($send_full_to == count($this->receive_amounts) - 1) {
                    $reach_manual = true;
                    break;
                }
            }
            if ($this->send_amounts[$id] == 0)
                continue;
            $this->send_money($id, $receive_keys[$send_full_to + 1], $this->send_amounts[$id]);
        }
    }
}
