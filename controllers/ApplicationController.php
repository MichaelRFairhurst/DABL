<?php

abstract class ApplicationController extends Controller {

	function __construct(ControllerRoute $route = null) {
		parent::__construct($route);

		$this['action'] = '';
		$this['controllerclass'] = get_class($this);
		$this['meta_controllerclass'] = get_class($this);
	}
}
