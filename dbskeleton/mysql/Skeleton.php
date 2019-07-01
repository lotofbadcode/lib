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
    public function alterTable()
    { }

    /**
     * 删除表
     */
    public function dropTable()
    { }

    /**
     * 添加字段
     */
    public function addColumn()
    { }

    /**
     * 修改字段
     */
    public function changeColumn()
    { }

    /**
     * 删除字段
     */
    public function dropColumn()
    {
        throw new Exception('123');
    }
}