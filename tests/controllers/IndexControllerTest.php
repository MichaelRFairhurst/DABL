<?php

class IndexControllerTest extends PHPUnit_Framework_TestCase {

	function setUp() {
		$this->suite = $this->getMock('PHPUnit_Framework_TestSuite');
		$this->result = $this->getMock('PHPUnit_Framework_TestResult');
		$this->files = $this->getMock('FileSystem');
		$this->classes = $this->getMock('Classes');
		$this->dir = $this->getMock('Directory');
		$this->files->expects($this->at(0))
				->method('dir')
				->with(APP_DIR . 'controllers')
				->will($this->returnValue($this->dir));

		$this->controller = new IndexController;
	}

	function testIndexControllerRunsTestSuite() {
		$this->suite->expects($this->once())
				->method('run')
				->with($this->result);

		$errors = array(1); $passed = array(2); $failures = array(3);

		$this->result->expects($this->once())
				->method('errors')
				->will($this->returnValue($errors));
		$this->result->expects($this->once())
				->method('failures')
				->will($this->returnValue($failures));
		$this->result->expects($this->once())
				->method('passed')
				->will($this->returnValue($passed));

		$this->index();
		$this->assertEquals($errors, $this->controller['errors']);
		$this->assertEquals($failures, $this->controller['failures']);
		$this->assertEquals($passed, $this->controller['passed']);
	}

	function testControllersAreLoadedIntoTestSuite() {
		$this->dir->expects($this->at(0))
			->method('read')
			->will($this->returnValue('MyTestController.php'));
		$this->dir->expects($this->at(1))
			->method('read')
			->will($this->returnValue('MyOtherTestController.php'));

		$this->classes->expects($this->any())
			->method('class_exists')
			->will($this->returnValue(true));

		$this->suite->expects($this->at(0))
			->method('addTestSuite')
			->with('MyTestControllerTest');
		$this->suite->expects($this->at(1))
			->method('addTestSuite')
			->with('MyOtherTestControllerTest');

		$this->index();
	}

	function testControllersWithoutTestSuitesAreNotLoaded() {
		$this->dir->expects($this->at(0))
			->method('read')
			->will($this->returnValue('MyTestController.php'));

		$this->classes->expects($this->once())
			->method('class_exists')
			->with('MyTestControllerTest')
			->will($this->returnValue(false));

		$this->suite->expects($this->never())
			->method('addTestSuite');

		$this->index();
	}

	function index() {
		return $this->controller->index($this->suite, $this->result, $this->files, $this->classes);
	}
		
}
