<?php
namespace App\console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;

class Test extends Command
{
    protected function configure()
    {
        $this
        ->setName('test')

        ->setDescription('do it by yourself')

        ->setHelp('_')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
