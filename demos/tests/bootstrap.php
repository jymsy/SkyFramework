<?php
// change the following paths if necessary
require_once(__DIR__.'/../config/settings.php');
$skyt=__DIR__.'/../../framework/skyt.php';
$config=__DIR__.'/../config/main_debug.php';

require_once($skyt);
require_once(__DIR__.'/WebTestCase.php');

\Sky\Sky::createWebApplication($config);