<?php

class IndexController extends ApplicationController {

	function index(
		PHPUnit_Framework_TestSuite $suite,
		PHPUnit_Framework_TestResult $result,
		FileSystem $filesystem,
		Classes $classes
	) {
		$dir = $filesystem->dir(APP_DIR . 'controllers');

		while($file = $dir->read()) {
			if($filesystem->is_dir($file)) continue;

			// FUN FACT: pathinfo is a pure string function, thus doesn't need to be injected
			$pathinfo = pathinfo($file);
			$testclass = $pathinfo['filename'] . 'Test';

			if($classes->class_exists($testclass))
				$suite->addTestSuite($testclass);
		}

		$suite->run($result);
		$this['failures'] = $result->failures();
		$this['errors'] = $result->errors();
		$this['passed'] = $result->passed();
	}

}
