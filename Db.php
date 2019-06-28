<?php

namespace lotofbadcode\phpextend;

use PDO;

class Db
{
    /**
     * 
     * @param string $scheme 数据库类型
     * @param string $server 服务器
     * @param string $dbname 数据库
     * @param string $username 账户
     * @param string $password 密码
     * @param string $code 编码
     */
    public function __construct($scheme, $server, $dbname, $username, $password, $code = 'utf8')
    {
        switch ($scheme) {
            case 'mysql':
                return new PDO($scheme . ':host=' . $server . ';dbname=' . $dbname, $username, $password, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES'" . $code . "';"]);
        }
    }
}
