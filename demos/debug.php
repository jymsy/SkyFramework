<?php
use Sky\Sky;
defined('SKY_DEBUG') or define('SKY_DEBUG',true);
// defined('SKY_TRACE_LEVEL') or define('SKY_TRACE_LEVEL',3);
require_once(__DIR__.'/config/settings.php');
require_once(__DIR__.'/../framework/sky.php');
$config=__DIR__.'/config/main_debug.php';
\Sky\logging\PQPLogRoute::logMemory("memory used before application");

Sky::createWebApplication($config)->run();