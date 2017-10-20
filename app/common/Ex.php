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
        return getDI()->get('config.errcode')[$msg] ?? 0 ;
    }
}
