<?php
namespace Apricore;
require_once 'class/Process.php';
$process = new Process();
$process->iniSet('apricorefileconf', array('apricoreversionmysql' => $_SERVER['argv'][1], 'apricoreservicemysqlinstall' => '--install-manual ApricoreMySQL --defaults-file=' . $process->apricoreinstalldirroot . '/front/conf/mysql/mysql-' . $_SERVER['argv'][1] . '/mysql.ini'));
?>