<?php

namespace lotofbadcode\phpextend\dbskeleton\mysql;

use PDO;
use Exception;
use lotofbadcode\phpextend\dbskeleton\ISkeleton;

class Skeleton implements ISkeleton
{

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
    public function createTable(TableModel $tableModel,  array $columnModels)
    {
        try {
            if (!$this->_columns) {
                throw new Exception('字段不能为空');
            }
            $columnssql = ' ( ';
            foreach ($columnModels as $columnModel) {
                $columnssql .= $columnModel->Generate();
            }
            $columnssql .= ' ) ';
            $sql = "CREATE TABLE `" . $tableModel->tablename . "` " . $columnssql . "  ENGINE=" . $tableModel->engine . " DEFAULT CHARSET=" . $tableModel->charset . " ROW_FORMAT=COMPACT COMMENT='" . $tableModel->comment . "';";
            $stmt = $this->_connection->prepare($sql);
            $stmt->execute();
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function dropTable()
    {
        try { } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}
