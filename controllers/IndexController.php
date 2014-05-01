<?php

class IndexController extends ApplicationController {

	function index(StdClass $mytest, $uri_segment = 'none') {
		die($mytest->see . ' and has first URI segment: ' . $uri_segment);
	}

}
