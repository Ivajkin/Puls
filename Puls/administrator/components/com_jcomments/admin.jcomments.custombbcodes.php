<?php
/**
 * JComments - Joomla Comment System
 *
 * Backend Custom BBCodes Manager
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

require_once(JCOMMENTS_TABLES.'/custombbcode.php');

class JCommentsACustomBBCodes
{
	public static function show()
	{
		$app = JCommentsFactory::getApplication('administrator');
		$context = 'com_jcomments.custombbcodes.';

		$limit = intval($app->getUserStateFromRequest($context . 'limit', 'limit', $app->getCfg('list_limit')));
		$limitstart = intval($app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0));

		$db = JCommentsFactory::getDBO();
		$db->setQuery('SELECT COUNT(*) FROM #__jcomments_custom_bbcodes');
		$total = $db->loadResult();

		$lists['pageNav'] = JCommentsAdmin::getPagination($total, $limitstart, $limit);

		$query = "SELECT * FROM #__jcomments_custom_bbcodes ORDER BY ordering";
		$db->setQuery($query, $lists['pageNav']->limitstart, $lists['pageNav']->limit);
		$lists['rows'] = $db->loadObjectList();

		HTML_JCommentsACustomBBCodes::show($lists);
	}

	public static function edit()
	{
		$id = JCommentsInput::getVar('cid', 0);
		if (is_array($id)) {
			$id = $id[0];
		}

		$db = JCommentsFactory::getDBO();

		$row = new JCommentsTableCustomBBCode($db);
		if ($id) {
			$row->load($id);
		}

		require_once (JCOMMENTS_HELPERS.'/user.php');
		$groups = JCommentsUserHelper::getUserGroups();

		$assignedGroups = explode(",", $row->button_acl);

		$lists['groups'] = array();

		for($i=0,$n=count($groups);$i<$n;$i++) {
			$groups[$i]->enabled = (int) in_array($groups[$i]->id, $assignedGroups);
		}
		$lists['groups'] = $groups;

		HTML_JCommentsACustomBBCodes::edit($row, $lists);
	}

	public static function save()
	{
		JCommentsSecurity::checkToken();

		$task = JCommentsInput::getVar('task');
		$id = JCommentsInput::getVar('id', 0);
		$acl = JCommentsInput::getVar('button_acl', array());

		$db = JCommentsFactory::getDBO();
		$row = new JCommentsTableCustomBBCode($db);

		$old_simple_pattern = '';
		$old_simple_replacement_html = '';
		$old_simple_replacement_text = '';

		if ($id) {
			$row->load($id);
			$old_simple_pattern = $row->simple_pattern;
			$old_simple_replacement_html = $row->simple_replacement_html;
			$old_simple_replacement_text = $row->simple_replacement_text;
		}

		$row->bind($_POST);

		$row->name = trim(strip_tags($row->name));
		$row->button_acl = implode(',', $acl);
		$row->button_open_tag = trim(strip_tags($row->button_open_tag));
		$row->button_close_tag = trim(strip_tags($row->button_close_tag));
		$row->button_title = trim(strip_tags($row->button_title));
		$row->button_prompt = trim(strip_tags($row->button_prompt));
		$row->button_image = trim(strip_tags($row->button_image));
		$row->button_css = trim(strip_tags($row->button_css));

		// handle magic quotes compatibility
		if (JCOMMENTS_JVERSION != '1.0') {
			if (get_magic_quotes_gpc() == 1) {
				$row->pattern = stripslashes($row->pattern);	
				$row->replacement_html = stripslashes($row->replacement_html);
				$row->replacement_text = stripslashes($row->replacement_text);
				$row->simple_pattern = stripslashes($row->simple_pattern);	
				$row->simple_replacement_html = stripslashes($row->simple_replacement_html);
				$row->simple_replacement_text = stripslashes($row->simple_replacement_text);
			}
		}

		if ($row->simple_replacement_text == '') {
			$row->simple_replacement_text = strip_tags($row->simple_replacement_html);
		}

		if ($row->simple_pattern != '' && $row->simple_replacement_html != '') {
			$tokens = array();
			$tokens['TEXT'] = array('([\w0-9-\+\=\!\?\(\)\[\]\{\}\/\&\%\*\#\.,_ ]+)' => '$1');
			$tokens['SIMPLETEXT'] = array('([\A-Za-z0-9-\+\.,_ ]+)' => '$1');
			$tokens['IDENTIFIER'] = array('([\w0-9-_]+)' => '$1');
			$tokens['NUMBER'] = array('([0-9]+)' => '$1');
			$tokens['ALPHA'] = array('([A-Za-z]+)' => '$1');

			$pattern = preg_quote($row->simple_pattern, '#');
			$replacement_html = $row->simple_replacement_html;
			$replacement_text = $row->simple_replacement_text;

			$m = array();
			$pad = 0;

			if (preg_match_all('/\{(' . implode('|', array_keys($tokens)) . ')[0-9]*\}/im', $row->simple_pattern, $m)) {
				foreach ($m[0] as $n => $token) {
					$token_type = $m[1][$n];

					reset($tokens[strtoupper($token_type)]);
					list($match, $replace) = each($tokens[strtoupper($token_type)]);

					$repad = array();
					if (preg_match_all('/(?<!\\\\)\$([0-9]+)/', $replace, $repad)) {
						$repad = $pad + sizeof(array_unique($repad[0]));
						$replace = preg_replace('/(?<!\\\\)\$([0-9]+)/e', "'\${' . (\$1 + \$pad) . '}'", $replace);
						$pad = $repad;
					}

					$pattern = str_replace(preg_quote($token, '#'), $match, $pattern);
					$replacement_html = str_replace($token, $replace, $replacement_html);
					$replacement_text = str_replace($token, $replace, $replacement_text);
				}
			}

			// if simple pattern not changed but pattern changed - clear simple
			if ($old_simple_pattern != $row->simple_pattern || $row->pattern == '') {
				$row->pattern = $pattern;
			}

			// if simple replacement not changed but pattern changed - clear simple
			if ($old_simple_replacement_html != $row->simple_replacement_html || $row->replacement_html == '') {
				$row->replacement_html = $replacement_html;
			}

			// if simple replacement not changed but pattern changed - clear simple
			if ($old_simple_replacement_text != $row->simple_replacement_text || $row->replacement_text == '') {
				$row->replacement_text = $replacement_text;
			}
		}

		if (!$row->id) {
			$db->setQuery("SELECT max(ordering) FROM #__jcomments_custom_bbcodes");
			$row->ordering = intval($db->loadResult()) + 1;
		}

		$row->store();

		$cache = JCommentsFactory::getCache('com_jcomments');
		$cache->clean();

		switch ($task) {
			case 'custombbcodes.apply':
				JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=custombbcodes.edit&hidemainmenu=1&cid[]=' . $row->id);
				break;
			case 'custombbcodes.save':
			default:
				JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=custombbcodes');
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
			$db->setQuery("UPDATE #__jcomments_custom_bbcodes SET published='$value' WHERE id IN ($ids)");
			$db->query();
		}
		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=custombbcodes');
	}

	public static function enableButton($value)
	{
		JCommentsSecurity::checkToken();

		$id = JCommentsInput::getVar('cid', array());

		if (is_array($id) && (count($id) > 0)) {
			$ids = implode(',', $id);

			$db = JCommentsFactory::getDBO();
			$db->setQuery("UPDATE #__jcomments_custom_bbcodes SET button_enabled='$value' WHERE id IN ($ids)");
			$db->query();
		}
		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=custombbcodes');
	}

	public static function cancel()
	{
		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=custombbcodes');
	}

	public static function remove()
	{
		JCommentsSecurity::checkToken();

		$id = JCommentsInput::getVar('cid', array());

		if (is_array($id) && (count($id) > 0)) {
			$ids = implode(',', $id);

			$db = JCommentsFactory::getDBO();
			$db->setQuery("DELETE FROM #__jcomments_custom_bbcodes WHERE id IN ($ids)");
			$db->query();
			
			$cache = JCommentsFactory::getCache('com_jcomments');
			$cache->clean();
		}
		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=custombbcodes');
	}

	public static function copy()
	{
		JCommentsSecurity::checkToken();

		$cids = JCommentsInput::getVar('cid', array());
		if (is_array($cids)) {
			$db = JCommentsFactory::getDBO();
			foreach ($cids as $cid) {
				$row = new JCommentsTableCustomBBCode($db);
				if ($row->load($cid)) {
					$row->id = 0;
					$row->name .= ' (copy)'; // TODO: use constant
					$row->button_enabled = 0;
					$row->published = 0;
					$row->ordering += 1;
					$row->store();
					$row->reorder();
				}
			}
		}
		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=custombbcodes');
	}

	public static function order( $inc )
	{
		JCommentsSecurity::checkToken();

		$id = JCommentsInput::getVar('cid', 0);
		$id = count($id) ? $id[0] : 0;

		$db = JCommentsFactory::getDBO();
		$row = new JCommentsTableCustomBBCode($db);
		
		if ($row->load($id)) {
			$row->move($inc);
			
			$cache = JCommentsFactory::getCache('com_jcomments');
			$cache->clean();
		}

		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=custombbcodes');
	}

}

class HTML_JCommentsACustomBBCodes
{
	public static function show( $lists )
	{
		$app = JCommentsFactory::getApplication('administrator');

		include_once (JCOMMENTS_HELPERS.DS.'system.php');
		$link = JCommentsSystemPluginHelper::getCSS();

		if (JCOMMENTS_JVERSION == '1.0') {
			$app->addCustomHeadTag('<link href="' . $link . '" rel="stylesheet" type="text/css" />');
		} else {
			$document = JFactory::getDocument();
			$document->addStyleSheet($link);
		}
?>
<form action="<?php echo JCOMMENTS_INDEX; ?>" method="post" name="adminForm" id="adminForm">
<table class="adminheading">
	<tr>
<?php
		if ( JCOMMENTS_JVERSION == '1.0' ) {
?>
	<th style="background-image: none; padding: 0;"><img src="components/com_jcomments/assets/icon-48-custombbcodes.png" width="48" height="48" align="middle" alt="<?php echo JText::_('A_CUSTOM_BBCODE'); ?>" />&nbsp;<?php echo JText::_('A_CUSTOM_BBCODE'); ?></th>
<?php
		}
?>
	<td nowrap="nowrap" align="right"></td>
	</tr>
</table>
<table id="jc" class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="1%"><input type="checkbox" id="toggle" name="toggle" value="" onclick="checkAll(<?php echo count( $lists['rows'] );?>);" /></th>
			<th width="30%" align="left" nowrap="nowrap"><?php echo JText::_('A_CUSTOM_BBCODE_NAME'); ?></th>
			<th width="50%" class="title"><?php echo JText::_('A_CUSTOM_BBCODE_PATTERN'); ?></th>
			<th width="16" align="center"><?php echo JText::_('A_CUSTOM_BBCODE_BUTTON_ICON'); ?></th>
			<th width="4%"><?php echo JText::_('A_ORDERING'); ?></th>
			<th width="5%"><?php echo JText::_('A_CUSTOM_BBCODE_BUTTON'); ?></th>
			<th width="5%"><?php echo JText::_('A_PUBLISHING'); ?></th>
		</tr>
	</thead>
	<tbody>
<?php
		for ($i = 0, $k = 0, $n = count($lists['rows']); $i < $n; $i++) {
			$row =& $lists['rows'][$i];

			$buttonStateTask = $row->button_enabled ? 'custombbcodes.disable_button' : 'custombbcodes.enable_button';
			if (JCOMMENTS_JVERSION == '1.7') {
				$buttonStateClass = $row->button_enabled ? 'publish' : 'unpublish';
				$buttonStateText = $row->button_enabled ? JText::_('JENABLED') : JText::_('JDISABLED');
				$buttonStateTitle = addslashes(htmlspecialchars($buttonStateText, ENT_COMPAT, 'UTF-8'));
				$buttonState = '<span class="state ' . $buttonStateClass . '"><span class="text">'.$buttonStateText.'</span></span>';
			} else {
				$buttonStateTitle = $row->button_enabled ? JText::_('A_DISABLE') : JText::_('A_ENABLE');
				$buttonState = '<img src="images/' . ($row->button_enabled ? 'tick.png' : 'publish_x.png') . '" border="0" alt="' . $buttonStateTitle . '" />';
			}

			$icon = '';

			if ($row->button_image != '') {
				$icon = '<img src="' . $app->getCfg('live_site') . '/' . $row->button_image .  '" alt="' . $row->name . '" />';
			} else if ($row->button_css != '') {
				$icon = '<span class="bbcode" style="width: 23px;"><a href="#" onclick="return false;" class="' . $row->button_css . '"></a></span>';
			}

			$link 	= JCOMMENTS_INDEX . '?option=com_jcomments&task=custombbcodes.edit&hidemainmenu=1&cid='. $row->id;
?>
<tr valign="middle" class="<?php echo "row$k"; ?>">
	<td align="center"><?php echo JCommentsHTML::_('grid.id', $row, $i); ?></td>
	<td align="left"><a href="<?php echo $link; ?>" title="<?php echo JText::_('A_EDIT'); ?>"><?php echo $row->name; ?></a></td>
	<td align="left"><?php echo $row->simple_pattern; ?></td>
	<td align="center"><?php echo $icon; ?></td>
	<td class="order" align="center">
		<span><?php echo $lists['pageNav']->orderUpIcon( $i, true, 'custombbcodes.orderup' ); ?></span>			
		<span><?php echo $lists['pageNav']->orderDownIcon( $i, $n, true, 'custombbcodes.orderdown' ); ?></span>
	</td>
	<td align="center"><a class="jgrid" href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $buttonStateTask;?>')" title="<?php echo $buttonStateTitle; ?>"><?php echo $buttonState; ?></a></td>
	<td align="center"><?php echo JCommentsHTML::_('grid.published', $row, $i, 'custombbcodes.'); ?></td>
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
<input type="hidden" name="task" value="custombbcodes" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="hidemainmenu" value="0" />
<?php echo JCommentsSecurity::formToken(); ?>
</form>
<?php
	}

	public static function edit( $row, $lists )
	{
		$pcreText = JText::_('A_CUSTOM_BBCODE_ADVANCED_PATTERN_PCRE');
		$pcreLink = JText::_('A_CUSTOM_BBCODE_ADVANCED_PATTERN_PCRE_LINK');

		$patternLink = '<a href="' . $pcreLink . '">' . $pcreText . '</a>';
		$patternDescription = JText::sprintf('A_CUSTOM_BBCODE_ADVANCED_PATTERN_DESC', $patternLink);
?>
<style type="text/css">
.editbox {border: 1px solid #ccc;padding: 2px;}
.short {width: 100px;}
.middle {width: 250px;}
.long {width: 450px;}
.adminform fieldset label {display: inline; clear: none; float: none; font-size: 1em;}
.adminform fieldset input {float: none}
.adminform fieldset p {margin: 5px 0; }
</style>
<script language="javascript" type="text/javascript">
<!--
function jc_insertText(id,text) {
	var ta=document.getElementById(id);
	if(typeof(ta.caretPos)!="undefined"&&ta.createTextRange){ta.focus();var sel=document.selection.createRange();sel.text=sel.text+text;ta.focus();}
	else if(typeof(ta.selectionStart)!="undefined"){
		var ss=ta.value.substr(0, ta.selectionStart);
		var se=ta.value.substr(ta.selectionEnd),sp=ta.scrollTop;
		ta.value=ss+text+se;
		if(ta.setSelectionRange){ta.focus();ta.setSelectionRange(ss.length+text.length,ss.length+text.length);}
		ta.scrollTop=sp;
	} else {ta.value+=text;ta.focus(ta.value.length-1);}
}

<?php if (JCOMMENTS_JVERSION == '1.7') { ?>
Joomla.submitbutton = function(task) {
	Joomla.submitform(task, document.getElementById('adminForm'));
};
<?php } else { ?>
function submitbutton(task)
{
	submitform(task);
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
		<th style="background-image: none; padding: 0;"><img src="components/com_jcomments/assets/icon-48-custombbcodes.png" width="48" height="48" align="middle" alt="<?php echo JText::_('A_CUSTOM_BBCODE_EDIT');?>">&nbsp;<?php echo JText::_('A_CUSTOM_BBCODE_EDIT');?></th>
	</tr>
</table>
<?php
		}
?>
<table class="adminform" width="100%" cellpadding="4" cellspacing="1" border="0">
	<tr valign="top" align="left">
		<td>
			<fieldset>
				<legend><?php echo JText::_('A_COMMON'); ?></legend>
				<table width="100%">
				<tr valign="top" align="left">
					<td width="10%"><label for="name"><?php echo JText::_('A_CUSTOM_BBCODE_NAME'); ?></label></td>
					<td><input type="text" class="editbox middle" size="35" id="name" name="name" onChange="return generate_tag();" value="<?php echo $row->name; ?>"></td>
				</tr>
				<tr valign="top" align="left">
					<td><?php echo JText::_('A_PUBLISHING'); ?></td>
					<td><?php echo JCommentsHTML::yesnoRadioList( 'published', 'class="inputbox"', $row->published, JText::_('A_YES'), JText::_('A_NO') ); ?></td>
					<td></td>
				</tr>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr valign="top" align="left">
		<td>
			<fieldset>
				<legend><?php echo JText::_('A_CUSTOM_BBCODE_PATTERN'); ?></legend>
<?php
		$tabs = new JCommentsTabs( 1 );
		$tabs->startPane('com_jcomments_custom_bbcode_pattern' );
		$tabs->startTab(JText::_('A_CUSTOM_BBCODE_SIMPLE'), "simple_pattern_tab");
?>
				<table width="100%">
				<tr valign="top" align="left">
					<td width="30%">
						<textarea class="editbox long" rows="4" cols="50" id="simple_pattern" name="simple_pattern"><?php echo $row->simple_pattern; ?></textarea>
						<p><?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKENS');?></p>
						<p>
						<abbr onclick="jc_insertText('simple_pattern', '{SIMPLETEXT}');" title="<?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKEN_SIMPLETEXT')?>">{SIMPLETEXT}</abbr>,
						<abbr onclick="jc_insertText('simple_pattern', '{TEXT}');" title="<?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKEN_TEXT')?>">{TEXT}</abbr>,
						<abbr onclick="jc_insertText('simple_pattern', '{IDENTIFIER}');" title="<?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKEN_IDENTIFIER')?>">{IDENTIFIER}</abbr>
						<abbr onclick="jc_insertText('simple_pattern', '{ALPHA}');" title="<?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKEN_ALPHA')?>">{ALPHA}</abbr>
						<abbr onclick="jc_insertText('simple_pattern', '{NUMBER}');" title="<?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKEN_NUMBER')?>">{NUMBER}</abbr>
						</p>
					</td>
					<td align="left">
						<label for="simple_pattern"><?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_PATTERN_DESC')?></label><br />
						<br /><?php echo JText::_('A_CUSTOM_BBCODE_EXAMPLE'); ?> [highlight={SIMPLETEXT1}]{SIMPLETEXT2}[/highlight]
					</td>
				</tr>
				</table>
<?php
		$tabs->endTab();
		$tabs->startTab(JText::_('A_CUSTOM_BBCODE_ADVANCED'), "regexp_pattern_tab");
?>
				<table width="100%">
				<tr valign="top" align="left">
					<td width="30%">
						<textarea class="editbox long" rows="4" cols="50" id="pattern" name="pattern"><?php echo $row->pattern; ?></textarea>
					</td>
					<td align="left">
						<label for="pattern"><?php echo $patternDescription; ?></label><br />
						<br /><?php echo JText::_('A_CUSTOM_BBCODE_EXAMPLE'); ?> \[highlight\=([a-zA-Z0-9].?)\](*.?)\[\/highlight\]
					</td>
				</tr>
				</table>
<?php
		$tabs->endTab();
		$tabs->endPane();
?>
			</fieldset>
		</td>
	</tr>
	<tr valign="top" align="left">
		<td>
			<fieldset>
				<legend><?php echo JText::_('A_CUSTOM_BBCODE_REPLACEMENT'); ?> (<?php echo JText::_('A_CUSTOM_BBCODE_HTML'); ?>)</legend>
<?php

		$tabs2 = new JCommentsTabs( 1 );
		$tabs2->startPane( 'com_jcomments_custom_bbcode_replacement_html' );
		$tabs2->startTab(JText::_('A_CUSTOM_BBCODE_SIMPLE'), "simple_replacement_html_tab");
?>
				<table width="100%">
				<tr valign="top" align="left">
					<td width="30%">
						<textarea class="editbox long" rows="4" cols="50" id="simple_replacement_html" name="simple_replacement_html"><?php echo $row->simple_replacement_html; ?></textarea>
						<p><?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKENS');?></p>
						<p>
						<abbr onclick="jc_insertText('simple_replacement_html', '{SIMPLETEXT}');" title="<?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKEN_SIMPLETEXT')?>">{SIMPLETEXT}</abbr>,
						<abbr onclick="jc_insertText('simple_replacement_html', '{TEXT}');" title="<?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKEN_TEXT')?>">{TEXT}</abbr>,
						<abbr onclick="jc_insertText('simple_replacement_html', '{IDENTIFIER}');" title="<?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKEN_IDENTIFIER')?>">{IDENTIFIER}</abbr>
						<abbr onclick="jc_insertText('simple_replacement_html', '{ALPHA}');" title="<?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKEN_ALPHA')?>">{ALPHA}</abbr>
						<abbr onclick="jc_insertText('simple_replacement_html', '{NUMBER}');" title="<?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKEN_NUMBER')?>">{NUMBER}</abbr>
						</p>
					</td>
					<td align="left">
						<label for="simple_replacement_html"><?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_REPLACEMENT_HTML_DESC')?></label><br />
						<br /><?php echo JText::_('A_CUSTOM_BBCODE_EXAMPLE'); ?> &lt;span style="background-color: {SIMPLETEXT1};"&gt;{SIMPLETEXT2}&lt;/span&gt;
					</td>
				</tr>
				</table>
<?php
		$tabs2->endTab();
		$tabs2->startTab(JText::_('A_CUSTOM_BBCODE_ADVANCED'), "regexp_replacement_html_tab");
?>
				<table width="100%">
				<tr valign="top" align="left">
					<td width="30%">
						<textarea class="editbox long" rows="4" cols="50" id="replacement_html" name="replacement_html"><?php echo $row->replacement_html; ?></textarea>
					</td>
					<td align="left">
						<label for="replacement_html"><?php echo $patternDescription; ?></label><br />
						<br /><?php echo JText::_('A_CUSTOM_BBCODE_EXAMPLE'); ?> &lt;span style="background-color: ${1};"&gt;${2}&lt;/span&gt;
					</td>
				</tr>
				</table>
<?php
		$tabs2->endTab();
		$tabs2->endPane();
?>
			</fieldset>
		</td>
	</tr>

	<tr valign="top" align="left">
		<td>
			<fieldset>
				<legend><?php echo JText::_('A_CUSTOM_BBCODE_REPLACEMENT'); ?> (<?php echo JText::_('A_CUSTOM_BBCODE_PLAIN_TEXT'); ?>)</legend>
<?php

		$tabs2 = new JCommentsTabs( 1 );
		$tabs2->startPane( 'com_jcomments_custom_bbcode_replacement_text' );
		$tabs2->startTab(JText::_('A_CUSTOM_BBCODE_SIMPLE'), "simple_replacement_text_tab");
?>
				<table width="100%">
				<tr valign="top" align="left">
					<td width="30%">
						<textarea class="editbox long" rows="3" cols="50" id="simple_replacement_text" name="simple_replacement_text"><?php echo $row->simple_replacement_text; ?></textarea>
						<p><?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKENS');?></p>
						<p>
						<abbr onclick="jc_insertText('simple_replacement_text', '{SIMPLETEXT}');" title="<?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKEN_SIMPLETEXT')?>">{SIMPLETEXT}</abbr>,
						<abbr onclick="jc_insertText('simple_replacement_text', '{TEXT}');" title="<?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKEN_TEXT')?>">{TEXT}</abbr>,
						<abbr onclick="jc_insertText('simple_replacement_text', '{IDENTIFIER}');" title="<?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKEN_IDENTIFIER')?>">{IDENTIFIER}</abbr>
						<abbr onclick="jc_insertText('simple_replacement_text', '{ALPHA}');" title="<?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKEN_ALPHA')?>">{ALPHA}</abbr>
						<abbr onclick="jc_insertText('simple_replacement_text', '{NUMBER}');" title="<?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_TOKEN_NUMBER')?>">{NUMBER}</abbr>
						</p>
					</td>
					<td align="left">
						<label for="simple_replacement_text"><?php echo JText::_('A_CUSTOM_BBCODE_SIMPLE_REPLACEMENT_TEXT_DESC')?></label><br />
						<br /><?php echo JText::_('A_CUSTOM_BBCODE_EXAMPLE'); ?> {SIMPLETEXT2}
					</td>
				</tr>
				</table>
<?php
		$tabs2->endTab();
		$tabs2->startTab(JText::_('A_CUSTOM_BBCODE_ADVANCED'), "regexp_replacement_text_tab");
?>
				<table width="100%">
				<tr valign="top" align="left">
					<td width="30%">
						<textarea class="editbox long" rows="3" cols="50" id="replacement_text" name="replacement_text"><?php echo $row->replacement_text; ?></textarea>
					</td>
					<td align="left">
						<label for="replacement_text"><?php echo $patternDescription; ?></label><br />
						<br /><?php echo JText::_('A_CUSTOM_BBCODE_EXAMPLE'); ?> ${2}
					</td>
				</tr>
				</table>
<?php
		$tabs2->endTab();
		$tabs2->endPane();
?>
			</fieldset>
		</td>
	</tr>

	<tr valign="top" align="left">
		<td>
			<fieldset>
				<legend><?php echo JText::_('A_CUSTOM_BBCODE_BUTTON'); ?></legend>

				<table class="adminform" width="100%">
				<tr valign="top" align="left">
					<td width="20%"><label for="button_title"><?php echo JText::_('A_CUSTOM_BBCODE_BUTTON_TITLE'); ?></label></td>
					<td width="30%"><input type="text" class="editbox middle" size="35" id="button_title" name="button_title" value="<?php echo $row->button_title; ?>"></td>
					<td><?php echo JText::_('A_CUSTOM_BBCODE_BUTTON_TITLE_DESC'); ?></td>
				</tr>
				<tr valign="top" align="left">
					<td><label for="button_prompt"><?php echo JText::_('A_CUSTOM_BBCODE_BUTTON_HELP_LINE'); ?></label></td>
					<td><input type="text" class="editbox middle" size="35" id="button_prompt" name="button_prompt" value="<?php echo $row->button_prompt; ?>"></td>
					<td><?php echo JText::_('A_CUSTOM_BBCODE_BUTTON_HELP_LINE_DESC'); ?></td>
				</tr>
				<tr valign="top" align="left">
					<td><label for="button_image"><?php echo JText::_('A_CUSTOM_BBCODE_BUTTON_ICON'); ?></label></td>
					<td><input type="text" class="editbox middle" size="35" id="button_image" name="button_image" value="<?php echo $row->button_image; ?>"></td>
					<td><?php echo JText::_('A_CUSTOM_BBCODE_BUTTON_ICON_DESC'); ?></td>
				</tr>
				<tr valign="top" align="left">
					<td><label for="button_css"><?php echo JText::_('A_CUSTOM_BBCODE_BUTTON_CSS_CLASS'); ?></label></td>
					<td><input type="text" class="editbox short" size="35" id="button_css" name="button_css" value="<?php echo $row->button_css; ?>"></td>
					<td><?php echo JText::_('A_CUSTOM_BBCODE_BUTTON_CSS_CLASS_DESC'); ?></td>
				</tr>
				<tr valign="top" align="left">
					<td><label for="button_open_tag"><?php echo JText::_('A_CUSTOM_BBCODE_BUTTON_OPEN_TAG'); ?></label></td>
					<td><input type="text" class="editbox short" size="35" id="button_open_tag" name="button_open_tag" value="<?php echo $row->button_open_tag; ?>"></td>
					<td><?php echo JText::_('A_CUSTOM_BBCODE_BUTTON_OPEN_TAG_DESC'); ?></td>
				</tr>
				<tr valign="top" align="left">
					<td><label for="button_close_tag"><?php echo JText::_('A_CUSTOM_BBCODE_BUTTON_CLOSE_TAG'); ?></label></td>
					<td><input type="text" class="editbox short" size="35" id="button_close_tag" name="button_close_tag" value="<?php echo $row->button_close_tag; ?>"></td>
					<td><?php echo JText::_('A_CUSTOM_BBCODE_BUTTON_CLOSE_TAG_DESC'); ?></td>
				</tr>
				<tr valign="top" align="left">
					<td><?php echo JText::_('A_CUSTOM_BBCODE_BUTTON_ENABLE'); ?></td>
					<td><?php echo JCommentsHTML::yesnoRadioList( 'button_enabled', 'class="inputbox"', $row->button_enabled, JText::_('A_YES'), JText::_('A_NO') ); ?></td>
					<td></td>
				</tr>
				</table>
			</fieldset>
		</td>
	</tr>

	<tr valign="top" align="left">
		<td>
			<fieldset>
				<legend><?php echo JText::_('A_CUSTOM_BBCODE_PERMISSIONS'); ?></legend>

				<table class="adminform" width="100%">
				<tr valign="top" align="left">
					<td>
<?php
		foreach ($lists['groups'] as $group) {
			$inputId = 'button_' . $row->id . '_acl_' . $group->id;
			$inputValue = $group->id;
			$prefix = trim(str_repeat('|&mdash; ', $group->level) . ' ');
			$text = ($prefix != '' ? '<span style="color: #ccc;">'.$prefix.'</span>&nbsp;':'')  . $group->text;
?>
						<input type="checkbox" id="<?php echo $inputId; ?>" name="button_acl[]" value="<?php echo $inputValue; ?>" <?php echo (($group->enabled == '1') ? 'checked="checked"' : '');  ?> />
						<label for="<?php echo $inputId; ?>"><?php echo $text; ?></label>
						<br />
<?php
		}
?>
					</td>
				</tr>
				</table>
			</fieldset>
		</td>
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