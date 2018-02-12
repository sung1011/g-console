<?php
namespace App\console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;

class Online extends Command
{
    protected function configure()
    {
        $this
        ->setName('ol')

        ->addOption('hostname', 'hn', InputArgument::OPTIONAL, 'switch host name', 'online')
        ->addOption('branch', 'b', InputArgument::OPTIONAL, 'swap branch', 'dev')

        ->setDescription('online op')

        ->setHelp('_')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //server
        $di = getDI();
        $ssh = $di->get('server.ssh');

        $conf = $di->get('config.const');

        //TODO

        // board
        // channel
        // section
        // server


        $output->write($ssh->stdOut());
    }

    private function board($ssh, $input)
    {
        $cmd = 'go';
        $ssh->handle($cmd, $input->getOption('hostname'));
    }
}
