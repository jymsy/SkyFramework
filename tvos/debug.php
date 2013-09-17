<?php
defined('SKY_DEBUG') or define('SKY_DEBUG',true);
require_once(__DIR__.'/config/settings.php');
require_once(__DIR__.'/../framework/sky.php');
$config=__DIR__.'/config/main_debug.php';

\Sky\Sky::createWebApplication($config)->run();