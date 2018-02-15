<?php

namespace App\common;

class Ex extends \Exception
{
    protected $data;

    public function __construct(string $msg, $extraMsg = '')
    {
        if (empty($extraMsg)) {
            $this->data = '';
        } else {
            $this->data = $extraMsg;
        }

        parent::__construct($msg, $this->getErrCode($msg));
    }

    private function getErrCode($msg)
    {
        return getDI()->get('config.errcode')[$msg] ?? 0 ;
    }
}
