<?php
namespace lotofbadcode\phpextend\dbskeleton\mysql;

class CreateTable
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

    public function create()
    {
        $sql = "CREATE TABLE `" . $this->_tablename . "`  ENGINE=" . $this->_engine . " DEFAULT CHARSET=" . $this->_charset . " ROW_FORMAT=COMPACT COMMENT='" . $this->_comment . "';";
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
