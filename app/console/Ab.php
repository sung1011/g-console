<?php
namespace App\console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;

class Ab extends Command
{
    protected function configure()
    {
        $this
        ->setName('ab')

        ->addArgument('cmd', InputArgument::OPTIONAL, 'input any cmd')
        ->addOption('hostname', 'hn', InputArgument::OPTIONAL, 'switch host name', 'dev')

        ->setDescription('all branch')

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

        $cmd = 'cd '. $Pathbackend . '/;';
        $cmd .= 'for e in `ls`
                do
                    f="./"$e
                    if [ -d $f ]
                    then
                        echo $e ":" `cd $e; git status | head -n 1 | awk \'{print $4}\'; `
                    else
                        echo $f > /dev/null
                    fi
                done';

        $ssh->handle($cmd, $input->getOption('hostname'));

        $output->write($ssh->stdOut());
    }
}
