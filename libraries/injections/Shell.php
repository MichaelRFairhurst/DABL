<?php

class Shell {

	function run($cmd) {
		return shell_exec($cmd);
	}

	function escapeArg($arg) {
		return escapeshellarg($arg);
	}

	function escapeCmd($cmd) {
		return escapeshellcmd($cmd);
	}

	function runEscaped($cmd) {
		return $this->run($this->escapeCmd($cmd));
	}

	function runEscapedArgs($cmd, array $args) {
		return $this->run($this->escapeCmd($cmd) . join(' ', array_map($args, array($this, 'escapeArg'))));
	}

}
