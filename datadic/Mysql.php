<?php

namespace lotofbadcode\phpextend\datadic;

class Dbdict
{
    /**
     * 服务器
     * @var string
     */
    private $_server = '127.0.0.1';
    /**
     * 数据库
     * @var string
     */
    private $_dbname = '';
    /**
     * 用户名
     * @var string
     */
    private $_username = '';
    /**
     * 密码
     * @var string
     */
    private $_password = '';

    /**
     * PDO对象
     * @var PDO 
     */
    private $_pdo;

    public function __construct($server, $dbname, $username, $password, $code = 'utf8')
    {
        $this->_server = $server;
        $this->_dbname = $dbname;
        $this->_username = $username;
        $this->_password = $password;
        $this->_pdo = new PDO('mysql:host=' . $this->_server . ';dbname=' . $this->_dbname, $this->_username, $this->_password, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES'" . $code . "';"]);
    }

    /**
     * 获取所有表
     */
    public function getTables()
    {
        $res = $this->_pdo->query('show table status;');
        return  $res->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 获取单张表中所有字段信息
     */
    public  function getColumns($tablename)
    {
        $res = $this->_pdo->query('show full COLUMNS from ' . $tablename . ';');
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }
}
