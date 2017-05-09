<?php

namespace lotofbadcode\phpextend\databackup\mysql;

use PDO;
use Exception;

if (!session_id())
{
    session_start();
}

class Recovery
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
     * SQL文件所在的目录
     * @var string 
     */
    private $_sqlfiledir = '';

    /**
     * SQL文件数组
     * @var array 
     */
    private $_sqlfilesarr = [];

    /**
     * 当前恢复文件数组的索引
     * @var int 
     */
    private $_nowfileidx = 0;

    /**
     * 下一个恢复的文件
     * @var int 
     */
    private $_nextfileidx = 0;

    /**
     * 
     * @param string $server 服务器
     * @param string $dbname 数据库
     * @param string $username 账户
     * @param string $password 密码
     * @param string $code 编码
     */
    public function __construct($server, $dbname, $username, $password, $code)
    {
        $this->_server = $server;
        $this->_dbname = $dbname;
        $this->_username = $username;
        $this->_password = $password;
        $this->_pdo = new PDO('mysql:host=' . $this->_server . ';dbname=' . $this->_dbname, $this->_username, $this->_password, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES'" . $code . "';"]);
    }

    public function setSqlfiledir($dir)
    {
        $this->_sqlfiledir = $dir;
        return $this;
    }

    public function getfiles()
    {
        if (!$this->_sqlfilesarr)
        {
            $dir = $this->_sqlfiledir;
            $iterator = new \DirectoryIterator($dir);
            $filesarr = [];
            foreach ($iterator as $it)
            {
                if (!$it->isDot())
                {
                    $filenameinfo = explode('#', $it->getFilename());
                    $fileext = explode('.', $filenameinfo[1]);
                    $filesarr[$filenameinfo[0]][$fileext[0]] = $it->getFilename();
                }
            }
            ksort($filesarr);
            foreach ($filesarr as $k => $f)
            {
                ksort($f);
                $filesarr[$k] = $f;
            }
            foreach ($filesarr as $f)
            {
                foreach ($f as $_f)
                {
                    $this->_sqlfilesarr[] = $_f;
                }
            }
        }
        return $this->_sqlfilesarr;
    }

    public function recovery()
    {
        try
        {
            $filesarr = $this->getfiles();
            $totalpercentage = 100;
            $this->_nowfileidx = $this->_nextfileidx;
            if (isset($filesarr[$this->_nowfileidx]))
            {
                $this->_importsqlfile($this->_sqlfiledir . DIRECTORY_SEPARATOR . $filesarr[$this->_nowfileidx]);
                $totalpercentage = $this->_nowfileidx / count($this->_sqlfilesarr) * 100;
                $this->_nextfileidx = $this->_nowfileidx + 1;
            }
            return [
                'nowfileidex' => $this->_nowfileidx, //当前正在恢复的文件
                'nextfileidx' => $this->_nextfileidx,
                'totalpercentage' => (int) $totalpercentage, //总百分比
            ];
        } catch (Exception $ex)
        {
            throw $ex;
        }
    }

    public function ajaxrecovery()
    {
        if (isset($_SESSION['ajaxparam']))
        {
            $ajaxparam = $_SESSION['ajaxparam'];
            $this->_nowfileidx = $ajaxparam['nowfileidex'];
            $this->_nextfileidx = $ajaxparam['nextfileidx'];
        }
        $result = $this->recovery();
        if ($result['totalpercentage'] >= 100)
        {
            unset($_SESSION['ajaxparam']);
        } else
        {
            $_SESSION['ajaxparam'] = $result;
        }
        return $result;
    }

    private function _importsqlfile($sqlfile)
    {
        if (is_file($sqlfile))
        {
            try
            {
                $content = file_get_contents($sqlfile);
                $arr = explode(';' . PHP_EOL, $content);
                foreach ($arr as $a)
                {
                    if (trim($a) != '')
                    {
                        $this->_pdo->exec($a);
                    }
                }
            } catch (Exception $ex)
            {
                return false;
            }
        }
        return true;
    }

}
