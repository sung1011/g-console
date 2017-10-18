<?php
namespace App\server;

use \App\common\Ex as ex;

class Main
{
    public static function get(string $file)
    {
        $c = getContainer();

        if (!isset($c['server.' . $file])) {
            throw new ex('server_file_not_found', $file);
        }
        return $c['server.' . $file];
    }
}
