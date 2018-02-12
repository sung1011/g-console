<?php
namespace App;

// use Pimple\Container; // pimple is too simple and not grace, Symfony/Dependency-Injection is better ! abandon it(pimple) !    T.T
// use Symfony\Component\Console\Application as Symfonyconsole;
use Symfony\Component\DependencyInjection\ContainerBuilder as Di;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\Console\CommandLoader\FactoryCommmandLoader;
use Symfony\Component\Yaml\Yaml;

class Application
{
    public static $container;

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
        $this->load();
        //console
        $this->initConsole();
    }

    private function load()
    {
        $di = getDI();
        //config
        $di->set('config.ssh', Yaml::parse(file_get_contents(__DIR__ . '/config/ssh.yml')));
        $di->set('config.const', Yaml::parse(file_get_contents(__DIR__ . '/config/const.yml')));
        $di->set('config.errcode', Yaml::parse(file_get_contents(__DIR__ . '/config/errcode.yml')));
        //service
        $loader = new YamlFileLoader($di, new FileLocator(__DIR__ . '/config'));
        $loader->load('di.yml');
    }

    private function initConsole()
    {
        $di = getDI();
        // set in console
        $console = $di->get('console');

        $console->addCommands($this->getServiceByPre('console.'));

        // config console
        $console->setCatchExceptions(false);

        $console->run();
    }

    private function getServiceIdsByPre($prefix)
    {
        $di = getDI();
        return array_filter($di->getServiceIds(), function ($e) use ($prefix) {
            if (strpos($e, $prefix) === 0) {
                return $e;
            }
        });
    }

    private function getServiceByPre($prefix)
    {
        $ids = $this->getServiceIdsByPre($prefix);
        $di = getDI();
        return array_map(function ($id) use ($di) {
            return $di->get($id);
        }, $ids);
    }

    public static function getDI()
    {
        if (!self::$container) {
            self::$container = new Di();
        }
        return self::$container;
    }

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __clone()
    {
    }
}
