
**php 扩展类库 后面有好的类库会慢慢添加**

**1.lotofbadcode\phpextend\databackup\mysql**

是mysql数据库备份恢复的类库 **支持AJAX 支持进度条 支持文件分卷**

demo地址： https://github.com/lotofbadcode/phpextenddemo

使用方法：
a.备份 

  不使用AJAX
  ```php (type)
    <?php
$backup = new \lotofbadcode\phpextend\databackup\mysql\Backup('127.0.0.1:3306', 'test', 'root', '');
$backup->setbackdir($backupdir)
        ->setvolsize(0.2);
do
{
    $result = $backup->backup();
} while ($result['totalpercentage'] < 100);
  ```
  
  使用AJAX
  
  ```php (type)
    $backup = new \lotofbadcode\phpextend\databackup\mysql\Backup('127.0.0.1:3306', 'test', 'root', '');
$result = $backup->setbackdir($backupdir)
        ->setvolsize(0.2) //分卷大小
        ->ajaxbackup();
  ```
  ![](https://github.com/lotofbadcode/phpextenddemo/blob/master/mysqlbackup/backup.gif)
   


b.恢复 

  不使用AJAX
   ```php (type)
    <?php

$recovery = new \lotofbadcode\phpextend\databackup\mysql\Recovery('127.0.0.1:3306', 'test', 'root', '');
$recovery->setSqlfiledir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'backup');
do
{
    $result = $recovery->recovery();

} while ($result['totalpercentage'] < 100);

  ```
  使用AJAX
   ```php (type)
    <?php
$recovery = new \lotofbadcode\phpextend\databackup\mysql\Recovery('127.0.0.1:3306', 'test', 'root', '');
$result = $recovery->setSqlfiledir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'backup')
        ->ajaxrecovery();

echo json_encode($result);
  ```
    ![](https://github.com/lotofbadcode/phpextenddemo/blob/master/mysqlbackup/recovery.gif)
