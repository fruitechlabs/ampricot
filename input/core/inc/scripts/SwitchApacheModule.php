<?php
namespace Ampricot;
require_once 'class/Process.php';
$process = new Process();
$process->switchApacheModule($_SERVER['argv']);
?>