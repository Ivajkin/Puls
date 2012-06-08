<?php
/**
 * JComments - Joomla Comment System
 *
 * Backend Blacklist Manager
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

require_once(JCOMMENTS_TABLES.'/blacklist.php');

class JCommentsAdminBlacklistManager
{
	public static function show()
	{
		$app = JCommentsFactory::getApplication('administrator');
		$config = JCommentsFactory::getConfig();
		if ($config->getInt('enable_blacklist') == 0) {
			if (JCOMMENTS_JVERSION != '1.0') {
				JError::raiseWarning(500, JText::_('A_BLACKLIST_WARNING_BLACKLIST_IS_DISABLED'));
			}
		}

		$context = 'com_jcomments.blacklist.';

		$limit = intval($app->getUserStateFromRequest($context . 'limit', 'limit', $app->getCfg('list_limit')));
		$limitstart = intval($app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0));
		$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'bl.created');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'desc');
		$search = trim($app->getUserStateFromRequest($context . 'search', 'search', ''));

		if (JCOMMENTS_JVERSION == '1.0') {
			$search = strtolower($search);
		} else {
			$search = JString::strtolower($search);
		}

		if ($filter_order == '') {
			$filter_order = 'bl.created';
		}

		if ($filter_order_Dir == '') {
			$filter_order_Dir = 'desc';
		}

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['search'] = $search;

		$db = JCommentsFactory::getDBO();

		$where = array();

		if ($search != '') {
			$where[] = '(LOWER(bl.ip) like "%' . $db->getEscaped($search, true) . '%")'
					. ' OR LOWER(bl.reason) like "%' . $db->getEscaped($search, true) . '%"'
					. ' OR LOWER(bl.notes) like "%' . $db->getEscaped($search, true) . '%"';
		}

		$query = "SELECT COUNT(*)"
				. " FROM #__jcomments_blacklist AS bl"
				. (count($where) ? (" WHERE " . implode(' AND ', $where)) : "");
		$db->setQuery($query);
		$total = $db->loadResult();

		$lists['pageNav'] = JCommentsAdmin::getPagination($total, $limitstart, $limit);

		$query = "SELECT bl.*, u.name AS editor"
				. " FROM #__jcomments_blacklist AS bl"
				. " LEFT JOIN #__users AS u ON u.id = bl.checked_out"
				. (count($where) ? (" WHERE " . implode(' AND ', $where)) : "")
				. " ORDER BY " . $filter_order . ' ' . $filter_order_Dir;
		$db->setQuery( $query, $lists['pageNav']->limitstart, $lists['pageNav']->limit );
		$lists['rows'] = $db->loadObjectList();

		HTML_JCommentsAdminBlacklistManager::show($lists);
	}

	public static function edit()
	{
		$id = JCommentsInput::getVar('cid', 0);
		if (is_array($id)) {
			$id = $id[0];
		}

		$db = JCommentsFactory::getDBO();
		$row = new JCommentsTableBlacklist($db);
		if ($id) {
			$user = JCommentsFactory::getUser();
			$row->load($id);
			$row->checkout($user->id);
		}

		HTML_JCommentsAdminBlacklistManager::edit($row);
	}

	public static function save()
	{
		JCommentsSecurity::checkToken();

		$task = JCommentsInput::getVar('task');
		$id = (int) JCommentsInput::getVar('id', 0);

		$db = JCommentsFactory::getDBO();
		$row = new JCommentsTableBlacklist($db);
		if ($id) {
			$row->load($id);
		} else {
			$user = JCommentsFactory::getUser();
			$row->created_by = $user->id;
			$row->created = JCommentsFactory::getDate();
		}

		$row->ip = preg_replace('#[^0-9\.\*]#', '', trim(strip_tags(JCommentsInput::getVar('ip'))));
		$row->reason = trim(strip_tags(JCommentsInput::getVar('reason')));
		$row->notes = trim(strip_tags(JCommentsInput::getVar('notes')));

		if (empty($row->notes) && !empty($row->reason)) {
			$row->notes = $row->reason;
		}

		if ($row->ip == $_SERVER['REMOTE_ADDR']) {
			JError::raiseWarning(500, JText::_('A_BLACKLIST_ERROR_YOU_CAN_NOT_BAN_YOUR_IP'));
		} else {
			$row->store();
		}
		$row->checkin();


		switch ($task) {
			case 'blacklist.apply':
				JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=blacklist.edit&hidemainmenu=1&cid[]=' . $row->id);
				break;
			case 'blacklist.save':
			default:
				JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=blacklist');
				break;
		}
	}

	public static function cancel()
	{
		JCommentsSecurity::checkToken();

		$db = JCommentsFactory::getDBO();
		$row = new JCommentsTableBlacklist($db);
		$row->bind($_POST);
		$row->checkin();

		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=blacklist');
	}

	public static function remove()
	{
		JCommentsSecurity::checkToken();

		$id = JCommentsInput::getVar('cid', array());

		if (is_array($id) && (count($id) > 0)) {
			$ids = implode(',', $id);

			$db = JCommentsFactory::getDBO();
			$db->setQuery("DELETE FROM #__jcomments_blacklist WHERE id IN ($ids)");
			$db->query();
		}
		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=blacklist');
	}
}

class HTML_JCommentsAdminBlacklistManager
{
	public static function show( $lists )
	{
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
		<th style="background-image: none; padding: 0;"><img src="components/com_jcomments/assets/icon-48-blacklist.png" width="48" height="48" align="middle" alt="<?php echo JText::_('A_BLACKLIST'); ?>" />&nbsp;<?php echo JText::_('A_BLACKLIST'); ?></th>
<?php
		}
?>
		<td nowrap="nowrap" align="left" width="50%">
			<label for="search"><?php echo JText::_('A_FILTER'); ?>:</label>
			<input type="text" name="search" id="search" value="<?php echo $lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_('A_FILTER_APPLY'); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('A_FILTER_RESET'); ?></button>
		</td>
		<td nowrap="nowrap" align="right" width="50%"></td>
	</tr>
</table>
<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="1%"><input type="checkbox" id="toggle" name="toggle" value="" onclick="checkAll(<?php echo count( $lists['rows'] );?>);" /></th>
<?php
		if (JCOMMENTS_JVERSION == '1.0') {
?>
			<th width="10%" align="left" nowrap="nowrap"><?php echo JText::_('A_BLACKLIST_IP'); ?></th>
			<th width="20%" align="left"><?php echo JText::_('A_BLACKLIST_REASON'); ?></th>
			<th width="60%" align="left"><?php echo JText::_('A_BLACKLIST_NOTES'); ?></th>
			<th width="10%" align="left"><?php echo JText::_('A_BLACKLIST_CREATED'); ?></th>
<?php
		} else {
?>
			<th width="10%" align="left" nowrap="nowrap"><?php echo JHTML::_( 'grid.sort', 'A_BLACKLIST_IP', 'bl.ip', $lists['order_Dir'], $lists['order']); ?></th>
			<th width="20%" align="left"><?php echo JHTML::_( 'grid.sort', 'A_BLACKLIST_REASON', 'bl.reason', $lists['order_Dir'], $lists['order']); ?></th>
			<th width="60%" align="left"><?php echo JHTML::_( 'grid.sort', 'A_BLACKLIST_NOTES', 'bl.notes', $lists['order_Dir'], $lists['order']); ?></th>
			<th width="10%" align="left"><?php echo JHTML::_( 'grid.sort', 'A_BLACKLIST_CREATED', 'bl.created', $lists['order_Dir'], $lists['order']); ?></th>
<?php
		}
?>
		</tr>
	</thead>
	<tbody>
<?php
		for ($i = 0, $k = 0, $n = count($lists['rows']); $i < $n; $i++) {
			$row =& $lists['rows'][$i];

			$link 	= JCOMMENTS_INDEX . '?option=com_jcomments&task=blacklist.edit&hidemainmenu=1&cid='. $row->id;
?>
<tr class="<?php echo "row$k"; ?>">
	<td align="center"><?php echo JCommentsHTML::_('grid.checkedout', $row, $i); ?></td>
	<td align="left"><a href="<?php echo $link; ?>" title="<?php echo JText::_('A_EDIT'); ?>"><?php echo $row->ip; ?></a></td>
	<td align="left"><?php echo $row->reason; ?></td>
	<td align="left"><?php echo $row->notes; ?></td>
	<td align="center"><?php echo $row->created; ?></td>
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
<input type="hidden" name="task" value="blacklist" />
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
.ip {width: 100px;}
.middle {width: 250px;}
</style>
<script type="text/javascript">
<!--
<?php if (JCOMMENTS_JVERSION == '1.7') { ?>
Joomla.submitbutton = function (task) {
	if (task == 'blacklist.cancel') {
		Joomla.submitform(task, document.getElementById('adminForm'));
		return;
	}
	if (document.adminForm.email.value == "") {
		alert("<?php echo addslashes(JText::sprintf('A_FORM_VALIDATE_FIELD_REQUIRED', JText::_('A_BLACKLIST_IP'))); ?>");
	} else {
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
};
<?php } else { ?>
function submitbutton(task)
{
	if (task == 'blacklist.cancel') {
		submitform(task);
		return;
	}
	if (document.adminForm.ip.value == "") {
		alert("<?php echo JText::sprintf('A_FORM_VALIDATE_FIELD_REQUIRED', JText::_('A_BLACKLIST_IP')); ?>");
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
		<th style="background-image: none; padding: 0;"><img src="components/com_jcomments/assets/icon-48-blacklist.png" width="48" height="48" align="middle" alt="<?php echo JText::_('A_BLACKLIST_EDIT');?>">&nbsp;<?php echo JText::_('A_BLACKLIST_EDIT');?></th>
	</tr>
</table>
<?php
		}
?>
<table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
<tr valign="top" align="left">
	<td width="15%"><label for="ip"><?php echo JText::_('A_BLACKLIST_IP'); ?></label></td>
	<td width="25%"><input type="text" class="editbox ip" size="35" id="ip" name="ip" value="<?php echo $row->ip; ?>"></td>
	<td width="60%"></td>
</tr>
<tr valign="top" align="left">
	<td><label for="reason"><?php echo JText::_('A_BLACKLIST_REASON'); ?></label></td>
	<td><input type="text" class="editbox middle" size="35" id="reason" name="reason" value="<?php echo $row->reason; ?>"></td>
	<td><?php echo JText::_('A_BLACKLIST_REASON_DESC'); ?></td>
</tr>
<tr valign="top" align="left">
	<td><label for="notes"><?php echo JText::_('A_BLACKLIST_NOTES'); ?></label></td>
	<td><input type="text" class="editbox middle" size="35" id="notes" name="notes" value="<?php echo $row->notes; ?>"></td>
	<td><?php echo JText::_('A_BLACKLIST_NOTES_DESC'); ?></td>
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