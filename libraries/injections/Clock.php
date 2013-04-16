<?php

class Clock {

	/**
	 * Get the current time as a unix timestamp
	 * @return int
	 */
	public function getTime() {
		return time();
	}

	/**
	 * Get the current time in microseconds
	 */
	public function getMicroTime() {
		return microtime(true);
	}

}
