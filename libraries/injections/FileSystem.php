<?php

/**
 * A collection of wrappers for all of PHP's filesystem functions. Note that
 * testing the filesystem should not always be done with mocks/unit tests. If
 * you are testing complex filesystem actions, use vfsStream...or just buckle
 * down and do an integration test
 */
class FileSystem {

	public function dir($path) {
		return dir($path);
	}

	public function chgrp() {
		return call_user_func_array('chgrp', func_get_args());
	}

	public function chmod() {
		return call_user_func_array('chmod', func_get_args());
	}

	public function chown() {
		return call_user_func_array('chown', func_get_args());
	}

	public function clearstatcache() {
		return call_user_func_array('clearstatcache', func_get_args());
	}

	public function copy() {
		return call_user_func_array('copy', func_get_args());
	}

	public function delete() {
		return call_user_func_array('delete', func_get_args());
	}

	public function dirname() {
		return call_user_func_array('dirname', func_get_args());
	}

	public function disk_free_space() {
		return call_user_func_array('disk_free_space', func_get_args());
	}

	public function disk_total_space() {
		return call_user_func_array('disk_total_space', func_get_args());
	}

	public function diskfreespace() {
		return call_user_func_array('diskfreespace', func_get_args());
	}

	public function fclose() {
		return call_user_func_array('fclose', func_get_args());
	}

	public function feof() {
		return call_user_func_array('feof', func_get_args());
	}

	public function fflush() {
		return call_user_func_array('fflush', func_get_args());
	}

	public function fgetc() {
		return call_user_func_array('fgetc', func_get_args());
	}

	public function fgetcsv() {
		return call_user_func_array('fgetcsv', func_get_args());
	}

	public function fgets() {
		return call_user_func_array('fgets', func_get_args());
	}

	public function fgetss() {
		return call_user_func_array('fgetss', func_get_args());
	}

	public function file_exists() {
		return call_user_func_array('file_exists', func_get_args());
	}

	public function file_get_contents() {
		return call_user_func_array('file_get_contents', func_get_args());
	}

	public function file_put_contents() {
		return call_user_func_array('file_put_contents', func_get_args());
	}

	public function file() {
		return call_user_func_array('file', func_get_args());
	}

	public function fileatime() {
		return call_user_func_array('fileatime', func_get_args());
	}

	public function filectime() {
		return call_user_func_array('filectime', func_get_args());
	}

	public function filegroup() {
		return call_user_func_array('filegroup', func_get_args());
	}

	public function fileinode() {
		return call_user_func_array('fileinode', func_get_args());
	}

	public function filemtime() {
		return call_user_func_array('filemtime', func_get_args());
	}

	public function fileowner() {
		return call_user_func_array('fileowner', func_get_args());
	}

	public function fileperms() {
		return call_user_func_array('fileperms', func_get_args());
	}

	public function filesize() {
		return call_user_func_array('filesize', func_get_args());
	}

	public function filetype() {
		return call_user_func_array('filetype', func_get_args());
	}

	public function flock() {
		return call_user_func_array('flock', func_get_args());
	}

	public function fnmatch() {
		return call_user_func_array('fnmatch', func_get_args());
	}

	public function fopen() {
		return call_user_func_array('fopen', func_get_args());
	}

	public function fpassthru() {
		return call_user_func_array('fpassthru', func_get_args());
	}

	public function fputcsv() {
		return call_user_func_array('fputcsv', func_get_args());
	}

	public function fputs() {
		return call_user_func_array('fputs', func_get_args());
	}

	public function fread() {
		return call_user_func_array('fread', func_get_args());
	}

	public function fscanf() {
		return call_user_func_array('fscanf', func_get_args());
	}

	public function fseek() {
		return call_user_func_array('fseek', func_get_args());
	}

	public function fstat() {
		return call_user_func_array('fstat', func_get_args());
	}

	public function ftell() {
		return call_user_func_array('ftell', func_get_args());
	}

	public function ftruncate() {
		return call_user_func_array('ftruncate', func_get_args());
	}

	public function fwrite() {
		return call_user_func_array('fwrite', func_get_args());
	}

	public function glob() {
		return call_user_func_array('glob', func_get_args());
	}

	public function is_dir() {
		return call_user_func_array('is_dir', func_get_args());
	}

	public function is_executable() {
		return call_user_func_array('is_executable', func_get_args());
	}

	public function is_file() {
		return call_user_func_array('is_file', func_get_args());
	}

	public function is_link() {
		return call_user_func_array('is_link', func_get_args());
	}

	public function is_readable() {
		return call_user_func_array('is_readable', func_get_args());
	}

	public function is_uploaded_file() {
		return call_user_func_array('is_uploaded_file', func_get_args());
	}

	public function is_writable() {
		return call_user_func_array('is_writable', func_get_args());
	}

	public function is_writeable() {
		return call_user_func_array('is_writeable', func_get_args());
	}

	public function lchgrp() {
		return call_user_func_array('lchgrp', func_get_args());
	}

	public function lchown() {
		return call_user_func_array('lchown', func_get_args());
	}

	public function link() {
		return call_user_func_array('link', func_get_args());
	}

	public function linkinfo() {
		return call_user_func_array('linkinfo', func_get_args());
	}

	public function lstat() {
		return call_user_func_array('lstat', func_get_args());
	}

	public function mkdir() {
		return call_user_func_array('mkdir', func_get_args());
	}

	public function move_uploaded_file() {
		return call_user_func_array('move_uploaded_file', func_get_args());
	}

	public function parse_ini_file() {
		return call_user_func_array('parse_ini_file', func_get_args());
	}

	public function parse_ini_string() {
		return call_user_func_array('parse_ini_string', func_get_args());
	}

	public function pclose() {
		return call_user_func_array('pclose', func_get_args());
	}

	public function popen() {
		return call_user_func_array('popen', func_get_args());
	}

	public function readfile() {
		return call_user_func_array('readfile', func_get_args());
	}

	public function readlink() {
		return call_user_func_array('readlink', func_get_args());
	}

	public function realpath_cache_get() {
		return call_user_func_array('realpath_cache_get', func_get_args());
	}

	public function realpath_cache_size() {
		return call_user_func_array('realpath_cache_size', func_get_args());
	}

	public function realpath() {
		return call_user_func_array('realpath', func_get_args());
	}

	public function rename() {
		return call_user_func_array('rename', func_get_args());
	}

	public function rewind() {
		return call_user_func_array('rewind', func_get_args());
	}

	public function rmdir() {
		return call_user_func_array('rmdir', func_get_args());
	}

	public function set_file_buffer() {
		return call_user_func_array('set_file_buffer', func_get_args());
	}

	public function stat() {
		return call_user_func_array('stat', func_get_args());
	}

	public function symlink() {
		return call_user_func_array('symlink', func_get_args());
	}

	public function tempnam() {
		return call_user_func_array('tempnam', func_get_args());
	}

	public function tmpfile() {
		return call_user_func_array('tmpfile', func_get_args());
	}

	public function touch() {
		return call_user_func_array('touch', func_get_args());
	}

	public function umask() {
		return call_user_func_array('umask', func_get_args());
	}

	public function unlink() {
		return call_user_func_array('unlink', func_get_args());
	}
}
