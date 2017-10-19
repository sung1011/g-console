<?php
namespace App;

// use Pimple\Container; // pimple is too simple and not grace, Symfony/Dependency-Injection is better ! abandon it(pimple) !    T.T
use Symfony\Component\Console\Application as Symfonyconsole;
use Symfony\Component\DependencyInjection\ContainerBuilder as Di;

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
        $di = $this->getDI();
        $di->register('server.ssh', 'App\server\Ssh');
    }

    private function initConsole()
    {
        //instance
        $di = $this->getDI();
        $di->register('console', 'Symfony\Component\Console\Application');
        $di->register('console.test', '\App\console\Test');
        $di->register('console.demo', '\App\console\Demo');
        $di->register('console.taillog', '\App\console\Taillog');
        $di->register('console.diy', '\App\console\Diy');

        // set in console
        $console = $di->get('console');
        $console->addCommands([
            $di->get('console.test'),
            $di->get('console.demo'),
            $di->get('console.diy'),
            $di->get('console.taillog'),
        ]);


        // config console
        $console->setCatchExceptions(false);

        $console->run();
    }

    static function getDI()
    {
        if (!self::$container) {
            self::$container = new Di;
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
