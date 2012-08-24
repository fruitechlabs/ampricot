<?php
namespace Ampricot;
require_once 'class/Process.php';
$process = new Process();
$process->iniSet('ampricotfileconf', array('ampricotharmony' => $_SERVER['argv'][1]));
?>