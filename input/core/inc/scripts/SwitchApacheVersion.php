<?php
namespace Ampricot;
require_once 'class/Process.php';
$process = new Process();
$process->switchApacheVersion($_SERVER['argv'][1]);
?>