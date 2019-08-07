<?php

namespace lotofbadcode\phpextend\dbskeleton;

use lotofbadcode\phpextend\dbskeleton\mysql\Skeleton as MysqlSkeleton;
use PDO;

class Factory
{
    private static  $instance = null;

    public static function instance($scheme, $server, $dbname, $username, $password, $code = 'utf8')
    {
        $args = md5(implode('_', func_get_args()));
        if (self::$instance[$args] == null) {
            switch ($scheme) {
                case 'mysql':
                    $pdo =  new PDO($scheme . ':host=' . $server . ';dbname=' . $dbname, $username, $password, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES'" . $code . "';"]);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    self::$instance[$args] = new MysqlSkeleton($pdo);
            }
        }
        return self::$instance[$args];
    }
}
