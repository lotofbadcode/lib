<?php

namespace lotofbadcode\phpextend\dbskeleton\mysql;

use PDO;
use Exception;

class Table
{
    /**
     * 表名
     *
     * @var string
     */
    private $_tablename;

    /**
     * 数据库引擎
     *
     * @var string
     */
    private $_engine = 'INNODB';

    /**
     * 数据库编码
     *
     * @var string
     */
    private $_charset = 'utf8mb4';

    /**
     * 备注
     *
     * @var string
     */
    private $_comment = '';

    /**
     * 数据库连接对象
     * @var PDO
     */
    private $_connection;

    public function __construct($connection)
    {
        $this->_connection = $connection;
    }
    public function create()
    {
        try {
            $sql = "CREATE TABLE `:tablename`  ENGINE=:engine DEFAULT CHARSET=:charset ROW_FORMAT=COMPACT COMMENT=':comment';";
            $stmt = $this->_connection->prepare($sql);
            $stmt->execute([
                'tablename' => $this->_tablename,
                'engine' => $this->_engine,
                'charset' => $this->_charset,
                'comment' => $this->_comment
            ]);
            return $stmt->rowCount();
        } catch (Exception $ex) {
            return false;
        }
    }


    /**
     * 设置表名
     *
     * @param string $tablename
     * @return this
     */
    public function setTablename($tablename)
    {
        $this->_tablename = $tablename;
        return $this;
    }

    /**
     * 设置引擎
     *
     * @return this
     */
    public function setEngine($engine)
    {
        $this->_engine = $engine;
        return $this;
    }

    /**
     * 设置编码
     *
     * @param string $charset
     * @return this
     */
    public function setCharset($charset)
    {
        $this->_charset = $charset;
        return $this;
    }

    /**
     * 设置备注
     *
     * @param string $comment
     * @return this
     */
    public function setComment($comment)
    {
        $this->_comment = $comment;
        return $this;
    }
}
