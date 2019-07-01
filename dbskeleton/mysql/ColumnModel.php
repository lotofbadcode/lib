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
    private $_name = '';

    /**
     * 字段类型
     */
    private $_type = '';

    /**
     * 字段长度
     */
    private $_len = 0;

    /**
     * 描述备注
     */
    private $_comment = '';

    /**
     * 是否主键
     */
    private $_ispk = false;

    /**
     * 是否可以为空
     */
    private $_isnull = true;
    /**
     * 是否自增长
     */
    private $_increment = false;

    /**
     * 默认值
     */
    private $_defaultval = '';

    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    public function setIsPk($ispk)
    {
        $this->_ispk = $ispk;
        return $this;
    }

    public function setLen($len)
    {
        $this->_len = $len;
        return $this;
    }

    /**
     * 设置描述
     */
    public function setComment($comment)
    {
        $this->_comment = $comment;
        return $this;
    }

    public function setIncrement($increment)
    {
        $this->_increment = $increment;
        return $this;
    }

    public function setIsnull($isnull)
    {
        $this->_isnull = $isnull;
        return $this;
    }

    public function setDefaultval($value)
    {
        $this->_defaultval = $value;
        return $this;
    }

    /**
     * 生成字段Sql
     */
    public function Generate()
    {
        $columnsql = ' `' . $this->_name . '` ' . $this->_type . '(' . $this->_len . ') ';
        if ($this->_increment) {
            $columnsql .= ' AUTO_INCREMENT ';
        }
        if ($this->_isnull == false) { //不允许空
            $columnsql .= ' NOT NULL ';
        }
        if ($this->_defaultval != '') { //是否有默认值
            $columnsql .= ' DEFAULT  ' . $this->_defaultval . ' ';
        }
        $columnsql .= "COMMENT '" . $this->_comment . "', ";
        if ($this->_ispk) {
            $columnsql .= ' PRIMARY KEY (`' . $this->_name . '`) ';
        }
        return $columnsql;
    }

}
