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
            if (count($columnModels) == 0) {
                throw new Exception('创建表时，必须包含一个列');
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

    /**
     * 修改表
     */
    public function alterTable(TableModel $oldtableModel, TableModel $newtableModel)
    {
        try {
            $sql = 'ALTER TABLE ' . $oldtableModel->tablename . ' RENAME ' . $newtableModel->tablename;
            $stmt = $this->_connection->prepare($sql);
            $stmt->execute();
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * 删除表
     */
    public function dropTable(TableModel $tableModel)
    {
        try {
            $sql = 'DROP TABLE ' . $tableModel->tablename . ';';
            $stmt = $this->_connection->prepare($sql);
            $stmt->execute();
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * 添加字段
     */
    public function addColumn(TableModel $tableModel, ColumnModel $columnModel)
    {
        //alter table table1 add transactor varchar(10) not Null;
        try {
            $sql = 'alter table ' . $tableModel->tablename . ' add ' . $columnModel->Generate();
            $stmt = $this->_connection->prepare($sql);
            $stmt->execute();
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * 修改字段
     */
    public function changeColumn(TableModel $tableModel, ColumnModel $oldcolumnModel, ColumnModel $newcolumnModel)
    {
        try {
            $sql = '';
            if ($oldcolumnModel->name != $newcolumnModel->name) { //修改字段名
                $sql = 'alter table ' . $tableModel->tablename . ' change ' . $oldcolumnModel->name . ' ' . $newcolumnModel->Generate();
            } else { //修改其它
                $sql = 'ALTER TABLE ' . $tableModel->tablename . ' MODIFY COLUMN ' . $newcolumnModel->Generate();
            }
            $stmt = $this->_connection->prepare($sql);
            $stmt->execute();
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * 删除字段
     */
    public function dropColumn(TableModel $tableModel, ColumnModel $columnModel)
    {
        try {
            $sql = 'ALTER TABLE ' . $tableModel->tablename . ' DROP COLUMN ' . $columnModel->name . ';';
            $stmt = $this->_connection->prepare($sql);
            $stmt->execute();
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}
