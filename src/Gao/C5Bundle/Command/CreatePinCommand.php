<?php
namespace Gao\C5Bundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CreatePinCommand
 * Name class must have suffix 'Command'
 * @author Do Viet Kien
 *
 */
class CreatePinCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('c5:create-pin')

            // the short description shown while running "php app/console list"
            ->setDescription('Create pin for use.')

            // configure arguments
            ->addArgument('number', InputArgument::REQUIRED, 'The number of pin will be generated.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            echo "========START========", PHP_EOL;

            $number = $input->getArgument('number');
            $number = 0 + $number;
            while ($number > 0) {
                $this->getContainer()->get('automation_service')->createPin();
                $number -= 1;
            }

        } catch (\Exception $ex) {
            echo "[ERROR]", $ex->getMessage(), PHP_EOL;
        } finally {
            echo "========FINISH=======", PHP_EOL;
        }
    }
}