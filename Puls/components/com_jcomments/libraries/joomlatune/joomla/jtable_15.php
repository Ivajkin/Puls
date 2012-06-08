<?php

if (!class_exists('JoomlaTuneDBTable')) {
	class JoomlaTuneDBTable extends JTable
	{
		function JoomlaTuneDBTable($table, $key, &$db)
		{
			parent::__construct($table, $key, $db);
		}
	}
}