<?php
use Sky\Sky;
use demos\components\DemoApplication;
//define('SKY_ENABLE_ERROR_HANDLER', false);
defined('SKY_DEBUG') or define('SKY_DEBUG',true);
// defined('SKY_TRACE_LEVEL') or define('SKY_TRACE_LEVEL',3);
require_once(__DIR__.'/config/settings.php');
require_once(__DIR__.'/../framework/sky.php');
//require_once(__DIR__.'/components/DemoApplication.php');
$config=__DIR__.'/config/main_debug.php';
// \Sky\logging\PQPLogRoute::logMemory("memory used before application");
Sky::createWebApplication($config)->run();
//Sky::createApplication('\demos\components\DemoApplication',$config)->run();
