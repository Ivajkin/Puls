<?php

function VirtuemartBuildRoute(&$query) {
	static $items, $vmmenus;
	$segments = array();
	$itemid = null;
	if (isset($query['Itemid'])) {
		$itemid = $query['Itemid'];
		unset($query['Itemid']);
	}
	unset($query['option']);
	// Search for an appropriate menu item.
	foreach ($query as $k => $v) {
		$segments[] = $k;
		$segments[] = $v;
		unset($query[$k]);
	}
	if($itemid) $query['Itemid'] = $itemid;
	$query['option'] = 'com_virtuemart';
	return $segments;
}

function VirtueMartParseRoute($segments) {
	$vars = array();

	$c = count($segments);
	for ($i = 0; $i < $c; $i = $i + 2) {
		$vars[$segments[$i]] = $segments[$i + 1];
	}
	return $vars;
}
