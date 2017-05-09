<?php

namespace lotofbadcode\phpextend\databackup\mysql;

use PDO;
use Exception;

if (!session_id())
{
    session_start();
}

class Backup
{

    /**
     * 服务器
     * @var string
     */
    private $_server = '127.0.0.1';

    /**
     * 数据库
     * @var string
     */
    private $_dbname = '';

    /**
     * 用户名
     * @var string
     */
    private $_username = '';

    /**
     * 密码
     * @var string
     */
    private $_password = '';

    /**
     * 分卷大小的 默认2M
     * @var int
     */
    private $_volsize = 2;

    /**
     * 备份路径
     * @var string
     */
    private $_backdir = '';

    /**
     * 表集合
     * @var array 
     */
    private $_tablelist = [];

    /**
     * 当前执行表的在$_tablelist中的键值
     * @var int 
     */
    private $_nowtableidx = 0;

    /**
     * 当前表已执行条数
     * @var int 
     */
    private $_nowtableexeccount = 0;

    /**
     * 当前表的总记录数
     * @var int 
     */
    private $_nowtabletotal = 0;

    /**
     * PDO对象
     * @var PDO 
     */
    private $_pdo;

    /**
     * 保存的文件名
     * @var string 
     */
    private $_filename = '';

    /**
     * insert Values 总条数
     * @var type 
     */
    private $_totallimit = 200;

    /**
     * 
     * @param string $server 服务器
     * @param string $dbname 数据库
     * @param string $username 账户
     * @param string $password 密码
     * @param string $code 编码
     */
    public function __construct($server, $dbname, $username, $password, $code='utf8')
    {
        $this->_server = $server;
        $this->_dbname = $dbname;
        $this->_username = $username;
        $this->_password = $password;
        $this->_pdo = new PDO('mysql:host=' . $this->_server . ';dbname=' . $this->_dbname, $this->_username, $this->_password, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES'" . $code . "';"]);
    }

    public function setvolsize($size)
    {
        $this->_volsize = $size;
        return $this;
    }

    public function settablelist($tablelist = [])
    {
        $this->_tablelist = $tablelist;
        return $this;
    }

    public function gettablelist()
    {
        if (!$this->_tablelist)
        {
            $rs = $this->_pdo->query('show table status');
            $res = $rs->fetchAll(PDO::FETCH_ASSOC);
            foreach ($res as $r)
            {
                $this->_tablelist[] = $r['Name'];
            }
        }
        return $this->_tablelist;
    }

    public function setbackdir($dir)
    {
        $this->_backdir = $dir;
        if (!is_dir($dir))
        {
            mkdir($dir, 0777);
        }
        return $this;
    }

    public function getbackdir()
    {
        return $this->_backdir;
    }

    /**
     * 设置文件名
     * @param string $filename
     * @return $this
     */
    public function setfilename($filename)
    {
        $this->_filename = $filename;
        if (!is_file($this->_backdir . '/' . $this->_filename))
        {
            fopen($this->_backdir . '/' . $this->_filename, "x+");
        }
        // return $this;
    }

    /**
     * 获取文件名
     * @return string 
     */
    public function getfilename()
    {
        if (!$this->_filename)
        {
            $this->_filename = isset($this->_tablelist[$this->_nowtableidx]) ? $this->_tablelist[$this->_nowtableidx] . '#0.sql' : '';
        }
        if (!is_file($this->_backdir . '/' . $this->_filename))
        {
            fopen($this->_backdir . '/' . $this->_filename, "x+");
        }
        return $this->_filename;
    }

    public function backup()
    {
        $totalpercentage = 100;
        $tablepercentage = 100;
        $tablelist = $this->gettablelist();
        $nexttable = $nowtable = '';
        $nexttableidx = $nowtableidx = $this->_nowtableidx;
        $nextstorefile = $nowstorefile = '';
        if (isset($tablelist[$this->_nowtableidx]))
        {
            $nexttable = $nowtable = $tablelist[$this->_nowtableidx];
            $sqlstr = '';

            if ($this->_nowtableexeccount == 0)
            {
                //Drop 建表
                $sqlstr .= 'DROP TABLE IF EXISTS `' . $nowtable . '`;' . PHP_EOL;
                $rs = $this->_pdo->query('SHOW CREATE TABLE `' . $nowtable . '`');
                $res = $rs->fetchAll();
                $sqlstr .= $res[0][1] . ';' . PHP_EOL;
                file_put_contents($this->_backdir . DIRECTORY_SEPARATOR . $this->getfilename(), file_get_contents($this->_backdir . DIRECTORY_SEPARATOR . $this->getfilename()) . $sqlstr);
            }
            if ($this->_nowtableexeccount == 0)
            {
                $this->gettabletotal($nowtable); //当前表的记录数
            }
            if ($this->_nowtableexeccount < $this->_nowtabletotal)
            {
                //建记录SQL语句
                $this->_singleinsertrecord($nowtable, $this->_nowtableexeccount);
            }
            //计算百分比
            $totalpercentage = ($this->_nowtableidx ) / count($tablelist) * 100;
            if ($this->_nowtabletotal != 0)
            {
                $tablepercentage = $this->_nowtableexeccount / $this->_nowtabletotal * 100;
            }
            $nextstorefile = $nowstorefile = $this->getfilename();
            if ($tablepercentage >= 100)
            {
                $this->_nowtableidx = $this->_nowtableidx + 1;
                $nexttableidx = $this->_nowtableidx;
                $this->_nowtableexeccount = 0;
                if (isset($tablelist[$this->_nowtableidx]))
                {
                    $nexttable = $this->_nowtableidx;
                    $nextstorefile = $tablelist[$this->_nowtableidx] . '#0.sql';
                    $this->setfilename($nextstorefile);
                }
            }
        }
        return [
            'nowtable' => $nowtable, //当前正在备份的表
            'nowtableidx' => $nowtableidx, //当前正在备份表的索引
            'nowstorefile' => $nowstorefile, //当前备份存储的文件名
            'nowtableexeccoun' => $this->_nowtableexeccount, //当前表执行条数
            'nowtabletotal' => $this->_nowtabletotal, //当前表执行总条数
            'nexttable' => $nexttable, //下一个要备份的表
            'nexttableidx' => $nexttableidx, //下一个要备份表的索引
            'nextstorefile' => $nextstorefile, //下一个要存储的文件名
            'totalpercentage' => (int) $totalpercentage, //总百分比
            'tablepercentage' => (int) $tablepercentage, //当前表百分比
        ];
    }

    public function ajaxbackup()
    {
        if (isset($_SESSION['ajaxparam']))
        {
            $ajaxparam = $_SESSION['ajaxparam'];
            $this->_nowtableidx = $ajaxparam['nexttableidx'];
            $this->setfilename($ajaxparam['nextstorefile']);
            if ($ajaxparam['tablepercentage'] >= 100)
            {
                $this->_nowtableexeccount = 0;
                $this->_nowtabletotal = 0;
            } else
            {
                $this->_nowtableexeccount = $ajaxparam['nowtableexeccoun'];
                $this->_nowtabletotal = $ajaxparam['nowtabletotal'];
            }
        }
        $result = $this->backup();

        if ($result['totalpercentage'] >= 100)
        {
            unset($_SESSION['ajaxparam']);
        } else
        {
            $_SESSION['ajaxparam'] = $result;
        }
        return $result;
    }

    public function gettabletotal($table)
    {
        $value = $this->_pdo->query('select count(*) from ' . $table);
        $counts = $value->fetchAll(PDO::FETCH_NUM);
        return $this->_nowtabletotal = $counts[0][0];
    }

    private function _singleinsertrecord($tablename, $limit)
    {
        $sql = 'select * from `' . $tablename . '` limit ' . $limit . ',' . $this->_totallimit;
        $valuers = $this->_pdo->query($sql);
        $valueres = $valuers->fetchAll(PDO::FETCH_NUM);
        $insertsqlv = '';
        $insertsql = 'insert into `' . $tablename . '` VALUES ';
        foreach ($valueres as $v)
        {
            $insertsqlv .= ' ( ';

            foreach ($v as $_v)
            {
                $insertsqlv .= "'" . $_v . "',";
            }
            $insertsqlv = rtrim($insertsqlv, ',');
            $insertsqlv .= ' ),';
        }
        $insertsql .= rtrim($insertsqlv, ',') . ' ;' . PHP_EOL;
        $this->_checkfilesize();
        file_put_contents($this->_backdir . '/' . $this->getfilename(), file_get_contents($this->_backdir . '/' . $this->getfilename()) . $insertsql);
        $this->_nowtableexeccount += $this->_totallimit;
        $this->_nowtableexeccount = $this->_nowtableexeccount >= $this->_nowtabletotal ? $this->_nowtabletotal : $this->_nowtableexeccount;
    }

    /**
     * 检查文件大小
     */
    private function _checkfilesize()
    {
        clearstatcache();
        $b = filesize($this->_backdir . '/' . $this->getfilename()) < $this->_volsize * 1024 * 1024 ? true : false;
        if ($b === false)
        {
            $filearr = explode('#', $this->getfilename());
            if (count($filearr) == 2)
            {
                $fileext = explode('.', $filearr[1]); //.sql
                $filename = $filearr[0] . '#' . ($fileext[0] + 1) . '.sql';

                $this->setfilename($filename);
            }
        }
    }

}
