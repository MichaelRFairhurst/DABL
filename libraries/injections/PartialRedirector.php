<?php

class PartialRedirector extends Redirector {

	public function redirect($url, $die = false) {
		parent::redirect('/partial/' . ltrim($url, '/'), $die);
	}
}
