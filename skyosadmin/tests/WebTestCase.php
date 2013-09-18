<?php
namespace skyosadmin\tests;

define('TEST_BASE_URL','http://localhost:8080/demos/debug.php/');
// define('TEST_BASE_URL','http://localhost:8080/demos/index.php/');
// define('TEST_BASE_URL','http://42.121.119.71/skyframework/demos/index.php/');

class WebTestCase extends \Sky\test\WebTestCase{
	/**
	 * Sets up before each test method runs.
	 * This mainly sets the base URL for the test application.
	 */
	protected function setUp(){
		parent::setUp();
		$this->setBrowserUrl(TEST_BASE_URL);
	}
}