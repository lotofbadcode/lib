<?php

namespace lotofbadcode\phpextend\dbskeleton\mysql;

use ArrayAccess;

/**
 * 字段生成
 */
class ColumnModel
{

    /**
     * 字段名
     */
    public $name = '';

    /**
     * 字段类型
     */
    public $type = '';

    /**
     * 字段长度
     */
    public $len = 0;

    /**
     * 描述备注
     */
    public $comment = '';

    /**
     * 是否主键
     */
    public $ispk = false;

    /**
     * 是否可以为空
     */
    public $isnull = true;
    /**
     * 是否自增长
     */
    public $increment = false;

    /**
     * 默认值
     */
    public $defaultval = '';

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setIsPk($ispk)
    {
        $this->ispk = $ispk;
        return $this;
    }

    public function setLen($len)
    {
        $this->len = $len;
        return $this;
    }

    /**
     * 设置描述
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    public function setIncrement($increment)
    {
        $this->increment = $increment;
        return $this;
    }

    public function setIsnull($isnull)
    {
        $this->isnull = $isnull;
        return $this;
    }

    public function setDefaultval($value)
    {
        $this->defaultval = $value;
        return $this;
    }

    /**
     * 生成字段Sql
     */
    public function Generate()
    {
        $columnsql = ' `' . $this->name . '` ';
        if ($this->type) {
            $columnsql .= $this->type;
        }
        if ($this->len) {
            $columnsql .= ' (' . $this->len . ') ';
        }
        if ($this->increment) {
            $columnsql .= ' AUTO_INCREMENT ';
        }
        if ($this->isnull == false) { //不允许空
            $columnsql .= ' NOT NULL ';
        }
        if ($this->defaultval != '') { //是否有默认值
            $columnsql .= ' DEFAULT  ' . $this->defaultval . ' ';
        }
        $columnsql .= "COMMENT '" . $this->comment . "' ";
        if ($this->ispk) {
            $columnsql .= ' , PRIMARY KEY (`' . $this->name . '`) ';
        }
        return $columnsql;
    }
}
