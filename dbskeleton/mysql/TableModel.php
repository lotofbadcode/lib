<?php

namespace lotofbadcode\phpextend\dbskeleton\mysql;

class TableModel
{
    /**
     * 表名
     *
     * @var string
     */
    public $tablename;

    /**
     * 数据库引擎
     *
     * @var string
     */
    public $engine = 'INNODB';

    /**
     * 数据库编码
     *
     * @var string
     */
    public $harset = 'utf8mb4';

    /**
     * 备注
     *
     * @var string
     */
    public $comment = '';

    /**
     * 设置表名
     *
     * @param string $tablename
     * @return this
     */
    public function setTablename($tablename)
    {
        $this->tablename = $tablename;
        return $this;
    }

    /**
     * 设置引擎
     *
     * @return this
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;
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
        $this->charset = $charset;
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
        $this->comment = $comment;
        return $this;
    }

}
