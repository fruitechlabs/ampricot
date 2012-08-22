<?php
namespace Apricore;
require_once 'class/Process.php';
$process = new Process();
$process->switchPHPExtension($_SERVER['argv']);
?>