<?php

class FileController extends ApplicationController {

	function controller($filename, FileSystem $files) {
		$this['action'] = 'source';
		$this['controllerclass'] = $filename;
		$filename = APP_DIR . 'controllers/' . $filename . '.php';
		if(!$files->file_exists($filename))
			throw new FileNotFoundException();

		$this['content'] = $files->file_get_contents($filename);
	}

	function test($filename, FileSystem $files) {
		$this['action'] = 'testsource';
		$this['controllerclass'] = substr($filename, 0, -4);
		$filename = APP_DIR . 'tests/controllers/' . $filename . '.php';
		if(!$files->file_exists($filename))
			throw new FileNotFoundException();

		$this['content'] = $files->file_get_contents($filename);
	}

	function index(Redirector $r) {
		$r->redirect('/');
	}

	function getView($action) {
		return 'file/view';
	}

}
