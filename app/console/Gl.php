<?php
namespace App\console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;

class Gl extends Command
{
    protected function configure()
    {
        $this
        ->setName('gl')

        ->addOption('hostname', 'hn', InputArgument::OPTIONAL, 'switch host name', 'dev')
        ->addOption('branch', 'b', InputArgument::OPTIONAL, 'swap branch', 'dev')

        ->setDescription('git pull')

        ->setHelp('_')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //server
        $di = getDI();
        $ssh = $di->get('server.ssh');

        $conf = $di->get('config.const');

        $Pathbackend = $conf['path']['master-dev']['backend'];

        $cmd = 'cd '. $Pathbackend . '/' . $input->getOption("branch") . ';';//cd
        $cmd .= 'git pull;';//git pull

        $ssh->handle($cmd, $input->getOption('hostname'));

        $output->write($ssh->stdOut());
    }
}
