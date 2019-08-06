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
    public function alterTable(TableModel $oldtableModel, TableModel $newtableModel);

    /**
     * 删除表
     */
    public function dropTable(TableModel $tableModel);

    /**
     * 添加字段
     */
    public function addColumn(TableModel $tableModel, ColumnModel $columnModel);

    /**
     * 修改字段
     */
    public function changeColumn(TableModel $tableModel, ColumnModel $oldcolumnModel, ColumnModel $newcolumnModel);

    /**
     * 删除字段
     */
    public function dropColumn(TableModel $tableModel, ColumnModel $columnModel);
}
