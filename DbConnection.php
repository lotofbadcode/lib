<?php

namespace lotofbadcode\phpextend;

use PDO;

class DbConnection
{
    private static  $instance = null;

    public static function instance($scheme, $server, $dbname, $username, $password, $code = 'utf8')
    {
        if (self::$instance == null) {
            switch ($scheme) {
                case 'mysql':
                    return  self::$instance = new PDO($scheme . ':host=' . $server . ';dbname=' . $dbname, $username, $password, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES'" . $code . "';"]);
            }
        } else {
            return self::$instance;
        }
    }
}
