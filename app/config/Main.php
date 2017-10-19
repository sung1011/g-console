<?php
namespace App\config;

use \App\common\Ex as ex;

class Main
{
    public static function init()
    {
        $di = getDI();

        $files = array_diff(scandir(__DIR__), ['.', '..', basename(__FILE__)]);

        foreach ($files as $file) {
            $f = basename($file, '.php');
            $di->set('config.' . $f, require __DIR__ . '/' . $file);
        }
    }

    public static function get($file, $k = null)
    {
        $di = getDI();

        $name = 'config.' . $file;
        $rs = null;

        // get config
        if (!$di->has($name)) {
            throw new ex('config_file_not_found', $file);
        } else {
            $rs = $di->get($name);
        }

        // get config val
        if ($k) {
            if (!isset($di->get($name)[$k])) {
                throw new ex('config_key_not_found', $k);
            }else {
                $rs = $di->get($name)[$k];
            }
        }

        return $rs;
    }
}
