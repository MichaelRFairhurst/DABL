<?php

class Classes {

	public function __autoload() {
		return call_user_func_array('__autoload', func_get_args());
	}

	public function call_user_method_array() {
		return call_user_func_array('call_user_method_array', func_get_args());
	}

	public function call_user_method() {
		return call_user_func_array('call_user_method', func_get_args());
	}

	public function class_alias() {
		return call_user_func_array('class_alias', func_get_args());
	}

	public function class_exists() {
		return call_user_func_array('class_exists', func_get_args());
	}

	public function get_called_class() {
		return call_user_func_array('get_called_class', func_get_args());
	}

	public function get_class_methods() {
		return call_user_func_array('get_class_methods', func_get_args());
	}

	public function get_class_vars() {
		return call_user_func_array('get_class_vars', func_get_args());
	}

	public function get_class() {
		return call_user_func_array('get_class', func_get_args());
	}

	public function get_declared_classes() {
		return call_user_func_array('get_declared_classes', func_get_args());
	}

	public function get_declared_interfaces() {
		return call_user_func_array('get_declared_interfaces', func_get_args());
	}

	public function get_declared_traits() {
		return call_user_func_array('get_declared_traits', func_get_args());
	}

	public function get_object_vars() {
		return call_user_func_array('get_object_vars', func_get_args());
	}

	public function get_parent_class() {
		return call_user_func_array('get_parent_class', func_get_args());
	}

	public function interface_exists() {
		return call_user_func_array('interface_exists', func_get_args());
	}

	public function is_a() {
		return call_user_func_array('is_a', func_get_args());
	}

	public function is_subclass_of() {
		return call_user_func_array('is_subclass_of', func_get_args());
	}

	public function method_exists() {
		return call_user_func_array('method_exists', func_get_args());
	}

	public function property_exists() {
		return call_user_func_array('property_exists', func_get_args());
	}

	public function trait_exists() {
		return call_user_func_array('trait_exists', func_get_args());
	}
}
