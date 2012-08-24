<?php
namespace Ampricot;
require_once 'class/Process.php';
$process = new Process();
$process->iniSet('ampricotfileconf', array('ampricotlang' => $_SERVER['argv'][1]));
?>