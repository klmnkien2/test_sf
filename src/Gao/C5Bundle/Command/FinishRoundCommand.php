<?php
namespace Gao\C5Bundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * FinishRoundCommand
 * Name class must have suffix 'Command'
 * @author Do Viet Kien
 *
 */
class FinishRoundCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('c5:finish-round')

            // the short description shown while running "php app/console list"
            ->setDescription('Finish round. All unfinish transaction will be noticed and banned.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        echo "========START========", PHP_EOL;
        $this->getContainer()->get('automation_service')->finishRound();
        echo "========FINISH=======", PHP_EOL;
    }

}