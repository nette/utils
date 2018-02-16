<?php

spl_autoload_register(function ($type) {
	if (strtolower(ltrim($type, '\\')) === 'nette\object') {
		$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$message = 'Replace deprecated Nette\Object with trait Nette\SmartObject'
			. (isset($trace[1]['file']) ? ' in ' . $trace[1]['file'] . ':' . $trace[1]['line'] : '');

		if (PHP_VERSION_ID < 70200) {
			trigger_error($message, E_USER_DEPRECATED);
			class_alias('Nette\LegacyObject', 'Nette\Object');
		} else {
			throw new Exception($message);
		}
	}
});
