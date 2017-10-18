<?php
namespace App\config;

use \App\common\Ex as ex;

class Main
{
    public static function init()
    {
        $c = getContainer();

        $files = array_diff(scandir(__DIR__), ['.', '..', basename(__FILE__)]);

        foreach ($files as $file) {
            $f = basename($file, '.php');
            $c['config.' . $f] = require __DIR__ . '/' . $file;
        }
    }

    public static function get($file, $k = null)
    {
        $c = getContainer();

        $rs = null;

        // get config
        if (!isset($c['config.' . $file])) {
            throw new ex('config_file_not_found', $file);
        } else {
            $rs = $c['config.' . $file];
        }

        // get config val
        if ($k) {
            if (!isset($c['config.' . $file][$k])) {
                throw new ex('config_key_not_found', $k);
            }else {
                $rs = $c['config.' . $file][$k];
            }
        }

        return $rs;
    }
}
