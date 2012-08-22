<?php
namespace Apricore;
require_once 'class/Process.php';
$process = new Process();
$process->switchPHPVersion($_SERVER['argv'][1]);
?>