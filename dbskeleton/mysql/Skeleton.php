<?php

namespace lotofbadcode\phpextend\dbskeleton\mysql;

use PDO;
use Exception;
use lotofbadcode\phpextend\dbskeleton\ISkeleton;

class Skeleton implements ISkeleton
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


    private $_columns = [];
    /**
     * 数据库连接对象
     * @var PDO
     */
    private $_connection;

    public function __construct($connection)
    {
        $this->_connection = $connection;
    }

    /**
     * 创建表
     */
    public function createTable()
    {
        try {
            if (!$this->_columns) {
                throw new Exception('字段不能为空');
            }
            $columnssql = ' ( ';
            foreach ($this->_columns as $_columns) {
                $columnssql .= $_columns->Generate();
            }
            $columnssql .= ' ) ';
            $sql = "CREATE TABLE `" . $this->_tablename . "` " . $columnssql . "  ENGINE=" . $this->_engine . " DEFAULT CHARSET=" . $this->_charset . " ROW_FORMAT=COMPACT COMMENT='" . $this->_comment . "';";
            $stmt = $this->_connection->prepare($sql);
            $stmt->execute();
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function dropTable()
    {
        try { 

        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
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

    public function setColumn($columns)
    {
        $this->_columns = $columns;
        return $this;
    }
}
