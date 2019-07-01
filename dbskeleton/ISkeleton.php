<?php

namespace lotofbadcode\phpextend\dbskeleton;

interface ISkeleton
{
    /**
     * 创建表
     * @param $tableModel TableModel
     * @param $columnModels
     */
    public function createTable($tableModel,  array $columnModels);

    /**
     * 修改表
     */
    public function alterTable();

    /**
     * 删除表
     */
    public function dropTable();

    /**
     * 添加字段
     */
    public function addColumn();

    /**
     * 修改字段
     */
    public function changeColumn();

    /**
     * 删除字段
     */
    public function dropColumn();
}
