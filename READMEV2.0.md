

**1.lotofbadcode\phpextend\databackup\mysql**

是mysql数据库备份恢复的类库 **支持AJAX 支持进度条 支持文件分卷**

demo地址： https://github.com/lotofbadcode/phpextenddemo/V2.0

1.数据库备份和恢复的使用方法：
a.备份 

  不使用AJAX
  ```php (type)
   use phpspirit\databackup\BackupFactory;
//自行判断文件夹
$backupdir = '';
if (isset($_POST['backdir']) && $_POST['backdir'] != '') {
    $backupdir = $_POST['backdir'];
} else {
    $backupdir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . date('Ymdhis');
}

if (!is_dir($backupdir)) {
    mkdir($backupdir, 0777, true);
}
$backup = BackupFactory::instance('mysql', '127.0.0.1:3306', 'test', 'root', 'root');
$backup->setbackdir($backupdir) //设置备份目录
    ->setvolsize(0.2); //设置分卷大小
do {
    $result = $backup->backup();
    echo str_repeat(' ', 1000); //这里会把浏览器缓存装满
    ob_flush(); 
    flush(); 
    if ($result['totalpercentage'] > 0) {
        echo '完成' . $result['totalpercentage'] . '%<br />';
    }
} while ($result['totalpercentage'] < 100);
  ```
  
  使用AJAX
  
  ```php (type)
use phpspirit\databackup\BackupFactory;
//自行判断文件夹
$backupdir = '';
if (isset($_POST['backdir']) && $_POST['backdir'] != '') {
    $backupdir = $_POST['backdir'];
} else {
    $backupdir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . date('Ymdhis');
}
if (!is_dir($backupdir)) {
    mkdir($backupdir, 0777, true);
}
$backup = BackupFactory::instance('mysql', '127.0.0.1:3306', 'test', 'root', 'root');
$result = $backup->setbackdir($backupdir)
    ->setvolsize(0.2)
    ->ajaxbackup($_POST);

echo json_encode($result);
  ```
 


b.恢复 

  不使用AJAX
   ```php (type)
    use phpspirit\databackup\RecoveryFactory;
$recovery = RecoveryFactory::instance('mysql', '127.0.0.1:3306', 'test', 'root', 'root');
$recovery->setSqlfiledir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'backup'.DIRECTORY_SEPARATOR.'20191205010418');
do
{
    $result = $recovery->recovery();
    echo str_repeat(' ', 1000); 
    ob_flush(); 
    flush(); 
    if ($result['totalpercentage'] > 0)
    {
        echo '完成' . $result['totalpercentage'] . '%<br />';
    }
} while ($result['totalpercentage'] < 100);

  ```
  使用AJAX
   ```php (type)
    <?php
use phpspirit\databackup\RecoveryFactory;
$recovery = RecoveryFactory::instance('mysql', '127.0.0.1:3306', 'test', 'root', 'root');
$result = $recovery->setSqlfiledir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'backup'.DIRECTORY_SEPARATOR.'20191205010418')
        ->ajaxrecovery($_POST);
echo json_encode($result);
  ```

1.数据库骨架：
```
  使用AJAX
   ```php (type)
    <?php

use lotofbadcode\phpextend\dbskeleton\Factory;
use lotofbadcode\phpextend\dbskeleton\mysql\ColumnModel;
use lotofbadcode\phpextend\dbskeleton\mysql\TableModel;
$dbskeleton = Factory::instance('mysql', '127.0.0.1:3306', 'qjfsonar', 'root', 'root');
$tablemodel = new TableModel();
$tablemodel->setCharset('utf8mb4')
    ->setEngine('InnoDB')
    ->setTablename('test')
    ->setComment('测试表');
$fieldmodel = new ColumnModel();
$fieldmodel->setType('int')
    ->setLen(11)
    ->setName('id')
    ->setIsPk(true)
    ->setIncrement(true)
    ->setComment('自增长');
//创建一个表 第一个参数 表模型 第二个参数 字段模型数组 
$dbskeleton->createTable($tablemodel, [$fieldmodel]);
/**
 *  alterTable(旧表模型，新表模型) 修改表
 *  dropTable(表模型) 删除表
 */
$fieldtitlemodel = new ColumnModel();
$fieldtitlemodel->setType('varchar')
    ->setLen(255)
    ->setName('title')
    ->setComment('标题');
//添加字段 第一个参数 表模型 第二个参数 字段模型
$dbskeleton->addColumn($tablemodel, $fieldtitlemodel);
/**
 *  changeColumn(TableModel $tableModel, ColumnModel $oldcolumnModel, ColumnModel $newcolumnModel) 修改字段
 *  dropColumn(TableModel $tableModel, ColumnModel $columnModel)  删除字段
 */
  ```
