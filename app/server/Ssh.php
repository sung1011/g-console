<?php
namespace App\server;

use \App\config\Main as conf;

/**
* php ssh server类
* require 需要安装ssh2拓展
*/
class Ssh
{
    private $_conn;
    private $_stream;
    private $_errorStream;
    private $_cmd;

    protected $_stdOut;
    protected $_stdErr;

    private $_host;
    private $_user;
    private $_pwd;
    private $_port;

    private function getConf($key)
    {
        $conf = conf::get('ssh', $key);

        //TODO chk:host, user, pwd, port

        $this->_host = $conf['host'];
        $this->_user = $conf['user'];
        $this->_pwd = $conf['pwd'];
        $this->_port = $conf['port'];
    }

    private function getConn($key)
    {
        $this->getConf($key);

        if (!$this->_conn) {
            $conn = ssh2_connect($this->_host, $this->_port);
            ssh2_auth_password($conn, $this->_user, $this->_pwd);
            $this->_conn = $conn;
        }
        return $this->_conn;
    }

    public function handle($cmd, $connKey)
    {
        $conn = $this->getConn($connKey);
        $this->_stream = ssh2_exec($conn, $cmd);
        $this->_errorStream = ssh2_fetch_stream($this->_stream, SSH2_STREAM_STDERR);
        stream_set_blocking($this->_stream, true);
        stream_set_blocking($this->_errorStream, true);

        $this->_stdOut .= stream_get_contents($this->_stream);
        $this->_stdErr .= stream_get_contents($this->_errorStream);

        if ($this->_errorStream) {
            fclose($this->_errorStream);
        }
        if ($this->_stream) {
            fclose($this->_stream);
        }

        if(DEBUG) {
            $this->stdAppend($cmd);
        }
    }

    private function stdAppend($str)
    {
        $this->_stdOut .= '--------------------' . PHP_EOL . $str;
        $this->_stdErr .= '--------------------' . PHP_EOL . $str;
    }

    public function stdOut($showCmd = false)
    {
        return $this->_stdOut;
    }

    public function stdErr()
    {
        return $this->_stdErr;
    }

    // public function __call($func, $param)
    // {
    // }
}
