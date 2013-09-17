<?php
// defined('SKY_DEBUG') or define('SKY_DEBUG',true);
// defined('SKY_TRACE_LEVEL') or define('SKY_TRACE_LEVEL',3);
require_once(__DIR__.'/config/settings.php');
if (defined('CREQ_DIR')) @include_once(CREQ_DIR.'cReqST.php');
require_once(__DIR__.'/../framework/sky.php');
$config=__DIR__.'/config/main.php';
// \Sky\logging\PQPLogRoute::logMemory("memory used before application");
// \Sky\Sky::beginXProfile();
\Sky\Sky::createWebApplication($config)->run();