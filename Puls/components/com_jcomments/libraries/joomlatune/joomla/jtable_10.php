<?php

if (!class_exists('JoomlaTuneDBTable')) {
	class JoomlaTuneDBTable extends mosDBTable
	{
		function JoomlaTuneDBTable()
		{
			$args = func_get_args();
			call_user_func_array(array(&$this, '__construct'), $args);
		}

		function __construct($table, $key, $db)
		{
			$this->mosDBTable($table, $key, $db);
		}
	}
}
