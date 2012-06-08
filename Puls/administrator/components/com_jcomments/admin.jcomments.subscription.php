<?php
/**
 * JComments - Joomla Comment System
 *
 * Backend Subscriptions Manager
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

class JCommentsAdminSubscriptionManager
{
	public static function show()
	{
		$app = JCommentsFactory::getApplication('administrator');
		$context = 'com_jcomments.subscriptions.';

		$object_group = trim($app->getUserStateFromRequest($context . 'fog', 'fog', ''));
		$object_id = intval($app->getUserStateFromRequest($context . 'foid', 'foid', 0));
		$flang = trim($app->getUserStateFromRequest($context . 'flang', 'flang', '-1'));
		$fauthor = trim($app->getUserStateFromRequest($context . 'fauthor', 'fauthor', ''));
		$fstate = trim($app->getUserStateFromRequest($context . 'fstate', 'fstate', '-1'));
		$limit = intval($app->getUserStateFromRequest($context . 'limit', 'limit', $app->getCfg('list_limit')));
		$limitstart = intval($app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0));

		$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'js.name');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'asc');
		$search = trim($app->getUserStateFromRequest($context . 'search', 'search', ''));

		if (JCOMMENTS_JVERSION == '1.0') {
			$search = strtolower($search);
		} else {
			$search = JString::strtolower($search);
		}

		if ($filter_order == '') {
			$filter_order = 'js.name';
		}
		if ($filter_order_Dir == '') {
			$filter_order_Dir = 'asc';
		}

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['search'] = $search;

		$db = JCommentsFactory::getDBO();

		$where = array();

		if ($object_group != '') {
			$where[] = 'js.object_group = "' . $db->getEscaped($object_group, true) . '"';
		}

		if ($object_id != 0) {
			$where[] = 'js.object_id = ' . intval($object_id);
		}

		if ($flang != '-1') {
			$where[] = 'js.lang = "' . $db->getEscaped($flang, true) . '"';
		}

		if ($fauthor != '') {
			$where[] = 'js.name = "' . $db->getEscaped($fauthor, true) . '"';
		}

		if ($fstate != '' && $fstate != '-1') {
			$where[] = 'js.published = ' . intval($fstate);
		}

		if ($search != '') {
			$where[] = '(js.name like "%' . $db->getEscaped($search, true) . '%" OR js.email like "%' . $db->getEscaped($search, true) . '%")';
		}

		$query = "SELECT COUNT(*)"
				. " FROM #__jcomments_subscriptions AS js"
				. (count($where) ? ("\nWHERE " . implode(' AND ', $where)) : "");
		$db->setQuery($query);
		$total = $db->loadResult();

		$lists['pageNav'] = JCommentsAdmin::getPagination($total, $limitstart, $limit);

		$query = "SELECT js.*, u.name AS editor"
				. " FROM #__jcomments_subscriptions AS js"
				. " LEFT JOIN #__users AS u ON u.id = js.userid"
				. (count($where) ? (" WHERE " . implode(' AND ', $where)) : "")
				. " ORDER BY " . $filter_order . ' ' . $filter_order_Dir;
		$db->setQuery( $query, $lists['pageNav']->limitstart, $lists['pageNav']->limit );
		$lists['rows'] = $db->loadObjectList();

		// Filter by object_group (component)
		$query = "SELECT DISTINCT(object_group) AS name, object_group AS value "
				. " FROM #__jcomments_subscriptions"
				. " ORDER BY name";
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$cnt = count($rows);

		if ($cnt > 1 || ($cnt == 1 && $total == 0)) {
			array_unshift($rows, JCommentsHTML::makeOption('', JText::_('A_FILTER_COMPONENT'), 'name', 'value'));
			$lists['fog'] = JCommentsHTML::selectList($rows, 'fog', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'name', 'value', $object_group);
		} else if ($cnt == 1) {
			if ($object_group == '') {
				$object_group = $rows[0]->name;
			}
		}
		unset($rows);

		if ($object_group != '') {
			$query = "SELECT DISTINCT object_id AS value, lang "
				. " FROM #__jcomments_subscriptions "
				. " WHERE object_group = " . $db->Quote($object_group)
				. (($flang != '-1') ? " AND lang = " . $db->Quote($flang) : "")
				;
			$db->setQuery($query);
			$rows = $db->loadObjectList();

			for ($i = 0, $n = count($rows); $i < $n; $i++) {
				$rows[$i]->name = JCommentsObjectHelper::getTitle($rows[$i]->value, $object_group, $rows[$i]->lang);
				if ($rows[$i]->name == '') {
					$rows[$i]->name = 'Untitled' . $rows[$i]->value;
				}
			}

			// Don't show filter if we have more than 100 objects
			if (count($rows) > 1 && count($rows) < 100) {
				usort($rows, create_function('$a, $b', 'return strcasecmp( $a->name, $b->name);'));
				array_unshift($rows, JCommentsHTML::makeOption('', JText::_('A_FILTER_OBJECT'), 'value', 'name'));
				$lists['foid'] = JCommentsHTML::selectList($rows, 'foid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'name', $object_id);
				unset($rows);
			}
		}

		// Filter by language
		$query = "SELECT DISTINCT(lang) AS text, lang AS value "
				. " FROM #__jcomments_subscriptions"
				. " ORDER BY lang";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if (count($rows) > 1) {
			array_unshift($rows, JCommentsHTML::makeOption('-1', JText::_('A_FILTER_LANGUAGE'), 'text', 'value'));
			$lists['flang'] = JCommentsHTML::selectList($rows, 'flang', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'text', 'value', $flang);
		}
		unset($rows);

		// Filter by published state
		$stateOptions = array();
		$stateOptions[] = JCommentsHTML::makeOption('-1', JText::_('A_FILTER_STATE'), 'text', 'value');
		$stateOptions[] = JCommentsHTML::makeOption('', JText::_('A_FILTER_STATE_ALL'), 'text', 'value');
		$stateOptions[] = JCommentsHTML::makeOption('1', JText::_('A_FILTER_STATE_PUBLISHED'), 'text', 'value');
		$stateOptions[] = JCommentsHTML::makeOption('0', JText::_('A_FILTER_STATE_UNPUBLISHED'), 'text', 'value');
		$lists['fstate'] = JCommentsHTML::selectList($stateOptions, 'fstate', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'text', 'value', $fstate);
		unset($stateOptions);

		// Filter by author
		$lists['fauthor'] = '';

		$db->setQuery("SELECT COUNT(DISTINCT(name)) FROM #__jcomments_subscriptions;");
		$usersCount = $db->loadResult();

		// Don't show filter if we have more than 100 comments' authors
		if ($usersCount > 0 && $usersCount < 100) {
			$query = "SELECT DISTINCT(name) AS author, name AS value "
				. " FROM #__jcomments_subscriptions"
				. " ORDER BY name"
				;
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			if (count($rows) > 1) {
				array_unshift($rows, JCommentsHTML::makeOption('', JText::_('A_FILTER_AUTHOR'), 'author', 'value'));
				$lists['fauthor'] = JCommentsHTML::selectList($rows, 'fauthor', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'author', 'value', $fauthor);
			}
			unset($rows);
		}

		HTML_JCommentsAdminSubscriptionManager::show($lists);
	}

	public static function edit()
	{
		$id = JCommentsInput::getVar('cid', 0);
		if (is_array($id)) {
			$id = $id[0];
		}

		require_once (JCOMMENTS_TABLES.'/subscription.php');

		$db = JCommentsFactory::getDBO();
		$row = new JCommentsTableSubscription($db);

		if ($id) {
			$row->load($id);
			$row->object_title = JCommentsBackendObjectHelper::getTitle($row->object_id, $row->object_group, $row->lang);
			$row->link = JCommentsBackendObjectHelper::getLink($row->object_id, $row->object_group, $row->lang);
		}

		HTML_JCommentsAdminSubscriptionManager::edit($row);
	}

	public static function save()
	{
		JCommentsSecurity::checkToken();

		$task = JCommentsInput::getVar('task');
		$id = (int) JCommentsInput::getVar('id', 0);

		require_once (JCOMMENTS_TABLES.'/subscription.php');

		$db = JCommentsFactory::getDBO();
		$row = new JCommentsTableSubscription($db);

		if ($id) {
			$row->load($id);
		}

		$row->object_id = (int) JCommentsInput::getVar('object_id');
		$row->object_group = JCommentsSecurity::clearObjectGroup(JCommentsInput::getVar('object_group'));
		$row->name = preg_replace("/[\'\"\>\<\(\)\[\]]?+/i", '', strip_tags(JCommentsInput::getVar('name')));
		$row->email = trim(strip_tags(JCommentsInput::getVar('email')));
		$row->published = (int) JCommentsInput::getVar('published');

		if (!$row->id) {
			$query = "SELECT id, name FROM #__users WHERE email = " . $db->Quote($row->email);
			$db->setQuery($query);
			$users = $db->loadObjectList();
			if (count($users)) {
				$row->userid = $users[0]->id;
				$row->name = $users[0]->name;
			} else {
				$row->userid = 0;
			}
			$row->lang = '';  // TODO: add language selection if JoomFish installed
		}

		$row->store();

		switch ($task) {
			case 'subscription.apply':
				JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=subscription.edit&hidemainmenu=1&cid[]=' . $row->id);
				break;
			case 'subscription.save':
			default:
				JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=subscriptions');
				break;
		}
	}

	public static function publish($value)
	{
		JCommentsSecurity::checkToken();

		$id = JCommentsInput::getVar('cid', array());

		if (is_array($id) && (count($id) > 0)) {
			$ids = implode(',', $id);

			$db = JCommentsFactory::getDBO();
			$db->setQuery("UPDATE #__jcomments_subscriptions SET published='$value' WHERE id IN ($ids)");
			$db->query();
		}
		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=subscriptions');
	}

	public static function cancel()
	{
		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=subscriptions');
	}

	public static function remove()
	{
		JCommentsSecurity::checkToken();

		$id = JCommentsInput::getVar('cid', array());

		if (is_array($id) && (count($id) > 0)) {
			$ids = implode(',', $id);

			$db = JCommentsFactory::getDBO();
			$db->setQuery("DELETE FROM #__jcomments_subscriptions WHERE id IN ($ids)");
			$db->query();
		}
		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=subscriptions');
	}
}

class HTML_JCommentsAdminSubscriptionManager
{
	public static function show( $lists )
	{
		$filter = '';
		$filterClear = '';

		if (isset($lists['fog'])) {
			$filter .= ' ' . $lists['fog'];
			$filterClear .= "document.getElementById('fog').value='';";
		}
		if (isset($lists['flang'])) {
			$filter .= ' ' . $lists['flang'];
			$filterClear .= "document.getElementById('flang').value='';";
		}
		if (isset($lists['foid'])) {
			$filter .= ' ' . $lists['foid'];
			$filterClear .= "document.getElementById('foid').value='';";
		}
		if (isset($lists['fauthor']) && $lists['fauthor'] != '') {
			$filter .= ' ' . $lists['fauthor'];
			$filterClear .= "document.getElementById('fauthor').value='';";
		}
		if (isset($lists['fstate'])) {
			$filter .= ' ' . $lists['fstate'];
			$filterClear .= "document.getElementById('fstate').value='';\n";
		}

		if (JCOMMENTS_JVERSION != '1.0') {
?>
<script type="text/javascript">
<!--
function tableOrdering(order, dir, task)
{
	var form = document.adminForm;
	form.filter_order.value = order;
	form.filter_order_Dir.value = dir;
	document.adminForm.submit(task);
}
//-->
</script>
<?php
		}
?>
<form action="<?php echo JCOMMENTS_INDEX; ?>" method="post" name="adminForm" id="adminForm">
<table class="adminheading" width="100%">
	<tr>
<?php
		if ( JCOMMENTS_JVERSION == '1.0' ) {
?>
	<th style="background-image: none; padding: 0;"><img src="components/com_jcomments/assets/icon-48-subscriptions.png" width="48" height="48" align="middle" alt="<?php echo JText::_('A_SUBSCRIPTIONS'); ?>" />&nbsp;<?php echo JText::_('A_SUBSCRIPTIONS'); ?></th>
<?php
		}
?>
	<td nowrap="nowrap" align="left" width="50%">
		<label for="search"><?php echo JText::_('A_FILTER'); ?>:</label>
		<input type="text" name="search" id="search" value="<?php echo $lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
		<button onclick="this.form.submit();"><?php echo JText::_('A_FILTER_APPLY'); ?></button>
		<button onclick="document.getElementById('search').value='';<?php echo $filterClear; ?>this.form.submit();"><?php echo JText::_('A_FILTER_RESET'); ?></button>
	</td>
	<td nowrap="nowrap" align="right" width="50%">
<?php
		if (trim($filter) != '') {
			echo $filter;
		}
?>
	</td>
	</tr>
</table>
<table class="adminlist jgrid" cellspacing="1">
	<thead>
		<tr>
			<th width="1%"><input type="checkbox" id="toggle" name="toggle" value="" onclick="checkAll(<?php echo count( $lists['rows'] );?>);" /></th>
<?php
		if (JCOMMENTS_JVERSION == '1.0') {
?>
			<th width="20%" align="left" nowrap="nowrap"><?php echo JText::_('A_SUBSCRIPTION_NAME'); ?></th>
			<th width="20%" align="left"><?php echo JText::_('A_SUBSCRIPTION_EMAIL'); ?></th>
			<th width="40%" align="left"><?php echo JText::_('A_COMMENT_OBJECT_TITLE'); ?></th>
			<th width="10%" align="left"><?php echo JText::_('A_COMPONENT'); ?></th>
			<th width="10%" nowrap="nowrap"><?php echo JText::_('A_PUBLISHING'); ?></th>
<?php
		} else {
?>
			<th width="20%" align="left" nowrap="nowrap"><?php echo JHTML::_( 'grid.sort', 'A_SUBSCRIPTION_NAME', 'js.name', $lists['order_Dir'], $lists['order']); ?></th>
			<th width="20%" align="left"><?php echo JHTML::_( 'grid.sort', 'A_SUBSCRIPTION_EMAIL', 'js.email', $lists['order_Dir'], $lists['order']); ?></th>
			<th width="40%" align="left"><?php echo JText::_('A_COMMENT_OBJECT_TITLE'); ?></th>
			<th width="10%" align="left"><?php echo JHTML::_( 'grid.sort', 'A_COMPONENT', 'js.object_group', $lists['order_Dir'], $lists['order']); ?></th>
			<th width="10%" nowrap="nowrap"><?php echo JHTML::_( 'grid.sort', 'A_PUBLISHING', 'js.published', $lists['order_Dir'], $lists['order']); ?></th>
<?php
		}
?>
		</tr>
	</thead>
	<tbody>
<?php
		for ($i = 0, $k = 0, $n = count($lists['rows']); $i < $n; $i++) {
			$row =& $lists['rows'][$i];
			$row->title = JCommentsBackendObjectHelper::getTitle($row->object_id, $row->object_group, $row->lang);
			$row->link = JCommentsBackendObjectHelper::getLink($row->object_id, $row->object_group, $row->lang);

			$link 	= JCOMMENTS_INDEX . '?option=com_jcomments&task=subscription.edit&hidemainmenu=1&cid='. $row->id;
?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center"><?php echo JCommentsHTML::_('grid.id', $row, $i); ?></td>
			<td align="left"><a href="<?php echo $link; ?>" title="<?php echo JText::_('A_EDIT'); ?>"><?php echo $row->name; ?></a></td>
			<td align="left"><?php echo $row->email; ?></td>
			<td align="left"><a href="<?php echo $row->link; ?>" title="<?php echo htmlspecialchars($row->title); ?>" target="_blank"><?php echo $row->title; ?></a></td>
			<td align="left">[<?php echo $row->object_group; ?>]</td>
			<td align="center"><?php echo JCommentsHTML::_('grid.published', $row, $i, 'subscription.'); ?></td>
		</tr>
<?php
			$k = 1 - $k;
		}
?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="15"><?php echo $lists['pageNav']->getListFooter(); ?></td>
		</tr>
	</tfoot>
</table>
<input type="hidden" name="option" value="com_jcomments" />
<input type="hidden" name="task" value="subscriptions" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JCommentsSecurity::formToken(); ?>
</form>
<?php
	}

	public static function edit( $row )
	{
?>
<style type="text/css">
.editbox {border: 1px solid #ccc;padding: 2px;}
.short {width: 40px;}
.long {width: 450px;}
</style>
<script type="text/javascript">
<!--
<?php if (JCOMMENTS_JVERSION == '1.7') { ?>
Joomla.submitbutton = function (task) {
	if (task == 'subscription.cancel') {
		Joomla.submitform(task, document.getElementById('adminForm'));
		return;
	}
	if (document.adminForm.object_group.value == "") {
		alert("<?php echo addslashes(JText::sprintf('A_FORM_VALIDATE_FIELD_REQUIRED', JText::_('A_COMPONENT'))); ?>");
	} else if (document.adminForm.object_id.value == "") {
		alert("<?php echo addslashes(JText::sprintf('A_FORM_VALIDATE_FIELD_REQUIRED', JText::_('A_SUBSCRIPTION_OBJECT_ID'))); ?>");
	} else if (document.adminForm.name.value == "") {
		alert("<?php echo addslashes(JText::sprintf('A_FORM_VALIDATE_FIELD_REQUIRED', JText::_('A_SUBSCRIPTION_NAME'))); ?>");
	} else if (document.adminForm.email.value == "") {
		alert("<?php echo addslashes(JText::sprintf('A_FORM_VALIDATE_FIELD_REQUIRED', JText::_('A_SUBSCRIPTION_EMAIL'))); ?>");
	} else {
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
};
<?php } else { ?>
function submitbutton(task)
{
	if (task == 'subscription.cancel') {
		submitform(task);
		return;
	}
	if (document.adminForm.object_group.value == "") {
		alert("<?php echo addslashes(JText::sprintf('A_FORM_VALIDATE_FIELD_REQUIRED', JText::_('A_COMPONENT'))); ?>");
	} else if (document.adminForm.object_id.value == "") {
		alert("<?php echo addslashes(JText::sprintf('A_FORM_VALIDATE_FIELD_REQUIRED', JText::_('A_SUBSCRIPTION_OBJECT_ID'))); ?>");
	} else if (document.adminForm.name.value == "") {
		alert("<?php echo addslashes(JText::sprintf('A_FORM_VALIDATE_FIELD_REQUIRED', JText::_('A_SUBSCRIPTION_NAME'))); ?>");
	} else if (document.adminForm.email.value == "") {
		alert("<?php echo addslashes(JText::sprintf('A_FORM_VALIDATE_FIELD_REQUIRED', JText::_('A_SUBSCRIPTION_EMAIL'))); ?>");
	} else {
		submitform(task);
	}
}
<?php } ?>
//-->
</script>
<form action="<?php echo JCOMMENTS_INDEX; ?>" method="post" name="adminForm" id="adminForm">
<?php
		if ( JCOMMENTS_JVERSION == '1.0' ) {
?>
<table class="adminheading">
	<tr>
		<th style="background-image: none; padding: 0;"><img src="components/com_jcomments/assets/icon-48-subscriptions.png" width="48" height="48" align="middle" alt="<?php echo JText::_('A_SUBSCRIPTION_EDIT');?>">&nbsp;<?php echo JText::_('A_SUBSCRIPTION_EDIT');?></th>
	</tr>
</table>
<?php
		}
?>
<table class="adminform" width="100%" cellpadding="4" cellspacing="1" border="0">
<tr valign="top" align="left">
	<td><label for="object_group"><?php echo JText::_('A_COMPONENT'); ?></label></td>
	<td><input type="text" class="editbox long" size="35" id="object_group" name="object_group" value="<?php echo $row->object_group; ?>"></td>
</tr>
<tr valign="top" align="left">
	<td><label for="object_id"><?php echo JText::_('A_SUBSCRIPTION_OBJECT_ID'); ?></label></td>
	<td><input type="text" class="editbox short" size="35" id="object_id" name="object_id" value="<?php echo $row->object_id; ?>"></td>
</tr>
<tr valign="top" align="left">
	<td><label for="name"><?php echo JText::_('A_SUBSCRIPTION_NAME'); ?></label></td>
	<td><input type="text" class="editbox long" size="35" id="name" name="name" value="<?php echo $row->name; ?>"></td>
</tr>
<tr valign="top" align="left">
	<td><label for="email"><?php echo JText::_('A_SUBSCRIPTION_EMAIL'); ?></label></td>
	<td><input type="text" class="editbox long" size="35" id="email" name="email" value="<?php echo $row->email; ?>"></td>
</tr>
<tr valign="top" align="left">
	<td><?php echo JText::_('A_PUBLISHING'); ?></td>
	<td><?php echo JCommentsHTML::yesnoRadioList( 'published', 'class="inputbox"', $row->published, JText::_('A_YES'), JText::_('A_NO') ); ?></td>
</tr>
</table>
<input type="hidden" name="option" value="com_jcomments" />
<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
<input type="hidden" name="task" value="" />
<?php echo JCommentsSecurity::formToken(); ?>
</form>
<?php
	}
}
?>