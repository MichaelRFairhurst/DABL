<?php

class RestRedirector extends Redirector {

	public function redirect($url, $die = true) {
		$url = '/rest/' . ltrim($url, '/');
		Controller::load($url);
		die;
	}
}
