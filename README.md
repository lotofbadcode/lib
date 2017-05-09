
**php 扩展类库 后面有好的类库会慢慢添加**

**1.lotofbadcode\phpextend\databackup\mysql**

是mysql数据库备份恢复的类库 支持AJAX 支持进度条

使用方法：
1.备份 不使用AJAX
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
2.恢复 不使用AJAX
   ```php (type)
    <?php

$recovery = new \lotofbadcode\phpextend\databackup\mysql\Recovery('127.0.0.1:3306', 'test', 'root', '');
$recovery->setSqlfiledir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'backup');
do
{
    $result = $recovery->recovery();

} while ($result['totalpercentage'] < 100);

  ```
