<?php
namespace Apricore;
require_once 'class/Process.php';
$process = new Process();
$process->iniSet('apricorefileconf', array('apricorelang' => $_SERVER['argv'][1]));
?>