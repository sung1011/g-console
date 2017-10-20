<?php
namespace App\console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;

class Diy extends Command
{
    protected function configure()
    {
        $this
        ->setName('diy')

        ->addArgument('cmd', InputArgument::REQUIRED, 'input any cmd')

        ->addOption('ssh', 'hn', InputArgument::OPTIONAL, 'switch host name', 'dev')

        ->setDescription('do it by yourself')

        ->setHelp('_')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //server
        $di = getDI();
        $ssh = $di->get('server.ssh');

        //handle
        $cmd = $input->getArgument('cmd');
        $ssh->handle($cmd, $input->getOption('ssh'));

        $output->write($ssh->stdOut());
    }
}
