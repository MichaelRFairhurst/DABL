<?php

$phpunit_loaded = class_exists('PHPUnit_Framework_TestSuite');
require_once '/usr/share/pear/PHPUnit/Autoload.php';
if(!$phpunit_loaded) {
	require APP_DIR . 'tests/controllers/IndexControllerTest.php';
	require APP_DIR . 'tests/controllers/RunControllerTest.php';
	require APP_DIR . 'tests/controllers/FileControllerTest.php';
	require APP_DIR . 'tests/controllers/ApplicationControllerTest.php';
}
