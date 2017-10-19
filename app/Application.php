<?php
namespace App;

use Symfony\Component\Console\Application as console;
use Symfony\Component\Console\Command\Command as command;
use Pimple\Container;

class Application
{
    static $container;

    private static $_instance;

    public function run()
    {
        try {
            $this->doRun();
        } catch (common\Ex $e) {
            dump($e); // TODO format Exception && record log
        }
    }

    public function doRun()
    {
        $container = $this->getContainer();
        //config
        $this->initConfig();
        //server
        $this->initServer();
        //console
        $this->initConsole();
    }

    private function initConfig()
    {
        config\Main::init();
    }

    private function initServer()
    {
        $c = $this->getContainer();
        $c['server.ssh'] = function () {
            return new \App\server\Ssh;
        };
    }

    private function initConsole()
    {
        // instance
        $c = $this->getContainer();
        $c['console'] = function () {
            return new console;
        };
        $c['console.demo'] = function () {
            return new \App\console\Demo;
        };
        $c['console.taillog'] = function () {
            return new \App\console\Taillog;
        };
        $c['console.diy'] = function () {
            return new \App\console\Diy;
        };

        // set in console
        $console = $c['console'];
        $console->addCommands([
            $c['console.demo'],
            $c['console.taillog'],
            $c['console.diy']
        ]);

        // config console
        $console->setCatchExceptions(false);

        $console->run();
    }

    static function getContainer()
    {
        if (!self::$container) {
            self::$container = new Container;
        }
        return self::$container;
    }

    static function getInstance()
    {
        if(!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __clone()
    {

    }
}
