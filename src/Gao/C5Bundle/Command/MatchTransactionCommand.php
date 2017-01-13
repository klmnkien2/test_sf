<?php
namespace Gao\C5Bundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * MatchTransactionCommand
 * Name class must have suffix 'Command'
 * @author Do Viet Kien
 *
 */
class MatchTransactionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('c5:match')

            // the short description shown while running "php app/console list"
            ->setDescription('Automate match transaction for pd and gd.')

            // the full command description shown when running the command with the "--help" option
            ->setHelp("Automate match transaction for pd and gd.")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        if (!$dialog->askConfirmation($output, '<question>Do you confirm matching transaction?</question>', false)) {
            return;
        }
        try {
            echo "========START========", PHP_EOL;
            $this->init_data();
            $this->getContainer()->get('automation_service')->beginTransaction();
            $this->match_job();
            $this->getContainer()->get('automation_service')->commitTransaction();
        } catch (\Exception $ex) {
            $this->getContainer()->get('automation_service')->rollbackTransaction();
            echo "[ERROR]", $ex->getMessage(), PHP_EOL;
        } finally {
            echo "========FINISH=======", PHP_EOL;
        }
    }

    /**
     * PRIVATE PROCESS FOR MATCHING TRANSACTION
     */

    private function init_data()
    {
        $wait_pd = $this->getContainer()->get('automation_service')->getAllWaitPd();
        $this->send_amounts = array();
        foreach ($wait_pd as $pd){
            $this->send_amounts[$pd->getId()] = $pd->getPdAmount()?:0;
        }

        $wait_gd = $this->getContainer()->get('automation_service')->getAllWaitGd();
        $this->receive_amounts = array();
        foreach ($wait_gd as $gd){
            $this->receive_amounts[$gd->getId()] = $gd->getGdAmount()?:0;
        }
//         echo "SEND DATA" , PHP_EOL;
//         print_r($this->send_amounts);
//         echo "RECEIVE DATA" , PHP_EOL;
//         print_r($this->receive_amounts);
    }

    private function send_money($from, $to, $money)
    {
        if ($money == 0) {
            echo "Bug found. send money = 0.", PHP_EOL;
            return;
        }

        $this->send_amounts[$from] -= $money;
        $this->receive_amounts[$to] -= $money;

        echo "$from => $to : $money", PHP_EOL;

        // Create transaction
        $this->getContainer()->get('automation_service')->createTransactionFromPdGd($from, $to, $money);
    }

    private function match_job()
    {
        $base_amount = $this->getContainer()->getParameter('default_tran_base_amount');

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
            echo "[NOTICE] Lack PD. Push BOT to GD stack", PHP_EOL;
            $more_gd = $this->getContainer()->get('automation_service')->getMoreBotGd(3, $total_send - $total_receive);
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
            $more_gd = $this->getContainer()->get('automation_service')->getMoreBotGd(1, $total_send - $total_receive);
            foreach ($more_gd as $gd){
                $this->receive_amounts[$gd->getId()] = $gd->getGdAmount()?:0;
                $total_receive += $gd->getGdAmount()?:0;
            }
        }

        echo "TOTAL SEND: ", $total_send , PHP_EOL;
        echo "TOTAL RECEIVE: ", $total_receive , PHP_EOL;

        asort($this->send_amounts);
        asort($this->receive_amounts);

        echo "SEND DATA: " , PHP_EOL;
        print_r($this->send_amounts);
        echo "RECEIVE DATA: " , PHP_EOL;
        print_r($this->receive_amounts);

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
            // echo "Process $id\r\n";
            while ($this->send_amounts[$id] >= 1.5 * $base_amount) {
                if ($this->receive_amounts[$receive_keys[$send_full_to + 1]] <= 1.5 * $base_amount) {
                    $send_full_to ++;
                    // echo "Finalize ".$receive_keys[$send_full_to]."\r\n";
                    if ($this->send_amounts[$id] >= $this->receive_amounts[$receive_keys[$send_full_to]]) {
                        $this->send_money($id, $receive_keys[$send_full_to], $this->receive_amounts[$receive_keys[$send_full_to]]);
                        // echo "$id => ".$receive_keys[$send_full_to].": ".$this->receive_amounts[$receive_keys[$send_full_to]]." \r\n";
                        // $this->send_amounts[$id] -= $this->receive_amounts[$receive_keys[$send_full_to]];
                    } else {
                        $nid = $send_keys[$i + 1];
                        if ($this->send_amounts[$nid] - $this->receive_amounts[$receive_keys[$send_full_to]] >= $this->send_amounts[$id]) {
                            $this->send_money($nid, $receive_keys[$send_full_to], $this->receive_amounts[$receive_keys[$send_full_to]]);
                            // echo "$nid => ".$receive_keys[$send_full_to].": ".$this->receive_amounts[$receive_keys[$send_full_to]]." \r\n";
                            // $this->send_amounts[$nid] -= $this->receive_amounts[$receive_keys[$send_full_to]];
                        } else {
                            $this->send_money($id, $receive_keys[$send_full_to], $this->send_amounts[$id]);
                            $this->send_money($nid, $receive_keys[$send_full_to], $this->receive_amounts[$receive_keys[$send_full_to]] - $this->send_amounts[$id]);
                            // echo "$id => ".$receive_keys[$send_full_to].": ".$this->send_amounts[$id]." \r\n";
                            // echo "$nid => ".$receive_keys[$send_full_to].": ".($this->receive_amounts[$receive_keys[$send_full_to]]-$this->send_amounts[$id])." \r\n";
                            // $this->send_amounts[$nid] -= ($this->receive_amounts[$receive_keys[$send_full_to]]-$this->send_amounts[$id]);
                            // $this->send_amounts[$id] = 0;
                        }
                    }
                    if ($send_full_to == count($this->receive_amounts) - 1) {
                        $reach_manual = true;
                        break;
                    }
                } else {
                    // echo "Send full $id\r\n";
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
                                // echo "$id => ".$receive_keys[$send_full_to+$j].": ".$base_amount." \r\n";
                                // $this->send_amounts[$id] -= $base_amount;
                                // $this->receive_amounts[$receive_keys[$send_full_to+$j]] -= $base_amount;
                            }
                            // echo "Problem??\r\n";
                            $this->send_money($id, $receive_keys[$send_full_to + $j], $this->send_amounts[$id]);
                            // echo "$id => ".$receive_keys[$send_full_to+$j].": ".$this->send_amounts[$id]." \r\n";
                            // $this->receive_amounts[$receive_keys[$send_full_to+$j]] -= $this->send_amounts[$id];
                            // $this->send_amounts[$id] = 0;
                        } else {
                            // echo "Problem??\r\n";
                            for ($j = 2; $j <= $n; $j ++) {
                                $this->send_money($id, $receive_keys[$send_full_to + $j], $base_amount);
                                // echo "$id => ".$receive_keys[$send_full_to+$j].": ".$base_amount." \r\n";
                                // $this->send_amounts[$id] -= $base_amount;
                                // $this->receive_amounts[$receive_keys[$send_full_to+$j]] -= $base_amount;
                            }
                            $this->send_money($id, $receive_keys[$send_full_to + 1], $this->send_amounts[$id]);
                            // echo "$id => ".$receive_keys[$send_full_to+1].": ".$this->send_amounts[$id]." \r\n";
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
                echo "Send remaining $id\r\n";
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