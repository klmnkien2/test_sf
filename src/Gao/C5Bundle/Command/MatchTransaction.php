<?php
namespace Gao\C5Bundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MatchTransaction extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('match-tran')

            // the short description shown while running "php app/console list"
            ->setDescription('Automate match transaction for pd and gd.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("Automate match transaction for pd and gd.")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        if (!$dialog->askConfirmation($output, '<question>Do you confirm spamming our users?</question>', false)) {
            return;
        }    
        $output->writeln('<comment>Starting Newsletter process</comment>');       
        $output->writeln('<info>Newsletter process ended succesfully</info>');
    }
}