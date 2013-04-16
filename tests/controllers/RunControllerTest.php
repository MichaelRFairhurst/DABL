<?php

class RunControllerTest extends PHPUnit_Framework_TestCase {

	function setUp() {
		$this->suite = $this->getMock('PHPUnit_Framework_TestSuite');
		$this->result = $this->getMock('PHPUnit_Framework_TestResult');
		$this->classes = $this->getMock('Classes');

		$this->controller = new RunController;
	}

	function testHasActionRun() {
		$this->assertEquals('run', $this->controller['action']);
	}

	function testRunSuiteClassNotExistsThrowsFNF() {
		$this->classes->expects($this->once())
					->method('class_exists')
					->with('MyTestSuite')
					->will($this->returnValue(false));

		$this->setExpectedException('FileNotFoundException');
		$this->runSuite('MyTestSuite');
	}

	function testRunSuiteClassNotATestSuite() {
		$this->classes->expects($this->once())
					->method('class_exists')
					->with('MyTestSuite')
					->will($this->returnValue(true));
		$this->classes->expects($this->once())
					->method('is_a')
					->with('MyTestSuite', 'PHPUnit_Framework_TestCase', true)
					->will($this->returnValue(false));

		$this->setExpectedException('FileNotFoundException');
		$this->runSuite('MyTestSuite');
	}

	function testSharesIndexView() {
		$this->assertEquals('/index', $this->controller->getView('anything'));
	}

	function setClassFileExists($classname) {
		$this->classes->expects($this->once())
					->method('class_exists')
					->with($classname)
					->will($this->returnValue(true));
		$this->classes->expects($this->once())
					->method('is_a')
					->with($classname, 'PHPUnit_Framework_TestCase', true)
					->will($this->returnValue(true));
	}

	function testRunSuiteSetsURLSuite() {
		$this->setClassFileExists('MyTestSuite');

		$this->suite->expects($this->once())
				->method('addTestSuite')
				->with('MyTestSuite');

		$this->runSuite('MyTestSuite');
	}

	function testRunSuiteSetsController() {
		$this->setClassFileExists('MyTestSuiteTest');
		$this->runSuite('MyTestSuiteTest');
		$this->assertEquals('MyTestSuite', $this->controller['controllerclass']);
	}

	function testSuiteRuns() {
		$this->setClassFileExists('MyTestSuite');

		$this->suite->expects($this->once())
				->method('run')
				->with($this->result);

		$errors = array(1); $passed = array(1); $failures = array(3);

		$this->result->expects($this->once())
				->method('errors')
				->will($this->returnValue($errors));
		$this->result->expects($this->once())
				->method('failures')
				->will($this->returnValue($failures));
		$this->result->expects($this->once())
				->method('passed')
				->will($this->returnValue($passed));

		$this->runSuite('MyTestSuite');
		$this->assertEquals($errors, $this->controller['errors']);
		$this->assertEquals($failures, $this->controller['failures']);
		$this->assertEquals($passed, $this->controller['passed']);
	}

	function runSuite($name) {
		return $this->controller->suite($name, $this->suite, $this->result, $this->classes);
	}
}
