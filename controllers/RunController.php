<?php

class RunController extends ApplicationController {

	public function __construct() {
		parent::__construct();
		$this['action'] = 'run';
	}

	public function suite(
		$testclass,
		PHPUnit_Framework_TestSuite $suite,
		PHPUnit_Framework_TestResult $result,
		Classes $classes
	) {
		$this->prepareTestSuite($testclass, $suite, $classes);
		$this->runSuite($suite, $result);
	}

	public function test(
		$testclass,
		$test,
		PHPUnit_Framework_TestSuite $suite,
		PHPUnit_Framework_TestResult $result,
		Classes $classes
	) {
		$this['messages'][] = 'This still runs the whole test suite';
		$this->prepareTestSuite($testclass, $suite, $classes);
		$this->runSuite($suite, $result);
	}

	function getView($action) {
		return '/index';
	}

	function index(Redirector $r) {
		$r->redirect('/');
	}

	private function prepareTestSuite($testclass, PHPUnit_Framework_TestSuite $suite, Classes $classes) {
		$this['controllerclass'] = substr($testclass, 0, -4);
		if(!$classes->class_exists($testclass)
			|| !$classes->is_a($testclass, 'PHPUnit_Framework_TestCase', true))
				throw new FileNotFoundException();
			
		$suite->addTestSuite($testclass);
	}

	private function runSuite(PHPUnit_Framework_TestSuite $suite, PHPUnit_Framework_TestResult $result) {
		$suite->run($result);
		$this['failures'] = $result->failures();
		$this['errors'] = $result->errors();
		$this['passed'] = $result->passed();
	}
}
