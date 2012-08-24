<?php
namespace Ampricot;
require_once 'class/Process.php';
$process = new Process();
$process->switchPHPExtension($_SERVER['argv']);
?>