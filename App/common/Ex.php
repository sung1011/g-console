<?php

namespace App\common;

class Ex extends \Exception
{
    protected $data;

    function __construct(string $msg, string $extraMsg = '')
    {
        $this->data = $extraMsg;
        
        parent::__construct($msg, $this->getErrCode($msg));
    }

    private function getErrCode($msg)
    {
        $c = getContainer();
        return $c['config.errcode'][$msg] ?? 0 ;
    }
}
