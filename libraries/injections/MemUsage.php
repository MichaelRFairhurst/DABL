<?php

class MemUsage {

	private static $sizes = array('B', 'KiB', 'MiB', 'GiB', 'TiB');

	/**
	 * This directive is no longer required as of 5.2.1. There are same workarounds for
	 * finding current usage, such as scanning the process list for your script by ID.
	 * You can create a custom solution and hook it up to the injector.
	 */
	private function enforceCompatibility() {
		if(!function_exists('memory_get_usage'))
			throw new RuntimeException('MemUsage object only supports php with --enable-memory-limit');
	}

	/**
	 * Current usage the PHP engine has allocated for your program
	 * @return int
	 */
	public function snapshot() {
		$this->enforceCompatibility();
		return memory_get_usage();
	}

	/**
	 * Current usage the PHP engine has allocated for itself while running your program
	 * @return int
	 */
	public function snapshotReal() {
		$this->enforceCompatibility();
		return memory_get_usage(true);
	}

	/**
	 * Peak usage the PHP engine has allocated for your program
	 * @return int
	 */
	public function peak() {
		$this->enforceCompatibility();
		return memory_get_peak_usage();
	}

	/**
	 * Peak usage the PHP engine has allocated for itself while running your program
	 * @return int
	 */
	public function peakReal() {
		$this->enforceCompatibility();
		return memory_get_peak_usage(true);
	}

	/**
	 * Get the memory_limit directive
	 * @return string
	 */
	public function getCap() {
		$this->enforceCompatibility();
		return ini_get('memory_limit');
	}

	/**
	 * Set the memory_limit directive. Can be an integer for bytes or a string. Returns
	 * the old value on success and false on failure
	 *
	 * @see PHP memory_limit manual
	 * @param mixed
	 * @return mixed
	 */
	public function setCap($amt) {
		$this->enforceCompatibility();
		ini_set('memory_limit', $amt);
	}

	/**
	 * Print the amount in a human readable way. Units will go up at half values, so
	 * any more than 255KiB becomes .25MiB. Notice this uses Kibibytes and Mebibites,
	 * the corect terms for multiples of 1024.
	 *
	 * @param integer $bytes
	 * @param integer $places defaults to 2
	 */
	public function format($bytes, $places = 2) {
		for($k=256, $i=0;$bytes >= $k && $i+1 < count(self::$sizes); $i++, $k<<=10);

		return $this->formatExact($bytes, self::$sizes[$i], $places);
	}

	/**
	 * Print the amount, normalized to a unit of your choice. You can also specify
	 * the number of decimal places. Available units are: B, KiB, MiB, GiB, TiB.
	 * These are the correct terms for units based on 1024 rather than 1000.
	 *
	 * @param integer $bytes
	 * @param string $size
	 * @param integer $places
	 */
	public function formatExact($bytes, $size, $places = 2) {
		$k = array_search($size, self::$sizes);
		if($k === false) throw new DomainException('Size ' . $size . ' not supported');

		return sprintf("%.{$places}f%s", $bytes/(1<<(10*$k)), $size);
	}

}
