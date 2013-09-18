<?php
// change the following paths if necessary
require_once(__DIR__.'/../config/settings.php');
$skyt=dirname(__FILE__).'/../../framework/skyt.php';
$config=dirname(__FILE__).'/../config/main.php';

require_once($skyt);
require_once(dirname(__FILE__).'/WebTestCase.php');

\Sky\Sky::createWebApplication($config);