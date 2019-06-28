<?php

namespace lotofbadcode\phpextend\dbskeleton;

use lotofbadcode\phpextend\dbskeleton\mysql\Skeleton as MysqlSkeleton;

class Factory
{
    private static  $instance = null;

    public static function instance($scheme, $server, $dbname, $username, $password, $code = 'utf8')
    {
        if (self::$instance[$scheme] == null) {
            switch ($scheme) {
                case 'mysql':
                    self::$instance[$scheme] = new PDO($scheme . ':host=' . $server . ';dbname=' . $dbname, $username, $password, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES'" . $code . "';"]);
            }
        } else {
            self::$instance[$scheme];
        }

        switch ($scheme) {
            case 'mysql':
                return new MysqlSkeleton(self::$instance[$scheme]);
        }
    }
}
