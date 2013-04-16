<?php

class FileControllerTest extends PHPUnit_Framework_TestCase {

	function setUp() {
		$this->controller = new FileController;
		$this->files = $this->getMock('FileSystem');
	}

	function testControllerFileNotExistsThrowsException() {
		$this->files->expects($this->once())
						->method('file_exists')
						->with(APP_DIR . 'controllers/TheFile.php')
						->will($this->returnValue(false));

		$this->setExpectedException('FileNotFoundException');
		$this->viewController('TheFile');
	}

	function setFileExists($filename) {
		$this->files->expects($this->once())
						->method('file_exists')
						->with($filename)
						->will($this->returnValue(true));
	}

	function testControllerSetsActionSource() {
		$this->setFileExists(APP_DIR . 'controllers/TheFile.php');
		$this->viewController('TheFile');
		$this->assertEquals('source', $this->controller['action']);
	}

	function testControllerSetsControllerClass() {
		$this->setFileExists(APP_DIR . 'controllers/TheFile.php');
		$this->viewController('TheFile');
		$this->assertEquals('TheFile', $this->controller['controllerclass']);
	}

	function testControllerFileExistsLoadsContents() {
		$this->setFileExists(APP_DIR . 'controllers/TheFile.php');

		$this->files->expects($this->once())
						->method('file_get_contents')
						->with(APP_DIR . 'controllers/TheFile.php')
						->will($this->returnValue('abcdyzd'));

		$this->viewController('TheFile');
		$this->assertEquals('abcdyzd', $this->controller['content']);
	}

	function testTestFileNotExistsThrowsException() {
		$this->files->expects($this->once())
						->method('file_exists')
						->with(APP_DIR . 'tests/controllers/TheFile.php')
						->will($this->returnValue(false));

		$this->setExpectedException('FileNotFoundException');
		$this->viewTest('TheFile');
	}

	function testTestFileExistsLoadsContents() {
		$this->setFileExists(APP_DIR . 'tests/controllers/TheFile.php');

		$this->files->expects($this->once())
						->method('file_get_contents')
						->with(APP_DIR . 'tests/controllers/TheFile.php')
						->will($this->returnValue('abcdyzd'));

		$this->viewTest('TheFile');
		$this->assertEquals('abcdyzd', $this->controller['content']);
	}

	function viewController($filename) {
		return $this->controller->controller($filename, $this->files);
	}

	function testTestSetsActionTestSource() {
		$this->setFileExists(APP_DIR . 'tests/controllers/TheFileTest.php');
		$this->viewTest('TheFileTest');
		$this->assertEquals('testsource', $this->controller['action']);
	}

	function testTestSetsControllerClass() {
		$this->setFileExists(APP_DIR . 'tests/controllers/TheFileTest.php');
		$this->viewTest('TheFileTest');
		$this->assertEquals('TheFile', $this->controller['controllerclass']);
	}

	function viewTest($filename) {
		return $this->controller->test($filename, $this->files);
	}
		
}
