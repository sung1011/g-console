<?php
namespace App\console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;

class Taillog extends Command
{
    private $_lineOneRow = 1;

    protected function configure()
    {
        $this
        ->setName('taillog')

        ->addOption('hostname', 'hn', InputArgument::OPTIONAL, 'switch host name', 'dev')
        ->addOption('num', 'rn', InputArgument::OPTIONAL, 'show row num', 5)
        ->addOption('grep', 'g', InputArgument::OPTIONAL, 'grep key in [id, optype, errorCode, errorMessage, type, file, line, level, rid, sec, time, version, trace, request, systemStatus, userData, errorData]')
        ->addOption('type', 't', InputArgument::OPTIONAL, 'specify type of log', 'error')
        ->addOption('subtype', 'st', InputArgument::OPTIONAL, 'specify sybtype of log', 'game')
        ->addOption('date', 'd', InputArgument::OPTIONAL, 'show date of log', date('Ymd'))

        ->setDescription('tail log by ssh')

        ->setHelp('_')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // get file path
        switch ($input->getOption('type')) {
            case 'error': $date = date('Ymd', strtotime($input->getOption('date')));
                $file = "~/log/error/{$date}/{$input->getOption('subtype')}/error.log";
                $this->_lineOneRow = 17;
            break;
            case 'phpfpm':
                $file = "~/log/phpfpm/php-fpm.log";
                $this->_lineOneRow = 1;
            break;
        }
        // tail log
        $cmd = "tail";
        $cmd .= " -n " . $this->getShowLine($input);
        $cmd .= " " . $file;
        if ($input->getOption('grep')) {
            $cmd .= " | grep '" . $input->getOption('grep') . "'";
        }

        $ssh = \App\server\Main::get('ssh');
        $ssh->handle($cmd, $input->getOption('hostname'));

        $output->write($ssh->stdOut());
    }

    private function getShowLine($input)
    {
        return $input->getOption('num') * $this->_lineOneRow;
    }
}
