<?php
//屏蔽E_NOTICE错误
error_reporting(E_ALL ^ E_NOTICE);
// defined('SKY_DEBUG') or define('SKY_DEBUG',true);
// defined('SKY_TRACE_LEVEL') or define('SKY_TRACE_LEVEL',3);
require_once(__DIR__.'/config/settings.php');
require_once(__DIR__.'/../framework/sky.php');
$config=__DIR__.'/config/main.php';

\Sky\Sky::createWebApplication($config)->run();