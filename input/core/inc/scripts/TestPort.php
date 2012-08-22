<?php
namespace Apricore;
require_once 'class/Process.php';
$process = new Process();
$process->testPort(intval($_SERVER['argv'][1]));
?>