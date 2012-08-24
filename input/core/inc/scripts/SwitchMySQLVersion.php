<?php
namespace Ampricot;
require_once 'class/Process.php';
$process = new Process();
$process->iniSet('ampricotfileconf', array('ampricotversionmysql' => $_SERVER['argv'][1], 'ampricotservicemysqlinstall' => '--install-manual AmpricotMySQL --defaults-file=' . $process->ampricotinstalldirroot . '/front/conf/mysql/mysql-' . $_SERVER['argv'][1] . '/mysql.ini'));
?>