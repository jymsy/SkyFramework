<?php
require_once(__DIR__.'/config/settings.php');
$skyc=__DIR__.'/../framework/skyc.php';
$config=__DIR__.'/config/console.php';
@putenv('SKY_CONSOLE_COMMANDS='. __DIR__.'/autorun');
require_once($skyc);