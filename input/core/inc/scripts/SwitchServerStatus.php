<?php
namespace Ampricot;
require_once 'class/Process.php';
$process = new Process();
$process->switchServerStatus($_SERVER['argv'][1]);
$process->iniSet('ampricotfileconf', array('ampricotstatus' => $_SERVER['argv'][1]));
?>