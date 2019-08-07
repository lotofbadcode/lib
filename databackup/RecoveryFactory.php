<?php

namespace lotofbadcode\phpextend\databackup;

use lotofbadcode\phpextend\databackup\mysql\Recovery;

class RecoveryFactory
{
    private static  $instance = null;

    public static function instance($scheme, $server, $dbname, $username, $password, $code = 'utf8')
    {
        $args = md5(implode('_', func_get_args()));
        if (self::$instance[$args] == null) {
            switch ($scheme) {
                case 'mysql':
                    $pdo =  new PDO($scheme . ':host=' . $server . ';dbname=' . $dbname, $username, $password, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES'" . $code . "';"]);
                    self::$instance[$args] = new Recovery($pdo);
            }
        }
        return self::$instance[$args];
    }
}
