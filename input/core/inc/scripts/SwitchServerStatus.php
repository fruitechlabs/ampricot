<?php
namespace Apricore;
require_once 'class/Process.php';
$process = new Process();
$process->switchServerStatus($_SERVER['argv'][1]);
$process->iniSet('apricorefileconf', array('apricorestatus' => $_SERVER['argv'][1]));
?>