<?php
namespace App\server;

use \App\common\Ex as ex;

class Main
{
    public static function get(string $file)
    {
        $di = getDI();

        $name = 'server.' . $file;

        if (!$di->has($name)) {
            throw new ex('server_file_not_found', $file);
        }
        return $di->get($name);
    }
}
