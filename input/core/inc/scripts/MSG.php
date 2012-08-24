<?php
namespace Ampricot;
require_once 'class/Process.php';
$process = new Process();
$process->msg($_SERVER['argv'][1]);
?>