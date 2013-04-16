<?php

class Logger {

	/**
	 * Log a message in all standard ways
	 */
	public function log($msg) {
		error_log($msg);
	}

}
