<?php
namespace App\console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;

class Catol extends Command
{
    private $_rid;
    private $_sdk;
    private $_sec;
    private $_url;

    protected function configure()
    {
        $this
        ->setName('catol')

        // ->addArgument('sdk', InputArgument::REQUIRED, 'sdk source')
        ->addArgument('rid', InputArgument::REQUIRED, 'role id')
        ->addArgument('field', InputArgument::OPTIONAL, 'user field')

        ->setDescription('cat master online user data')

        ->setHelp('_')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->register($input);

        $this->_url = $this->_const['url']['master-online'] . "/index.php?&mod=web&method=Forward\\User.get&rid=$this->_rid&sec=$this->_sec&sdk_source=$this->_sdk";

        $ret = $this->doCurl();

        $output->write($ret);
    }

    private function register($input)
    {
        $this->_const = getDI()->get('config.const');
        $this->_rid = $input->getArgument('rid');
        $this->ridToSection();
        $this->ridToSDKSource();
    }

    private function ridToSDKSource()
    {
        $sdkMap = $this->_const['master-sdk'];
        foreach ($sdkMap as $sdk => $v) {
            if ($this->_sec >= $v[0] && $this->_sec <= $v[1]) {
                $this->_sdk =  'ios';
                break;
            }
        }
        if (empty($this->_sdk)) {
            throw new \App\common\Ex('param_sdk_no_exist');
        }
    }

    private function ridToSection()
    {
        $this->_sec = explode('_', $this->_rid)[1];
    }

    private function doCurl()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);

        curl_close($ch);
        if ($output === false || $info['http_code'] != 200) {
            $output = "No cURL data returned for $this->_url [". $info['http_code']. "]";
            if (curl_error($ch)) {
                $output .= "\n". curl_error($ch);
            }
            throw new \App\common\Ex('curl_response_err', $output);
        }
        return $output;
    }

    public function __get($k)
    {
    }
}
