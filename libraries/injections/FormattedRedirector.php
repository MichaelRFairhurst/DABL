<?php

class FormattedRedirector extends Redirector {

	private $format;

	function __construct($format) {
		$this->format = $format;
	}

	function redirect($url, $die = false) {
		if (strpos($url, '?') !== false) {
			$parts = explode('?', $url, 2);
			$url = array_shift($parts);
			$url .= '.' . $this->outputFormat;
			array_unshift($parts, $url);
			$url = implode('?', $parts);
		} else {
			$url .= '.' . $this->outputFormat;
		}

		parent::redirect($url, $die);
	}
}
