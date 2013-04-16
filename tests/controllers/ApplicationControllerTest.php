<?php

class ApplicationControllerTest extends PHPUnit_Framework_TestCase {

	function testSetsControllerClassName() {
		$controller = $this->getMockForAbstractClass('ApplicationController');
		$this->assertEquals(get_class($controller), $controller['controllerclass']);
	}

	function testSetsMetaControllerClassName() {
		$controller = $this->getMockForAbstractClass('ApplicationController');
		$this->assertEquals(get_class($controller), $controller['meta_controllerclass']);
	}
	
}
