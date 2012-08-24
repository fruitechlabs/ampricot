<?php
namespace Ampricot;
require_once 'class/Process.php';
$process = new Process();
$process->switchPHPSetting($_SERVER['argv']);
?>