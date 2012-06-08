<?php
/**
 * JComments - Joomla Comment System
 *
 * Backend content viewer
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

if (JCOMMENTS_JVERSION == '1.0') {
	class JCommentsTabs extends mosTabs
	{
		public function JCommentsTabs($useCookies, $xhtml=NULL)
		{
			$app = JCommentsFactory::getApplication('administrator');

			$cssPath = $app->getCfg('live_site') . '/administrator/components/com_jcomments/assets/tabpane.css';
			if ($xhtml) {
				$app->addCustomHeadTag('<link rel="stylesheet" type="text/css" media="all" href="' . $cssPath . '" id="luna-tab-style-sheet" />');
			} else {
				echo '<link id="luna-tab-style-sheet" type="text/css" rel="stylesheet" href="' . $cssPath . '" />';
			}
			
			$tabpaneFilename = 'tabpane_mini.js';

			if (defined('_ISO2')) {
				$charset = strtolower(_ISO2);
				if ($charset == 'utf-8' || $charset == 'utf8') {
					$tabpaneFilename = 'tabpane.js';
				}
			}

			echo '<script type="text/javascript" src="'. $app->getCfg('live_site') . '/includes/js/tabs/'.$tabpaneFilename.'"></script>';
			$this->useCookies = $useCookies;
		}
	}
} else {
	JLoader::register('JPaneTabs',  JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'pane.php');

	if (!class_exists('JCommentsTabs')) {
		class JCommentsTabs extends JPaneTabs
		{
			var $useCookies = false;

			public function __construct( $useCookies )
			{
				parent::__construct( array('useCookies' => $useCookies) );
			}

			public function startTab( $tabText, $paneid )
			{
				echo $this->startPanel( $tabText, $paneid);
			}

			public function endTab()
			{
				echo $this->endPanel();
			}

			public function startPane( $tabText )
			{
				echo parent::startPane( $tabText );
			}

			function endPane()
			{
				echo parent::endPane();
			}
		}
	}
}

class HTML_JComments
{
	public static function show( $lists )
	{
		$app = JCommentsFactory::getApplication('administrator');
		$user = JCommentsFactory::getUser();
		$config = JCommentsFactory::getConfig();

		$filter = '';
		$filterClear = '';

		if (isset($lists['fog'])) {
			$filter .= ' ' . $lists['fog'];
			$filterClear .= "document.getElementById('fog').value='';";
		}
		if (isset($lists['flang'])) {
			$filter .= ' ' . $lists['flang'];
			$filterClear .= "document.getElementById('flang').value='-1';\n";
		}
		if (isset($lists['foid'])) {
			$filter .= ' ' . $lists['foid'];
			$filterClear .= "document.getElementById('foid').value='';\n";
		}
		if (isset($lists['fauthor']) && $lists['fauthor'] != '') {
			$filter .= ' ' . $lists['fauthor'];
			$filterClear .= "document.getElementById('fauthor').value='';\n";
		}
		if (isset($lists['fstate'])) {
			$filter .= ' ' . $lists['fstate'];
			$filterClear .= "document.getElementById('fstate').value='-1';\n";
		}

		if (JCOMMENTS_JVERSION == '1.0') {
			mosCommonHTML::loadOverlib();
		} else {
			JHTML::_('behavior.tooltip');
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
<link rel="stylesheet" href="<?php echo $app->getCfg('live_site'); ?>/administrator/components/com_jcomments/assets/style.css" type="text/css" />
<script type="text/javascript" src="<?php echo $app->getCfg( 'live_site' );?>/components/com_jcomments/libraries/joomlatune/ajax.js?v=2"></script>
<script type="text/javascript" src="<?php echo $app->getCfg( 'live_site' );?>/administrator/components/com_jcomments/assets/jcomments-backend-v2.1.js"></script>

<form action="<?php echo JCOMMENTS_INDEX; ?>" method="post" name="adminForm" id="adminForm">

<table class="adminheading" width="100%">
	<tr>
<?php
		if (JCOMMENTS_JVERSION == '1.0') {
?>
	<th style="background-image: none; padding: 0;"><img src="components/com_jcomments/assets/icon-48-jcomments.png" width="48" height="48" align="middle" />&nbsp;<?php echo JText::_('A_COMMENTS'); ?></th>
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
<?php
		if (JCOMMENTS_JVERSION == '1.0') {
?>
			<th width="1%"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $lists['rows'] );?>);" /></th>
			<th width="50%" class="title"><?php echo ($config->getInt('comment_title') > 0 ? JText::_('A_COMMENT_TITLE') : JText::_('A_COMMENT_TEXT')); ?></th>
			<th width="12" align="center">@</th>
			<th width="10%" align="left" nowrap="nowrap"><?php echo JText::_('A_COMMENT_NAME'); ?></th>
			<th width="25%" align="left"><?php echo JText::_('A_COMMENT_OBJECT_TITLE'); ?></th>
			<th width="5%" align="left"><?php echo JText::_('A_COMPONENT'); ?></th>
			<th width="5%" nowrap="nowrap"><?php echo JText::_('A_COMMENT_DATE'); ?></th>
			<th width="5%"><?php echo JText::_('A_PUBLISHING'); ?></th>
<?php
		} else {
?>
			<th width="1%"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $lists['rows'] );?>);" /></th>
			<th width="50%" class="title">
<?php
			if ($config->getInt('comment_title') > 0) {
?>
				<?php echo JHTML::_( 'grid.sort', 'A_COMMENT_TITLE', 'c.title', $lists['order_Dir'], $lists['order']); ?>
<?php
			} else {
?>
				<?php echo JText::_('A_COMMENT_TEXT'); ?>
<?php
			}
?>
			</th>
			<th width="12" align="center">@</th>
			<th width="10%" align="left" nowrap="nowrap"><?php echo JHTML::_( 'grid.sort', 'A_COMMENT_NAME', 'c.name', $lists['order_Dir'], $lists['order']); ?></th>
			<th width="25%" align="left"><?php echo JText::_('A_COMMENT_OBJECT_TITLE'); ?></th>
			<th width="5%" align="left"><?php echo JHTML::_( 'grid.sort', 'A_COMPONENT', 'c.object_group', $lists['order_Dir'], $lists['order']); ?></th>
			<th width="5%" nowrap="nowrap"><?php echo JHTML::_( 'grid.sort', 'A_COMMENT_DATE', 'c.date', $lists['order_Dir'], $lists['order']); ?></th>
			<th width="5%" nowrap="nowrap"><?php echo JHTML::_( 'grid.sort', 'A_PUBLISHING', 'c.published', $lists['order_Dir'], $lists['order']); ?></th>
<?php
		}
?>
		</tr>
	</thead>
	<tbody>
<?php
		$config = JCommentsFactory::getConfig();
		$word_maxlength = $config->getInt('word_maxlength');

		for ($i = 0, $k = 0, $n = count($lists['rows']); $i < $n; $i++) {
			$row =& $lists['rows'][$i];

			$subscribed = '';
			$email_icon = '';

			if ($row->subscription > 0) {
				$email_icon = 'subscribed.gif';
			} else if($row->email != '') {
				$email_icon = 'mail.gif';
			}

			if ($email_icon != '') {
				$subscribed = ' <a href="mailto:'.$row->email.'" target="_blank"><img src="components/com_jcomments/assets/'.$email_icon.'" alt="" border="0" /></a>';
			}

			if (empty($row->object_title)) {
				$object_title = JCommentsObjectHelper::getTitle($row->object_id, $row->object_group, $row->lang);
			} else {
				$object_title = $row->object_title;
			}

			if (!$row->deleted) {
				$commentText = $row->comment;
				$commentText = str_replace('<br />', "\n", $commentText);
				$commentText = JCommentsText::cleanText($commentText);
				$commentText = str_replace("\n", '<br />', $commentText);

				// fix long word replacement
				$words = explode(' ', $commentText);
				foreach($words as $word) {
					if ($word_maxlength > 0 && JCommentsText::strlen($word) > $word_maxlength) {
						$commentText = str_replace($word, JCommentsText::wordwrap($word, $word_maxlength, ' ', true), $commentText);
					}
				}
				$commentText = JCommentsText::substr($commentText, 200);
		
				if ($config->getInt('comment_title') > 0) {
					if ($row->title != '') {
						$commentTitle = $row->title;				
					} else {
						$commentTitle = JText::_('A_COMMENT_TITLE_RE') . ' ' . $object_title;
					}
					$commentText = '<span style="font-weight: bold;">'. $commentTitle . '</span><br />' . $commentText;
				}
			} else {
				$commentText = JText::_('A_COMMENT_HAS_BEEN_DELETED');
			}

			$row->link = JCommentsBackendObjectHelper::getLink($row->object_id, $row->object_group, $row->lang);
			$link = JCOMMENTS_INDEX . '?option=com_jcomments&task=comments.edit&hidemainmenu=1&cid='. $row->id;
?>
	<tr class="<?php echo "row$k"; ?>">
			<td align="center"><?php echo JCommentsHTML::_('grid.checkedout', $row, $i); ?></td>
			<td align="left">
<?php
			if ($row->checked_out && ($row->checked_out != $user->id)) {
				echo $commentText;
			} else {
?>
				<a href="<?php echo $link; ?>" title="<?php echo JText::_('A_EDIT'); ?>"><?php echo $commentText; ?></a>
<?php
			}

			if ($row->reports != 0) {
				HTML_JComments::showWarning(JText::sprintf('A_REPORTS_COUNT', $row->reports));
			}
?>
			</td>
			<td align="left"><?php echo $subscribed; ?></td>
			<td align="left"><?php echo JComments::getCommentAuthorName($row); ?><br />
			<a href="http://www.ripe.net/perl/whois?searchtext=<?php echo $row->ip; ?>" target="_blank" title="Whois"><?php echo $row->ip; ?></a></td>
			<td align="left">
<?php
			if (isset($row->link)) {
?>
				<a href="<?php echo $row->link; ?>" title="<?php echo htmlspecialchars($row->title); ?>" target="_blank"><?php echo $object_title; ?></a>
<?php
			} else {
				echo $object_title;
			}
?>
			</td>
			<td align="left">[<?php echo $row->object_group; ?>]</td>
			<td align="center" nowrap="nowrap"><?php echo $row->date; ?></td>
			<td align="center"><?php echo JCommentsHTML::_('grid.published', $row, $i, 'comments.'); ?></td>
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
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JCommentsSecurity::formToken(); ?>
</form>
<?php
	}

	public static function edit( $row, $lists )
	{
		$app = JCommentsFactory::getApplication('administrator');
		$config = JCommentsFactory::getConfig();
		$ajaxUrl = JCommentsFactory::getLink('ajax-backend');
?>
<style type="text/css">
.editbox {border: 1px solid #ccc;padding: 2px;}
.long {width: 450px;}
</style>
<script type="text/javascript" src="<?php echo $app->getCfg( 'live_site' );?>/components/com_jcomments/libraries/joomlatune/ajax.js?v=2"></script>
<script type="text/javascript" src="<?php echo $app->getCfg( 'live_site' );?>/administrator/components/com_jcomments/assets/jcomments-backend-v2.1.js"></script>
<script type="text/javascript">
<!--
<?php if (JCOMMENTS_JVERSION == '1.7') { ?>
Joomla.submitbutton = function (task) {
	if (task == 'comments.cancel') {
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
	if (document.adminForm.comment.value == "") {
		alert("<?php echo addslashes(JText::sprintf('A_FORM_VALIDATE_FIELD_REQUIRED', JText::_('A_COMMENT_TEXT'))); ?>");
	} else {
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
};
<?php } else { ?>
function submitbutton(task)
{
	if (task == 'comments.cancel') {
		submitform(task);
		return;
	}
	if (document.adminForm.comment.value == "") {
		alert("<?php echo JText::sprintf('A_FORM_VALIDATE_FIELD_REQUIRED', JText::_('A_COMMENT_TEXT')); ?>");
	} else {
		submitform(task);
	}
}
<?php } ?>

function JCommentsRemoveReportAJAX(id)
{
	try {
		jtajax.setup({url:'<?php echo $ajaxUrl; ?>'});
		return jtajax.call('JCommentsRemoveReportAjax', arguments, 'post');
	} catch(e) {
		return false;
	}
}

function removeReport(id)
{
	var e = document.getElementById('report-' + id);
	if (e) {
		e.parentNode.removeChild(e);
	}
}

function removeReportList()
{
	var e = document.getElementById('reports');
	if (e) {
		e.parentNode.removeChild(e);
	}
}
//-->
</script>
<form action="<?php echo JCOMMENTS_INDEX; ?>" method="post" name="adminForm" id="adminForm">
<?php
		if (JCOMMENTS_JVERSION == '1.0') {
?>
<table class="adminheading">
	<tr>
		<th style="background-image: none; padding: 0;">
			<img src="components/com_jcomments/assets/icon-48-jcomments.png" width="48" height="48" align="middle" alt="" />&nbsp;<?php echo JText::_('A_COMMENT_EDIT');?>
		</th>
	</tr>
</table>
<?php
		}
?>
<table cellpadding="4" cellspacing="1" border="0" width="100%"	class="adminform">
	<tr valign="top" align="left">
		<td width="15%"><?php echo JText::_('A_COMPONENT'); ?></td>
		<td><?php echo $row->object_group; ?></td>
		<td width="40%" rowspan="8">
<?php
			if (count($lists['reports'])) {
?>
			<fieldset id="reports">
			<legend><?php echo JText::_('A_REPORTS_LIST'); ?></legend>
				<table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminlist">
					<thead>
						<tr>
							<th width="5">#</th>
							<th width="60%"><?php echo JText::_('A_REPORTS_REPORT_REASON'); ?></th>
							<th width="20%"><?php echo JText::_('A_REPORTS_REPORT_NAME'); ?></th>
							<th width="20%"><?php echo JText::_('A_REPORTS_REPORT_DATE'); ?></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
<?php
					$i = 1;
					foreach($lists['reports'] as $report) {
?>
						<tr id="report-<?php echo $report->id;?>">
							<td><?php echo $i; ?></td>
							<td><?php echo $report->reason; ?></td>
							<td><?php echo $report->name; ?><br/ ><?php echo $report->ip; ?></td>
							<td><?php echo $report->date; ?></td>
							<td><a title="<?php echo JText::_('A_REPORTS_REMOVE_REPORT'); ?>" href="#" onclick="JCommentsRemoveReportAJAX('<?php echo $report->id; ?>'); return false;"><img src="components/com_jcomments/assets/delete.gif" alt="<?php echo JText::_('A_REMOVE_REPORT'); ?>" /></a></td>
						</tr>
<?php
						$i++;
					}
?>
					</tbody>
				</table>
			</fieldset>
<?php
			}
?>
		</td>
	</tr>
<?php
		if (!empty($lists['object_title'])) {
?>
	<tr valign="top" align="left">
		<td><?php echo JText::_('A_COMMENT_OBJECT_TITLE'); ?></td>
		<td>
<?php
			if (!empty($lists['object_link'])) {
?>
		<a href="<?php echo $lists['object_link']; ?>" target="_blank"><?php echo $lists['object_title']; ?></a>
<?php
			} else {
				echo $lists['object_title'];
			}
?>
		</td>
	</tr>
<?php
		}
?>
	<tr valign="top" align="left">
		<td><?php echo JText::_('A_COMMENT_DATE'); ?></td>
		<td><?php echo $row->date; ?></td>
	</tr>
	<tr valign="top" align="left">
		<td><label for="author_name"><?php echo JText::_('A_COMMENT_NAME'); ?></label></td>
		<td>
<?php
		if ($row->userid != 0) {
			echo $row->name;
		} else {
?>
			<input type="text" class="editbox long" size="35" id="author_name" name="name" value="<?php echo $row->name; ?>" />
<?php
		}
?>
	</td>
	</tr>
<?php
		if ($row->email != '') {
?>
	<tr valign="top" align="left">
		<td><label for="author_email"><?php echo JText::_('A_COMMENT_EMAIL'); ?></label></td>
		<td><input type="text" class="editbox long" size="35" id="author_email" name="email" value="<?php echo $row->email; ?>" /></td>
	</tr>
<?php
		}
		if ($config->getInt('author_homepage') != 0) {
?>
	<tr valign="top" align="left">
		<td><label for="author_homepage"><?php echo JText::_('A_COMMENT_HOMEPAGE'); ?></label></td>
		<td><input type="text" class="editbox long" size="35" id="author_homepage" name="homepage" value="<?php echo isset($row->homepage) ? $row->homepage : ''; ?>" /></td>
	</tr>
<?php
		}
?>
	<tr valign="top" align="left">
		<td>IP:</td>
		<td><?php echo $row->ip; ?></td>
	</tr>

<?php
		if ($row->title != '') {
?>
	<tr valign="top" align="left">
		<td><label for="comment_title"><?php echo JText::_('A_COMMENT_TITLE'); ?></label></td>
		<td><input type="text" class="editbox long" size="35" id="comment_title" name="title" value="<?php echo $row->title; ?>" /></td>
	</tr>
<?php
		}
?>
	<tr valign="top" align="left">
		<td><label for="comment_text"><?php echo JText::_('A_COMMENT_TEXT'); ?></label></td>
		<td><textarea class="editbox long" cols="25" rows="10" id="comment_text" name="comment"><?php echo $row->comment; ?></textarea></td>
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

	public static function _smileItem($id, $code, $image, $imageList)
	{
		$app = JCommentsFactory::getApplication('administrator');

		if ($image == '') {
			$image_src = $app->getCfg( 'live_site' ) . "/images/blank.png";
		} else {
			$image_src = $app->getCfg( 'live_site' ) . "/components/com_jcomments/images/smiles/". $image;
		}
?>
<div style="white-space: nowrap; height: 24px;" id="jc_smile_<?php echo $id; ?>">
	<a href="javascript:jc_smileDelete('<?php echo $id; ?>')" id="jc_smileDelete_<?php echo $id; ?>" title="<?php echo JText::_('A_SMILES_DELETE'); ?>">
		<img style="vertical-align: middle;" src="components/com_jcomments/assets/delete.gif" alt="<?php echo JText::_('A_SMILES_DELETE'); ?>" width="17" height="17" border="0" />
	</a>
	<a href="javascript:jc_smileUp('<?php echo $id; ?>')" id="jc_smileUp_<?php echo $id; ?>" title="<?php echo JText::_('A_SMILES_MOVE_UP'); ?>">
		<img style="vertical-align: middle;" src="components/com_jcomments/assets/up.gif" alt="<?php echo JText::_('A_SMILES_MOVE_UP'); ?>" width="14" height="12" border="0" />
	</a>
	<a href="javascript:jc_smileDown('<?php echo $id; ?>')" id="jc_smileDown_<?php echo $id; ?>" title="<?php echo JText::_('A_SMILES_MOVE_DOWN'); ?>">
		<img style="vertical-align: middle;" src="components/com_jcomments/assets/down.gif" alt="<?php echo JText::_('A_SMILES_MOVE_DOWN'); ?>" width="14" height="12" border="0" />
	</a>
	<input class="inputbox" name="cfg_smile_codes[<?php echo $id; ?>]" id="jc_smileCode_<?php echo $id; ?>" value="<?php echo htmlspecialchars($code); ?>" style="border: 1px solid #999;" type="text" />
	&nbsp;<?php echo JText::_('A_SMILES_REPLACE_WITH'); ?>&nbsp;
	<select class="inputbox" name="cfg_smile_images[<?php echo $id; ?>]" id="jc_smileImage_<?php echo $id; ?>" onchange="jc_smilePreview(this.getAttribute('id'), this.value)">
		<option value="" selected="selected"></option>
<?php
		foreach($imageList as $img) {
?>
		<option value="<?php echo $img; ?>" <?php echo (($img == $image) ? 'selected="selected"' : ''); ?>><?php echo $img; ?></option>
<?php
		}
?>
	</select>&nbsp;<img src="<?php echo $image_src; ?>" id="jc_smilePreview_<?php echo $id; ?>" alt="" style="vertical-align: middle;" border="0" />&nbsp;</div>
<?php
	}

	public static function showSettings( &$lists )
	{
		$app = JCommentsFactory::getApplication('administrator');
		$config = JCommentsFactory::getConfig();

		$formPosition[] = JCommentsHTML::makeOption( 0, JText::_('AP_FORM_POSITION_AFTER_COMMENTS'));
		$formPosition[] = JCommentsHTML::makeOption( 1, JText::_('AP_FORM_POSITION_BEFORE_COMMENTS'));
		$lists["form_position"] = JCommentsHTML::selectList($formPosition, 'cfg_form_position', 'class="inputbox"', 'value', 'text', $config->get('form_position'));

		$order[] = JCommentsHTML::makeOption('DESC', JText::_('AP_LIST_ORDER_DESCENDING'));
		$order[] = JCommentsHTML::makeOption('ASC', JText::_('AP_LIST_ORDER_ASCENDING'));
		$lists["order"] = JCommentsHTML::selectList($order, 'cfg_comments_order', 'class="inputbox"', 'value', 'text', $config->get('comments_order'));

		$treeOrder[] = JCommentsHTML::makeOption( 0, JText::_('AP_TREE_ORDER_NATURAL'));
		$treeOrder[] = JCommentsHTML::makeOption( 1, JText::_('AP_TREE_ORDER_REVERSE'));
		$treeOrder[] = JCommentsHTML::makeOption( 2, JText::_('AP_TREE_ORDER_COMBINED'));
		$lists["tree_order"] = JCommentsHTML::selectList($treeOrder, 'cfg_tree_order', 'class="inputbox"', 'value', 'text', $config->get('tree_order'));

		$pagination[] = JCommentsHTML::makeOption('top', JText::_('AP_PAGINATION_TOP'));
		$pagination[] = JCommentsHTML::makeOption('bottom', JText::_('AP_PAGINATION_BOTTOM'));
		$pagination[] = JCommentsHTML::makeOption('both', JText::_('AP_PAGINATION_BOTH'));
		$lists["pagination"] = JCommentsHTML::selectList($pagination, 'cfg_comments_pagination', 'class="inputbox"', 'value', 'text', $config->get('comments_pagination'));

		$display_author[] = JCommentsHTML::makeOption('name', JText::_('AP_DISPLAY_AUTHOR_NAME'));
		$display_author[] = JCommentsHTML::makeOption('username', JText::_('AP_DISPLAY_AUTHOR_USERNAME'));
		$lists["display_author"] = JCommentsHTML::selectList($display_author, 'cfg_display_author', 'class="inputbox"', 'value', 'text', $config->get('display_author'));

		$field = array();
		$field[] = JCommentsHTML::makeOption('0', JText::_('AP_FORM_FIELD_DISABLED'));
		$field[] = JCommentsHTML::makeOption('1', JText::_('AP_FORM_FIELD_UNREQUIRED'));
		$field[] = JCommentsHTML::makeOption('2', JText::_('AP_FORM_FIELD_REQUIRED_FOR_UNREGISTERED'));

		$lists["author_email"] = JCommentsHTML::selectList($field, 'cfg_author_email', 'class="inputbox"', 'value', 'text', $config->get('author_email'));
		$lists["author_name"] = JCommentsHTML::selectList($field, 'cfg_author_name', 'class="inputbox"', 'value', 'text', $config->get('author_name'));

		$field = array();
		$field[] = JCommentsHTML::makeOption('0', JText::_('AP_FORM_FIELD_DISABLED'));
		$field[] = JCommentsHTML::makeOption('1', JText::_('AP_FORM_FIELD_UNREQUIRED'));
		$field[] = JCommentsHTML::makeOption('2', JText::_('AP_FORM_FIELD_REQUIRED_FOR_UNREGISTERED'));
		$field[] = JCommentsHTML::makeOption('5', JText::_('AP_FORM_FIELD_UNREQUIRED_FOR_GUESTS_HIDDEN_FOR_USERS'));
		$field[] = JCommentsHTML::makeOption('4', JText::_('AP_FORM_FIELD_REQUIRED_FOR_GUESTS_HIDDEN_FOR_USERS'));
		$field[] = JCommentsHTML::makeOption('3', JText::_('AP_FORM_FIELD_REQUIRED_FOR_ALL'));
		$lists["author_homepage"] = JCommentsHTML::selectList($field, 'cfg_author_homepage', 'class="inputbox"', 'value', 'text', $config->get('author_homepage'));

		$field = array();
		$field[] = JCommentsHTML::makeOption('0', JText::_('AP_FORM_FIELD_DISABLED'));
		$field[] = JCommentsHTML::makeOption('1', JText::_('AP_FORM_FIELD_UNREQUIRED'));
		$field[] = JCommentsHTML::makeOption('3', JText::_('AP_FORM_FIELD_REQUIRED_FOR_ALL'));
		$lists["comment_title"] = JCommentsHTML::selectList($field, 'cfg_comment_title', 'class="inputbox"', 'value', 'text', $config->get('comment_title'));

		$form_show = array();
		$form_show[] = JCommentsHTML::makeOption('1', JText::_('AP_FORM_SHOW_FORM'));
		$form_show[] = JCommentsHTML::makeOption('0', JText::_('AP_FORM_SHOW_LINK'));
		$form_show[] = JCommentsHTML::makeOption('2', JText::_('AP_FORM_SHOW_LINK_IF_ANY_COMMENTS_EXIST'));
		$lists["form_show"] = JCommentsHTML::selectList($form_show, 'cfg_form_show', 'class="inputbox"', 'value', 'text', $config->get('form_show'));
		
		$template_view[] = JCommentsHTML::makeOption('list', JText::_('AP_TEMPLATE_VIEW_LIST'));
		$template_view[] = JCommentsHTML::makeOption('tree', JText::_('AP_TEMPLATE_VIEW_TREE'));
		$lists["template_view"] = JCommentsHTML::selectList($template_view, 'cfg_template_view', 'class="inputbox" onchange="jc_show_template_view(this.value);"', 'value', 'text', $config->get('template_view'));

		$notification = array();
		$notification[] = JCommentsHTML::makeOption(0, JText::_('A_NO'));
		$notification[] = JCommentsHTML::makeOption(1, JText::_('A_YES'));
		$lists["notification"] = JCommentsHTML::selectList($notification, 'cfg_enable_notification', 'class="inputbox" onchange="jc_show_notification_email(this.value);"', 'value', 'text', $config->get('enable_notification'));

		$reports = array();
		$reports[] = JCommentsHTML::makeOption(0, JText::_('A_NO'));
		$reports[] = JCommentsHTML::makeOption(1, JText::_('A_YES'));
		$lists["reports"] = JCommentsHTML::selectList($reports, 'cfg_enable_reports', 'class="inputbox" onchange="jc_show_reports(this.value);"', 'value', 'text', $config->get('enable_reports'));

		$ntypes = explode(',', $config->get('notification_type'));
		$types = array();
		foreach($ntypes as $type) {
			$t = new StdClass();
			$t->value = $type;
			$types[] = $t;
		}
		unset($ntypes);

		$notification_type[] = JCommentsHTML::makeOption(1, JText::_('AP_NOTIFICATION_TYPES_NOTIFICATIONS'));
		$notification_type[] = JCommentsHTML::makeOption(2, JText::_('AP_NOTIFICATION_TYPES_REPORTS'));
		$lists["notification_type"] = JCommentsHTML::selectList($notification_type, 'cfg_notification_type[]', 'class="inputbox" size="2" multiple="multiple"', 'value', 'text', $types);

		$quick_moderation[] = JCommentsHTML::makeOption(0, JText::_('A_NO'));
		$quick_moderation[] = JCommentsHTML::makeOption(1, JText::_('A_YES'));
		$lists["quick_moderation"] = JCommentsHTML::selectList($quick_moderation, 'cfg_enable_quick_moderation', 'class="inputbox"', 'value', 'text', $config->get('enable_quick_moderation'));

		$delete_mode[] = JCommentsHTML::makeOption(0, JText::_('AP_DELETE_MODE_DELETE'));
		$delete_mode[] = JCommentsHTML::makeOption(1, JText::_('AP_DELETE_MODE_MARK'));
		$lists["delete_mode"] = JCommentsHTML::selectList($delete_mode, 'cfg_delete_mode', 'class="inputbox"', 'value', 'text', $config->get('delete_mode'));

		$groupNames = array();
		foreach($lists['group_names'] as $group) {
			$groupNames[] = $group->text;
		}

		if (JCOMMENTS_JVERSION == '1.0') {
			mosCommonHTML::loadOverlib();
		} else {
			JHTML::_('behavior.tooltip');
		}

		$ajaxUrl = JCommentsFactory::getLink('ajax-backend');
?>
<script type="text/javascript" src="<?php echo $app->getCfg( 'live_site' );?>/components/com_jcomments/libraries/joomlatune/ajax.js?v=2"></script>
<script type="text/javascript" src="<?php echo $app->getCfg( 'live_site' );?>/administrator/components/com_jcomments/assets/jcomments-backend-v2.1.js"></script>
<script type="text/javascript">
<!--
function JCommentsSaveSettingsAJAX(func) {
	try{
		var requestURI='<?php echo $ajaxUrl; ?>';
		jtajax.setup({url:requestURI});
		var params = 'jtxf=' + jtajax.encode(func);
		var frm = jtajax.$('adminForm');
		if (frm && frm.tagName.toUpperCase() == 'FORM') {
			var e = frm.elements, query = [];
			for (var i=0; i < e.length; i++) {
				var name = e[i].name, value;
				if (!name) continue;
				if (e[i].type && ('radio' == e[i].type || 'checkbox' == e[i].type) && false === e[i].checked) continue;
				if ('select-multiple' == e[i].type) {
					for (var j = 0; j < e[i].length; j++) {
						if (true === e[i].options[j].selected)
							query.push(name+"="+jtajax.encode(e[i].options[j].value));
					}
				} else { query.push(name+"="+jtajax.encode(e[i].value)); 
				}
			}
			params += '&jtx64=' + encodeURIComponent(jcbackend.base64_encode(query.join('&')));
		}
		jtajax.ajax({type: 'post', data: params});
		return true;

	}catch(e){
		return false;
	}
}

<?php if (JCOMMENTS_JVERSION == '1.7') { ?>
Joomla.submitbutton = function(task) {
	if (task == 'settings.cancel') {
		Joomla.submitform(task, document.getElementById('adminForm'));
		return;
	}

	var disableAJAX = false;
	try {
		var ne = document.getElementById('cfg_enable_notification');
		if (ne) {
			if (ne.value != jc_notification_state) {
				disableAJAX = true;
			}
		}
	} catch (ex) {
	}

	if(jtajax && !disableAJAX) {
		if (task == 'settings.save') {
			JCommentsSaveSettingsAJAX('JCommentsSaveSettingsAjax');
			return;
		} else if (task == 'settings.restore') {
			if (!confirm('<?php echo addslashes(JText::_('A_SETTINGS_CONFIRM_RESTORE_DEFAULT'));?>')) {
				return;
			}
		}
	}
	Joomla.submitform(task, document.getElementById('adminForm'));
};
<?php } else { ?>
function submitbutton(task) {
	if (task == 'settings.cancel') {
		submitform(task);
		return;
	}

	var disableAJAX = false;
	try {
		var ne = document.getElementById('cfg_enable_notification');
		if (ne) {
			if (ne.value != jc_notification_state) {
				disableAJAX = true;
			}
		}
	} catch (ex) {
	}

	if(jtajax && !disableAJAX) {
		if (task == 'settings.save') {
			JCommentsSaveSettingsAJAX('JCommentsSaveSettingsAjax');
			return;
		} else if (task == 'settings.restore') {
			if (!confirm('<?php echo addslashes(JText::_('A_SETTINGS_CONFIRM_RESTORE_DEFAULT'));?>')) {
				return;
			}
		}
	}
	submitform(task);
}
<?php } ?>

function jc_show_notification_email(v){
	var e=document.getElementById('notification_type');if(e){e.style.display=((v==1)?'':'none');}
	e=document.getElementById('notification_email');if(e){e.style.display=((v==1)?'':'none');}
	e=document.getElementById('quick_moderation');if(e){e.style.display=((v==1)?'':'none');}
}

function jc_show_reports(v){
	var e=document.getElementById('reports_max_reports_per_comment');if(e){e.style.display=((v==1)?'':'none');}
	e=document.getElementById('reports_reports_before_unpublish');if(e){e.style.display=((v==1)?'':'none');}
	e=document.getElementById('reports_reports_reason_required');if(e){e.style.display=((v==1)?'':'none');}
}

function jc_show_template_view(v){
	var listViewParams = document.getElementById('list_view_params');
	var treeViewParams = document.getElementById('tree_view_params');
	if(listViewParams){listViewParams.style.display=((v=='list')?'':'none');}
	if(treeViewParams){treeViewParams.style.display=((v=='list')?'none':'');}
}

var jc_notification_state = <?php echo $config->getInt('enable_notification'); ?>;
var jc_usergroupsNames = new Array("<?php echo implode('","', $groupNames); ?>");
var jc_usergroupsKeys = new Array("<?php array_walk($groupNames, create_function('&$val', '$val = md5($val);')); echo implode('","', $groupNames); ?>");
var jc_selected_group = jc_usergroupsKeys[0];
function jc_showgroup(value) {
	var gn,ge,ce,he = document.getElementById('groupheader');

	if (value.length != 32) {
		value = jc_usergroupsKeys[0];
	}
	for(var i=0;i<jc_usergroupsNames.length;i++) {
		gn = jc_usergroupsKeys[i];
		ge = document.getElementById(gn);
		ce = document.getElementById('jc_'+gn);

		if (gn == value) {
			document.cookie = 'jcommentsadmin_group=' + jc_usergroupsKeys[i] + '; path=/';
			he.innerHTML = '<?php echo addslashes(JText::_('A_RIGHTS_GROUP_DESC')); ?>'+' <span style="color: green">'+jc_usergroupsNames[i]+'</span>';
			ge.style.display = '';
			ce.className = 'active';
		}
	}

	if (jc_selected_group != value) {
		ge = document.getElementById(jc_selected_group);
		ce = document.getElementById('jc_'+jc_selected_group);
		ge.style.display = 'none';
		ce.className = 'nonactive';
	}

	jc_selected_group = value;
}
//-->
</script>
<style type="text/css">

#jcomments-message {padding: 0 0 0 25px;margin: 0; width: auto; float: right; font-size: 14px; font-weight: bold;}
.jcomments-message-error {background: transparent url(components/com_jcomments/assets/error.gif) no-repeat 4px 50%; color: red;}
.jcomments-message-info {background: transparent url(components/com_jcomments/assets/info.gif) no-repeat 4px 50%; color: green;}

#jc fieldset.adminform {margin: 0}
#jc fieldset.adminform textarea {width: 300px}
#jc fieldset.adminform textarea.short {width: 200px}
#jc table.admintable tr {text-align: left; vertical-align: top}
#jc table.admintable li {margin: 0 0 0 15px; padding: 3px; list-style: disc outside none}
#jc div.current {width: auto !important;}
#jc div.current fieldset {border: 1px #ccc solid;}
#jc div.current label {display: inline !important; float: none !important;}
#jc div.current input {display: inline !important; float: none !important; clear: none;}
#jc img { vertical-align: middle; }
#jc textarea { border: 1px solid #ccc; }
#jc .inputbox { border: 1px solid #ccc; padding: 2px 2px 2px 2px; margin: 2px 0; }
#jc select.categories {width: 200px;}
#jc input:focus,#jc select:focus,#jc textarea:focus { background-color: #ffd }
#jc table.rights tr td div table tr td {text-align: left;}
#jc select#lang {float: none; clear: none;}

table.rights td.active,table.rights td.nonactive,table.rights td.container,table.rights td.top-spacer,table.rights td.bottom-spacer { margin: 0; width: 150px;}
table.rights td.top-spacer { border-right: 1px solid #5194CB; line-height: 3px; height: 3px; }
table.rights td.bottom-spacer { border-right: 1px solid #5194CB; height: 100%; }
table.rights td.nonactive { padding: 0 0 0 8px; cursor: pointer; border-right: 1px solid #5194CB; }
table.rights td.active { padding: 0 0 0 5px; border-top: 1px solid #5194CB; border-bottom: 1px solid #5194CB; border-left: 3px solid #5194CB; border-right: 1px solid #fff; background-color: #fff; }
table.rights td.container { margin: 0; padding: 5px; border-top: 1px solid #5194CB; border-bottom: 1px solid #5194CB; border-right: 1px solid #5194CB; background-color: #fff; }
* html ul.tabs { margin: 10px -3px 0 0 !important; }
* html .rights_table { margin: 0 0 0 -3px !important; }
* html .rights_table input.inputbox { border: 0 !important; background-color: #fff !important; }
</style>
<div id="jc">
<form action="<?php echo JCOMMENTS_INDEX; ?>" method="post" name="adminForm" id="adminForm">
<?php
		if (JCOMMENTS_JVERSION == '1.0') {
?>
<table class="adminheading">
	<tr>
		<th style="background-image: none; padding: 0;"><img src="components/com_jcomments/assets/icon-48-settings.png" width="48" height="48" align="middle">&nbsp;<?php echo JText::_('A_SETTINGS'); ?></th>
	</tr>
</table>
<?php
		}
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td align="left">
<?php
		if (isset($lists['languages'])) {
?>
			<div style="text-align:left;">
				<?php echo JText::_('A_SETTINTS_LANGUAGE'); ?> <?php echo $lists['languages']; ?>
			</div>
<?php
			// TODO: add an option to disable comments separation by language(multilingual_support)
		} else {
			echo '&nbsp;';
		}
?>
		</td>
		<td width="50%" align="right"><div id="jcomments-message-holder">&nbsp;</div></td>
	</tr>
</table>

<?php
		$tabs = new JCommentsTabs( 1 );
		$tabs->startPane( 'com_jcomments' );
		$tabs->startTab(JText::_('A_COMMON'), "common");
?>
<fieldset class="adminform"><legend><?php echo JText::_('A_CATEGORIES')?></legend>
<table class="admintable" width="100%">
	<tr>
		<td width="20%"><?php echo JText::_('AP_CATEGORIES'); ?></td>
		<td width="30%"><?php echo $lists['categories']; ?></td>
		<td width="50%"><?php echo JText::_('AP_CATEGORIES_DESC'); ?></td>
	</tr>
</table>
</fieldset>

<fieldset class="adminform"><legend><?php echo JText::_('A_NOTIFICATIONS'); ?></legend>
<table class="admintable" width="100%">
	<tr>
		<td width="20%"><?php echo JText::_('AP_ENABLE_NOTIFICATION'); ?></td>
		<td width="30%"><?php echo $lists["notification"]; ?></td>
		<td width="50%"><?php echo JText::_('AP_ENABLE_NOTIFICATION_DESC'); ?></td>
	</tr>
<?php
	$style = ($config->getInt('enable_notification') == 0) ? 'style="display: none"' : '';
?>
	<tr id="notification_type" <?php echo $style; ?>>
		<td width="20%"><?php echo JText::_('AP_NOTIFICATION_TYPES'); ?></td>
		<td width="30%"><?php echo $lists["notification_type"]; ?></td>
		<td width="50%"></td>
	</tr>
	<tr id="notification_email" <?php echo $style; ?>>
		<td><?php echo JText::_('AP_ENABLE_NOTIFICATION_EMAIL'); ?></td>
		<td><input type="text" class="inputbox" size="35" name="cfg_notification_email" value="<?php echo $config->get('notification_email'); ?>" /></td>
		<td><?php echo JText::_('AP_ENABLE_NOTIFICATION_EMAIL_DESC'); ?></td>
	</tr>
	<tr id="quick_moderation" <?php echo $style; ?>>
		<td><?php echo JText::_('AP_ENABLE_QUICK_MODERATION'); ?></td>
		<td><?php echo $lists["quick_moderation"]; ?></td>
		<td><?php echo JText::_('AP_ENABLE_QUICK_MODERATION_DESC'); ?></td>
	</tr>
</table>
</fieldset>

<fieldset class="adminform"><legend><?php echo JText::_('A_MISC'); ?></legend>
<table class="admintable" width="100%">
	<tr>
		<td width="20%"><?php echo JText::_('AP_ENABLE_RSS'); ?></td>
		<td width="30%"><?php echo JCommentsHTML::yesnoSelectList( 'cfg_enable_rss', 'class="inputbox"', $config->get('enable_rss'), JText::_('A_YES'), JText::_('A_NO')  ); ?></td>
		<td width="50%"><?php echo JText::_('AP_ENABLE_RSS_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_FEED_LIMIT'); ?></td>
		<td><input type="text" class="inputbox" size="5" name="cfg_feed_limit" value="<?php echo $config->getInt('feed_limit', 100); ?>" /></td>
		<td><?php echo JText::_('AP_FEED_LIMIT_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_USE_PLUGINS'); ?></td>
		<td><?php echo JCommentsHTML::yesnoSelectList( 'cfg_enable_mambots', 'class="inputbox"', $config->get('enable_mambots'), JText::_('A_YES'), JText::_('A_NO')  ); ?></td>
		<td><?php echo JText::_('AP_USE_PLUGINS_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_ALWAYS_EMBED_COMMENTS_INTO_PAGE_SOURCE'); ?></td>
		<td><?php echo JCommentsHTML::yesnoSelectList( 'cfg_load_cached_comments', 'class="inputbox"', $config->get('load_cached_comments'), JText::_('A_YES'), JText::_('A_NO')  ); ?></td>
		<td><?php echo JText::_('AP_ALWAYS_EMBED_COMMENTS_INTO_PAGE_SOURCE_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_DELETE_MODE'); ?></td>
		<td><?php echo $lists["delete_mode"]; ?></td>
		<td></td>
	</tr>
</table>
</fieldset>

<fieldset class="adminform"><legend><?php echo JText::_('A_REPORTS'); ?></legend>
<table class="admintable" width="100%">
	<tr>
		<td width="20%"><?php echo JText::_('AP_ENABLE_REPORTS'); ?></td>
		<td width="30%"><?php echo $lists["reports"]; ?></td>
		<td width="50%"><?php echo JText::_('AP_ENABLE_REPORTS_DESC'); ?></td>
	</tr>
<?php
	$style = ($config->getInt('enable_reports') == 0) ? 'style="display: none"' : '';
?>
	<tr id="reports_max_reports_per_comment" <?php echo $style; ?>>
		<td><?php echo JText::_('AP_MAX_REPORTS_PER_COMMENT'); ?></td>
		<td><input type="text" class="inputbox" size="5" name="cfg_reports_per_comment" value="<?php echo $config->getInt('reports_per_comment'); ?>" /></td>
		<td><?php echo JText::_('AP_MAX_REPORTS_PER_COMMENT_DESC'); ?></td>
	</tr>
	<tr id="reports_reports_before_unpublish" <?php echo $style; ?>>
		<td><?php echo JText::_('AP_REPORTS_BEFORE_UNPUBLISH'); ?></td>
		<td><input type="text" class="inputbox" size="5" name="cfg_reports_before_unpublish" value="<?php echo $config->getInt('reports_before_unpublish'); ?>" /></td>
		<td><?php echo JText::_('AP_REPORTS_BEFORE_UNPUBLISH_DESC'); ?></td>
	</tr>
	<tr id="reports_reports_reason_required" <?php echo $style; ?>>
		<td><?php echo JText::_('AP_REPORT_REASON_REQUIRED'); ?></td>
		<td><?php echo JCommentsHTML::yesnoSelectList( 'cfg_report_reason_required', 'class="inputbox"', $config->get('report_reason_required'), JText::_('A_YES'), JText::_('A_NO')  ); ?></td>
		<td><?php echo JText::_('AP_REPORT_REASON_REQUIRED_DESC'); ?></td>
	</tr>
</table>
</fieldset>

<?php
		$tabs->endTab();
		$tabs->startTab(JText::_('A_LAYOUT'), "layout");
?>
<fieldset class="adminform"><legend><?php echo JText::_('A_VIEW'); ?></legend>
<table class="admintable" width="100%">
	<tr>
		<td width="20%"><?php echo JText::_('AP_TEMPLATE'); ?></td>
		<td width="30%"><?php echo $lists["templates"]; ?></td>
		<td width="50%"><?php echo JText::_('AP_TEMPLATE_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_ENABLE_SMILES'); ?></td>
		<td><?php echo JCommentsHTML::yesnoSelectList( 'cfg_enable_smiles', 'class="inputbox"', $config->get('enable_smiles'), JText::_('A_YES'), JText::_('A_NO')  ); ?></td>
		<td><?php echo JText::_('AP_ENABLE_SMILES_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_ENABLE_CUSTOM_BBCODE'); ?></td>
		<td><?php echo JCommentsHTML::yesnoSelectList( 'cfg_enable_custom_bbcode', 'class="inputbox"', $config->get('enable_custom_bbcode'), JText::_('A_YES'), JText::_('A_NO')  ); ?></td>
		<td><?php echo JText::_('AP_ENABLE_CUSTOM_BBCODE_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_ENABLE_VOTING'); ?></td>
		<td><?php echo JCommentsHTML::yesnoSelectList( 'cfg_enable_voting', 'class="inputbox"', $config->get('enable_voting'), JText::_('A_YES'), JText::_('A_NO')  ); ?></td>
		<td><?php echo JText::_('AP_ENABLE_VOTING_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_DISPLAY_AUTHOR'); ?></td>
		<td><?php echo $lists["display_author"]; ?></td>
		<td><?php echo JText::_('AP_DISPLAY_AUTHOR_DESC'); ?></td>
	</tr>
</table>
</fieldset>

<fieldset class="adminform"><legend><?php echo JText::_('A_LIST_PARAMS'); ?></legend>
<table class="admintable" width="100%">
	<tr>
		<td width="20%"><?php echo JText::_('AP_TEMPLATE_VIEW'); ?></td>
		<td width="30%"><?php echo $lists["template_view"]; ?></td>
		<td width="50%"><?php echo JText::_('AP_TEMPLATE_VIEW_DESC'); ?></td>
	</tr>
</table>
<?php
	$listStyle = ($config->get('template_view', 'list') == 'tree') ? 'style="display: none"' : '';
	$treeStyle = ($config->get('template_view', 'list') == 'tree') ? '' : 'style="display: none"';
?>
<table class="admintable" id="tree_view_params" width="100%" <?php echo $treeStyle; ?>>
	<tr>
		<td width="20%"><?php echo JText::_('AP_TREE_ORDER'); ?></td>
		<td width="30%"><?php echo $lists["tree_order"]; ?></td>
		<td width="50%"><?php echo JText::_('AP_TREE_ORDER_DESC'); ?></td>
	</tr>
</table>
<table class="admintable" id="list_view_params" width="100%" <?php echo $listStyle; ?>>
	<tr>
		<td width="20%"><?php echo JText::_('AP_LIST_ORDER'); ?></td>
		<td width="30%"><?php echo $lists["order"]; ?></td>
		<td width="50%"><?php echo JText::_('AP_LIST_ORDER_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_COMMENTS_PER_PAGE'); ?></td>
		<td><input type="text" class="inputbox" size="5" name="cfg_comments_per_page" value="<?php echo $config->getInt('comments_per_page'); ?>" /></td>
		<td><?php echo JText::_('AP_COMMENTS_PER_PAGE_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_COMMENTS_PAGE_LIMIT'); ?></td>
		<td><input type="text" class="inputbox" size="5" name="cfg_comments_page_limit" value="<?php echo $config->getInt('comments_page_limit'); ?>" /></td>
		<td><?php echo JText::_('AP_COMMENTS_PAGE_LIMIT_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_PAGINATION'); ?></td>
		<td><?php echo $lists["pagination"]; ?></td>
		<td><?php echo JText::_('AP_PAGINATION_DESC'); ?></td>
	</tr>
</table>

</fieldset>

<fieldset class="adminform"><legend><?php echo JText::_('A_FORM_PARAMS'); ?></legend>
<table class="admintable" width="100%">
	<tr>
		<td width="20%"><?php echo JText::_('AP_FORM_SHOW'); ?></td>
		<td width="30%"><?php echo $lists["form_show"]; ?></td>
		<td width="50%"><?php echo JText::_('AP_FORM_SHOW_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_FORM_POSITION'); ?></td>
		<td><?php echo $lists["form_position"]; ?></td>
		<td><?php echo JText::_('AP_FORM_POSITION_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_FORM_FIELD_AUTHOR_NAME'); ?></td>
		<td><?php echo $lists["author_name"]; ?></td>
		<td><?php echo JText::_('AP_FORM_FIELD_AUTHOR_NAME_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_FORM_FIELD_AUTHOR_EMAIL'); ?></td>
		<td><?php echo $lists["author_email"]; ?></td>
		<td><?php echo JText::_('AP_FORM_FIELD_AUTHOR_EMAIL_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_FORM_FIELD_AUTHOR_HOMEPAGE'); ?></td>
		<td><?php echo $lists["author_homepage"]; ?></td>
		<td></td>
	</tr>     
	<tr>
		<td><?php echo JText::_('AP_FORM_FIELD_COMMENT_TITLE'); ?></td>
		<td><?php echo $lists["comment_title"]; ?></td>
		<td></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_SHOW_COMMENTLENGTH'); ?></td>
		<td><?php echo JCommentsHTML::yesnoSelectList( 'cfg_show_commentlength', 'class="inputbox"', $config->getInt('show_commentlength'), JText::_('A_SHOW'), JText::_('A_HIDE')  ); ?></td>
		<td><?php echo JText::_('AP_SHOW_COMMENTLENGTH_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_FORM_FIELD_CAPTCHA'); ?></td>
		<td><?php echo $lists["captcha"]; ?></td>
		<td></td>
	</tr>
</table>

</fieldset>
<?php
		$tabs->endTab();
		$tabs->startTab(JText::_('A_RIGHTS'),"rights");
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="adminForm">
<tr>
	<th align="left"><?php echo JText::_('A_RIGHTS_GROUPS'); ?></th>
	<th align="left"><span id="groupheader"><?php echo JText::_('A_RIGHTS_DESC'); ?></span></th>
</tr>
<tr align="left" valign="top">
	<td colspan="2">
		<table class="rights" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td class="top-spacer">&nbsp;</td>
			<td rowspan="<?php echo count($lists['group_names']) + 2; ?>" class="container">
<?php
		$i = 0;

		foreach($lists['groups'] as $gname=>$glist) {
			$pgColumns = 3;

			$groupName = md5($gname);
?>
				<div id="<?php echo $groupName; ?>" style="display: none">
					<table>
					<tr valign="top">
<?php
			$j = 1;
			foreach($glist as $pname=>$plist) {
?>
						<td width="<?php echo round(100/$pgColumns); ?>%" nowrap="nowrap"><strong><?php echo $pname; ?></strong><br />
<?php
				foreach($plist as $param) {
					$inputName = 'cfg_' . $param['name'] . '[' . $i . ']';
					$inputId = 'cfg_' . $param['name'] . '_' . $i;
					$caption = addslashes(strip_tags($param['label']));
					$message = addslashes($param['note']);
?>
							<input type="checkbox" name="<?php echo $inputName; ?>" id="<?php echo $inputId; ?>" value="<?php echo $param['groupId']; ?>" <?php echo (($param['value'] == '1') ? 'checked="checked"' : '');  ?> <?php echo (($param['value'] == '-1') ? 'disabled="disabled"' : ''); ?> />
<?php
					if (JCOMMENTS_JVERSION == '1.0') {
?>
							<label for="<?php echo $inputId; ?>" onmouseover="return overlib('<?php echo $message; ?>', CAPTION, '<?php echo $caption; ?>', WIDTH, 300);" onmouseout='return nd();'><span class='editlinktip'><?php echo $param['label']; ?></span></label>
<?php
					} else {
?>
							<label for="<?php echo $inputId; ?>"><span class="hasTip" title="<?php echo $caption; ?>::<?php echo $message; ?>"><?php echo $param['label']; ?></span></label>
<?php
					}
?>			
<?php
					if ($param['error'] != '') {
						HTML_JComments::showWarning($param['error']);
					}
?>
							<br />
<?php
				}
				$i++;
?>
						</td>
<?php
				if ($j%$pgColumns == 0 && $j != count($glist)) {
?>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr valign="top">
<?php
				}
				$j++;
			}
?>
					</tr>
					</table>
				</div>
<?php
		}
?>
			</td>
		</tr>
<?php
		$activeGroup = '';

		if (isset($_COOKIE['jcommentsadmin_group'])) {
			$activeGroup = $_COOKIE['jcommentsadmin_group'];
		}

		$i = 0;
		foreach($lists['group_names'] as $group) {
			if ((($activeGroup == '')&&($i==0))
			|| (($activeGroup != '')&&($group->text == $activeGroup))) {
				$selected = 'class="active"';
			} else {
				$selected = 'class="nonactive"';
			}
			$groupName = md5($group->text);
			$prefix = trim(str_repeat('|&mdash; ', $group->level) . ' ');
			$text = ($prefix != '' ? '<span style="color: #ccc;">'.$prefix.'</span>&nbsp;':'')  . $group->text;
?>
		<tr>
			<td <?php echo $selected; ?> id="jc_<?php echo $groupName; ?>" onclick="jc_showgroup('<?php echo $groupName; ?>')"><?php echo $text; ?></td>
		</tr>
<?php
			$i++;
		}
?>
		<tr>
			<td class="bottom-spacer">&nbsp;</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php
		$tabs->endTab();
		$tabs->startTab(JText::_('A_RESTRICTIONS'),"restrictions");
?>
<fieldset class="adminform"><legend><?php echo JText::_('A_RESTRICTIONS'); ?></legend>
<table class="admintable" width="100%">
	<tr>
		<td width="20%"><?php echo JText::_('AP_USERNAME_MAXLENGTH'); ?></td>
		<td width="30%"><input type="text" class="inputbox" size="5" name="cfg_username_maxlength" value="<?php echo $config->getInt('username_maxlength'); ?>" /></td>
		<td width="50%"><?php echo JText::_('AP_USERNAME_MAXLENGTH_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_COMMENT_MINLENGTH'); ?></td>
		<td><input type="text" class="inputbox" size="5" name="cfg_comment_minlength" value="<?php echo $config->getInt('comment_minlength'); ?>" /></td>
		<td><?php echo JText::_('AP_COMMENT_MINLENGTH_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_COMMENT_MAXLENGTH'); ?></td>
		<td><input type="text" class="inputbox" size="5" name="cfg_comment_maxlength" value="<?php echo $config->getInt('comment_maxlength'); ?>" /></td>
		<td><?php echo JText::_('AP_COMMENT_MAXLENGTH_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_WORD_MAXLENGTH'); ?></td>
		<td><input type="text" class="inputbox" size="5" name="cfg_word_maxlength" value="<?php echo $config->getInt('word_maxlength'); ?>" /></td>
		<td><?php echo JText::_('AP_WORD_MAXLENGTH_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_LINK_MAXLENGTH'); ?></td>
		<td><input type="text" class="inputbox" size="5" name="cfg_link_maxlength" value="<?php echo $config->getInt('link_maxlength'); ?>" /></td>
		<td><?php echo JText::_('AP_LINK_MAXLENGTH_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_FLOOD_TIME'); ?></td>
		<td><input type="text" class="inputbox" size="5" name="cfg_flood_time" value="<?php echo $config->getInt('flood_time'); ?>" /></td>
		<td><?php echo JText::_('AP_FLOOD_TIME_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_ENABLE_NESTED_QUOTES'); ?></td>
		<td><?php echo JCommentsHTML::yesnoSelectList( 'cfg_enable_nested_quotes', 'class="inputbox"', $config->get('enable_nested_quotes'), JText::_('A_YES'), JText::_('A_NO')); ?></td>
		<td><?php echo JText::_('AP_ENABLE_NESTED_QUOTES_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_MERGE_TIME'); ?></td>
		<td><input type="text" class="inputbox" size="5" name="cfg_merge_time" value="<?php echo $config->getInt('merge_time'); ?>" /></td>
		<td><?php echo JText::_('AP_MERGE_TIME_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_MAX_COMMENTS_PER_OBJECT'); ?></td>
		<td><input type="text" class="inputbox" size="5" name="cfg_max_comments_per_object" value="<?php echo $config->getInt('max_comments_per_object'); ?>" /></td>
		<td><?php echo JText::_('AP_MAX_COMMENTS_PER_OBJECT_DESC'); ?></td>
	</tr>
</table>
</fieldset>

<fieldset class="adminform"><legend><?php echo JText::_('A_SECURITY'); ?></legend>
<table class="admintable" width="100%">
	<tr>
		<td width="20%"><?php echo JText::_('AP_ENABLE_USERNAME_CHECK'); ?></td>
		<td width="30%"><?php echo JCommentsHTML::yesnoSelectList( 'cfg_enable_username_check', 'class="inputbox"', $config->get('enable_username_check'), JText::_('A_YES'), JText::_('A_NO')); ?></td>
		<td width="50%"><?php echo JText::_('AP_ENABLE_USERNAME_CHECK_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_ENABLE_BLACKLIST'); ?></td>
		<td><?php echo JCommentsHTML::yesnoSelectList( 'cfg_enable_blacklist', 'class="inputbox"', $config->get('enable_blacklist'), JText::_('A_YES'), JText::_('A_NO')); ?></td>
		<td><?php echo JText::_('AP_ENABLE_BLACKLIST_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_FORBIDDEN_NAMES_LIST'); ?></td>
		<td><textarea class="inputbox short" cols="50" rows="10" name="cfg_forbidden_names"><?php echo $config->get('forbidden_names'); ?></textarea></td>
		<td><?php echo JText::_('AP_FORBIDDEN_NAMES_LIST_DESC'); ?></td>
	</tr>
</table>
</fieldset>
<?php
		$tabs->endTab();
		$tabs->startTab(JText::_('A_CENSOR'),"censor");
?>
<fieldset class="adminform"><legend><?php echo JText::_('A_CENSOR_DESC'); ?></legend>
<table class="admintable" width="100%">
	<tr>
		<td width="20%"><?php echo JText::_('AP_BAD_WORDS_LIST'); ?></td>
		<td width="30%"><textarea class="inputbox" cols="50" rows="10" name="cfg_badwords"><?php echo htmlspecialchars($config->get('badwords'), ENT_QUOTES );?></textarea></td>
		<td width="50%"><?php echo JText::_('AP_BAD_WORDS_LIST_DESC'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('AP_CENSOR_REPLACE_WORD'); ?></td>
		<td><input type="text" class="inputbox" size="30" name="cfg_censor_replace_word" value="<?php echo htmlspecialchars($config->get('censor_replace_word'), ENT_QUOTES ); ?>" /></td>
		<td></td>
	</tr>
</table>

</fieldset>
<?php
		$tabs->endTab();
		$tabs->startTab(JText::_('A_MESSAGES'), "messages");
?>
<fieldset class="adminform"><legend><?php echo JText::_('A_MESSAGES_POLICY_POST'); ?></legend>
<table class="admintable" width="100%">
	<tr>
		<td><textarea class="inputbox" cols="50" rows="5" name="cfg_message_policy_post"><?php echo stripslashes($config->get('message_policy_post')); ?></textarea></td>
		<td width="50%"><?php echo JText::_('A_MESSAGES_POLICY_POST_DESC'); ?></td>
	</tr>
</table>
</fieldset>

<fieldset class="adminform"><legend><?php echo JText::_('A_MESSAGES_POLICY_WHOCANCOMMENT'); ?></legend>
<table class="admintable" width="100%">
	<tr>
		<td><textarea class="inputbox" cols="50" rows="5" name="cfg_message_policy_whocancomment"><?php echo stripslashes($config->get('message_policy_whocancomment')); ?></textarea></td>
		<td width="50%"><?php echo JText::sprintf('A_MESSAGES_POLICY_WHOCANCOMMENT_DESC', JText::_('A_MESSAGES_POLICY_WHOCANCOMMENT_DEFAULT')); ?></td>
	</tr>
</table>
</fieldset>

<fieldset class="adminform"><legend><?php echo JText::_('A_MESSAGES_LOCKED'); ?></legend>
<table class="admintable" width="100%">
	<tr>
		<td><textarea class="inputbox" cols="50" rows="5" name="cfg_message_locked"><?php echo stripslashes($config->get('message_locked')); ?></textarea></td>
		<td width="50%"><?php echo JText::_('A_MESSAGES_LOCKED_DESC'); ?></td>
	</tr>
</table>
</fieldset>

<fieldset class="adminform"><legend><?php echo JText::_('A_MESSAGES_BANNED'); ?></legend>
<table class="admintable" width="100%">
	<tr>
		<td><textarea class="inputbox" cols="50" rows="5" name="cfg_message_banned"><?php echo stripslashes($config->get('message_banned')); ?></textarea></td>
		<td width="50%"><?php echo JText::_('A_MESSAGES_BANNED_DESC'); ?></td>
	</tr>
</table>
</fieldset>

<?php
		$tabs->endTab();
		$tabs->endPane();
?>
<script type="text/javascript">
<!--
jc_showgroup('<?php echo $activeGroup; ?>');
//-->
</script>
<input type="hidden" name="task" value="" />
<input type="hidden" name="option" value="com_jcomments" />
<?php echo JCommentsSecurity::formToken(); ?>
</form>
</div>
<?php
	}

	public static function showSmiles( &$lists )
	{
		$app = JCommentsFactory::getApplication('administrator');

		$lastSmileId = count($lists['smiles']) + 1;
?>
<style type="text/css">
#jc input {border: 1px solid #ccc; padding: 2px 2px 2px 2px;}
#jc img {vertical-align: middle;}
#jc textarea {border: 1px solid #ccc;}
#jc input:focus,#jc select:focus,#jc textarea:focus {background-color: #ffd}
</style>
<script type="text/javascript">
<!--
var jc_lastSmileId = <?php echo $lastSmileId; ?>;

function jc_addSmile() {
	var elField = document.getElementById('jc_smile_' + jc_lastSmileId).cloneNode(true);
	document.getElementById('jc_smile_' + jc_lastSmileId).setAttribute('id', 'jc_smile_'+(jc_lastSmileId+1));
	document.getElementById('jc_smileCode_'+jc_lastSmileId).setAttribute('name', 'cfg_smile_codes['+(jc_lastSmileId+1)+']');
	document.getElementById('jc_smileCode_'+jc_lastSmileId).setAttribute('id', 'jc_smileCode_'+(jc_lastSmileId+1));
	document.getElementById('jc_smileImage_'+jc_lastSmileId).setAttribute('name', 'cfg_smile_images['+(jc_lastSmileId+1)+']');
	document.getElementById('jc_smileImage_'+jc_lastSmileId).setAttribute('id', 'jc_smileImage_'+(jc_lastSmileId+1));
	document.getElementById('jc_smilePreview_'+jc_lastSmileId).setAttribute('id', 'jc_smilePreview_'+(jc_lastSmileId+1));
	document.getElementById('jc_smileDelete_'+jc_lastSmileId).setAttribute('href', 'javascript:jc_smileDelete('+(jc_lastSmileId+1)+')');
	document.getElementById('jc_smileDelete_'+jc_lastSmileId).setAttribute('id', 'jc_smileDelete_'+(jc_lastSmileId+1));
	document.getElementById('jc_smileUp_'+jc_lastSmileId).setAttribute('href', 'javascript:jc_smileUp('+(jc_lastSmileId+1)+')');
	document.getElementById('jc_smileUp_'+jc_lastSmileId).setAttribute('id', 'jc_smileUp_'+(jc_lastSmileId+1));
	document.getElementById('jc_smileDown_'+jc_lastSmileId).setAttribute('href', 'javascript:jc_smileDown('+(jc_lastSmileId+1)+')');
	document.getElementById('jc_smileDown_'+jc_lastSmileId).setAttribute('id', 'jc_smileDown_'+(jc_lastSmileId+1));
	jc_lastSmileId = jc_lastSmileId + 1;
	document.getElementById('jc_smileContainer').appendChild(elField);
}

function jc_smileDelete(id) {
	document.getElementById('jc_smileContainer').removeChild(document.getElementById('jc_smile_'+id));
}

function jc_smileUp(id) {
	var elField1 = document.getElementById('jc_smile_'+id);
	var elField2 = document.getElementById('jc_smile_'+id).cloneNode(true);
	for (var i = 0; i < document.getElementById('jc_smileImage_'+id).childNodes.length; i++) {
		if (document.getElementById('jc_smileImage_'+id).childNodes[i].selected) {
			elFieldType1 = i;
			break;
		}
	}

	var elField3 = document.getElementById('jc_smile_'+id).previousSibling;
	if (elField3) {
		while (elField3.nodeType != 1) {
			elField3 = elField3.previousSibling;
			if (!elField3) {
				return;
			}
		}
	} else {
		return;
	}

	document.getElementById('jc_smileContainer').removeChild(elField1);
	document.getElementById('jc_smileContainer').insertBefore(elField2, elField3);
	document.getElementById('jc_smileImage_'+elField2.getAttribute('id').substr(9)).childNodes[elFieldType1].selected = true;
}

function jc_smileDown(id) {
	var elField1 = document.getElementById('jc_smile_'+id).cloneNode(true);

	for (var i = 0; i < document.getElementById('jc_smileImage_'+id).childNodes.length; i++) {
		if (document.getElementById('jc_smileImage_'+id).childNodes[i].selected) {
			elFieldType1 = i;
			break;
		}
	}

	var elField2 = document.getElementById('jc_smile_'+id).nextSibling;
	if (elField2) {
		while (elField2.nodeType != 1) {
			elField2 = elField2.nextSibling;
			if (!elField2) {
				return;
			}
		}
	} else {
		return;
	}

	for (var i = 0; i < document.getElementById('jc_smileImage_'+elField2.getAttribute('id').substr(9)).childNodes.length; i++) {
		if (document.getElementById('jc_smileImage_'+elField2.getAttribute('id').substr(9)).childNodes[i].selected) {
			elFieldType2 = i;
			break;
		}
	}

	var elField3 = elField2;
	elField2 = elField2.cloneNode(true);

	document.getElementById('jc_smileContainer').removeChild(document.getElementById('jc_smile_'+id));
	document.getElementById('jc_smileContainer').replaceChild(elField1, elField3);
	document.getElementById('jc_smileContainer').insertBefore(elField2, document.getElementById('jc_smile_'+id));
	document.getElementById('jc_smileImage_'+id).childNodes[elFieldType1].selected = true;
	document.getElementById('jc_smileImage_'+elField2.getAttribute('id').substr(9)).childNodes[elFieldType2].selected = true;
}

function jc_smilePreview(el, type) {
	var img = document.getElementById('jc_smilePreview_'+el.substr(14));
	if (type != '') {
		img.src = '<?php echo $lists['smiles_path']; ?>' + type;
	} else {
		img.src = '<?php echo $app->getCfg( 'live_site' ) . "/images/blank.png"; ?>';
	}
}
//-->
</script>
<div style="display: none;">
	<?php HTML_JComments::_smileItem( $lastSmileId, '', '', $lists['images']); ?>
</div>

<div id="jc">

<form action="<?php echo JCOMMENTS_INDEX; ?>" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="option" value="com_jcomments">
<input type="hidden" name="task" value="">

<?php
		if (JCOMMENTS_JVERSION == '1.0') {
?>
<table class="adminheading">
	<tr>
		<th style="background-image: none; padding: 0;"><img src="components/com_jcomments/assets/icon-48-smiles.png" width="48" height="48" align="middle" alt="" />&nbsp;<?php echo JText::_('A_SMILES'); ?></th>
	</tr>
</table>
<?php
		}
?>

<table width="100%" border="0" cellpadding="4" cellspacing="2"
	class="adminform">
	<tr>
		<td>
		<div id="jc_smileContainer">
<?php
		if (is_array($lists['smiles'])) {
			$i = 1;
			foreach($lists['smiles'] as $code=>$image) {
				HTML_JComments::_smileItem( $i, $code, $image, $lists['images']);
				$i++;
			}
		}
?>
		</div>
		<br />
		<br />
		<input type="button" onclick="jc_addSmile()" name="addSmile" value="<?php echo JText::_('A_SMILES_ADD'); ?>" /></td>
	</tr>
</table>
<?php echo JCommentsSecurity::formToken(); ?>
</form>
</div>
<?php
	}

	public static function showAbout()
	{
		$app = JCommentsFactory::getApplication('administrator');
		require_once(dirname(__FILE__) . DS . 'install' . DS . 'helpers' . DS . 'installer.php');
		require_once(dirname(__FILE__) . DS . 'version.php');
		$version = new JCommentsVersion();

		if ((version_compare(phpversion(), '5.1.0') >= 0)) {
			date_default_timezone_set('UTC');
		}

		if (JCOMMENTS_JVERSION == '1.7') {
			JHtml::_('behavior.framework');
		}
?>
<script type="text/javascript">
<!--
<?php if (JCOMMENTS_JVERSION == '1.7') { ?>
Joomla.submitbutton = function (task) {
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
<link rel="stylesheet" href="<?php echo $app->getCfg('live_site'); ?>/administrator/components/com_jcomments/assets/style.css?v=<?php echo $version->getVersion(); ?>" type="text/css" />

<div id="jc">
<div class="jcomments-box">
<div class="m">

<table width="95%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50px"><img src="<?php echo $app->getCfg( 'live_site' ); ?>/administrator/components/com_jcomments/assets/icon-48-jcomments.png" border="0" alt="JComments" /></td>
		<td>
			<span class="componentname"><?php echo $version->getLongVersion(); ?></span>
			<span class="componentdate">[<?php echo $version->getReleaseDate(); ?>]</span><br />
			<span class="copyright">&copy; 2006-<?php echo date('Y'); ?> smart (<a href="http://www.joomlatune.ru" target="_blank">JoomlaTune.ru</a> | <a href="http://www.joomlatune.com" target="_blank">JoomlaTune.com</a>). <?php echo JText::_('A_ABOUT_COPYRIGHT');?><br /></span>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<span class="installheader"><?php echo JText::_('A_ABOUT_TESTERS'); ?></span>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<ul>
				<li>Dutch, era, bzzik, GDV, zikkuratvk, ABTOP, SmokerMan, mamahtehok, BBC, Darkick, Alldar, iT)ZevS(, PaLyCH, Yurii Smetana, Aleks_El_Dia, Selim Alamo Bocaz, crazyASD, sherza, voland</li>
	 		</ul>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<span class="installheader"><?php echo JText::_('A_ABOUT_TRANSLATORS'); ?></span>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<ul>
				<li>Arabic - Ashraf Damra</li>
				<li>Belorussian - Samsonau Siarhei, Dmitry Tsesluk, Prywid</li>
				<li>Bengali (Bangladesh) - Nasir Khan Saikat</li>
				<li>Bosnian - Samir Gutu&#263;</li>
				<li>Bulgarian - Ana Vasileva, Alexander Sidorov, Georgi Gerov, Ivo Apostolov</li>
				<li>Catalan (Spain) - Xavier Montana Carreras</li>
				<li>Chinese - Yusuf Wang, moiska</li>
				<li>Croatian - Tomislav Kikic</li>
				<li>Czech - Ale&#353; Drnovsk&yacute;</li>
				<li>Danish - ot2sen, Martin Podolak, Mads</li>
				<li>Dutch - Aapje, Eleonora van Nieuwburg, Pieter Agten, Kaizer M. (Mirjam)</li>
				<li>English - Alexey Brin, ABTOP</li>
				<li>Estonian - Rivo Z&#228;ngov</li>
				<li>Finnish - Sami Haaranen (aka Mortti)</li>
				<li>French - Saber, Jean-Marie Chauvel, Eric Lamy, Max Schmit</li>
				<li>Galician (Spain) - Manuel - Simboloxico Vol.2</li>
				<li>German - Denis Panschinski, Max Schmit, Hermann Herz</li>
				<li>Greek - Lazaros Giannakidis, Chrysovalantis Mochlas</li>
				<li>Hebrew - vollachr</li>
				<li>Hungarian - J&oacute;zsef Tam&aacute;s Herczeg</li>
				<li>Italian - Marco a.k.a. Vamba, Giuseppe Covino, Guido Romano</li>
				<li>Japanese - spursmusasi</li>
				<li>Khmer - Sovann Heng</li>
				<li>Latvian - Igors Maslakovs, Igor Vetruk, Dmitrijs Rekuns</li>
				<li>Lithuanian - Andrewas, abc123</li>
				<li>Norwegian - Helge Johnsen, &Oslash;yvind S&oslash;nderbye</li>
				<li>Persian - hostkaran, ULTIMATE, Mahdi Ahazan (JoomlaFarsi.com)</li>
				<li>Polish - Tomasz Zi&oacute;&#322;czy&#324;ski, Jamniq</li>
				<li>Portuguese (Portugal) - Paulo Izidoro, Pedro Jesus</li>
				<li>Portuguese (Brazil) - Daniel Gomes, Caio Guimaraes, Manoel Silva (iikozen)</li>
				<li>Romanian - zlideni, Dan Partac, Razvan Ciule</li>
				<li>Serbian - Ivan Krkotic, Ivan Milosavljevic</li>
				<li>Slovak - Vladim&iacute;r Proch&aacute;zka</li>
				<li>Slovenian - Dorjano Baruca, Chico</li>
				<li>Spanish - Selim Alamo Bocaz, Miguel Tuyar&#233;</li>
				<li>Spanish (Argentina) - migueliyo17  </li>
				<li>Swedish - MulletMidget</li>
				<li>Thai - Thammatorn Kraikokit, AriesAnywhere</li>
				<li>Turkish - Tolga Sanci</li>
				<li>Ukrainian - Denys Nosov, Yurii Smetana</li>
			</ul>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<span class="installheader"><?php echo JText::_('A_ABOUT_LOGO_DESIGN'); ?></span>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<ul>
				<li>Dmitry Zuzin aka MrDenim</li>
			</ul>
		</td>
	</tr>
</table>

</div>
</div>
</div>
<form action="<?php echo JCOMMENTS_INDEX; ?>" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="option" value="com_jcomments" />
	<input type="hidden" name="task" value="" />
	<?php echo JCommentsSecurity::formToken(); ?>
</form>
<?php
	}

	public static function showWarning($message) 
	{
		if (JCOMMENTS_JVERSION == '1.0') {
?>
	<span onmouseover="return overlib('<?php echo $message; ?>');" onmouseout='return nd();' class="editlinktip">
		<img src="components/com_jcomments/assets/warning.png" alt="" border="" hspace="6" vspace="0" />
	</span>
<?php
		} else {
?>
	<span class="error hasTip" title="<?php echo JText::_('A_WARNING');?>::<?php echo $message; ?>">
		<img src="components/com_jcomments/assets/warning.png" alt="" border="" hspace="6" vspace="0" />
	</span>
<?php
		}
	}

	public static function refreshObjects() 
	{
		$app = JCommentsFactory::getApplication('administrator');

		$hash = md5($app->getCfg('secret'));
		$ajaxUrl = str_replace('/administrator', '', JCommentsFactory::getLink('ajax'));

		$urls = array();
		$urls[""] = $ajaxUrl;

		$languages = JCommentsMultilingual::getLanguages();
		foreach($languages as $language) {
			$urls[$language->value] = str_replace('/administrator', '', JCommentsFactory::getLink('ajax', 0, null, $language->urlcode));
		}

		$js = array();
		foreach($urls as $k => $v) {
			$js[] = '"' . $k . '" : "' . $v . '"';
		}

?>
<link rel="stylesheet" href="<?php echo $app->getCfg('live_site'); ?>/administrator/components/com_jcomments/assets/style.css" type="text/css" />
<script type="text/javascript" src="<?php echo $app->getCfg( 'live_site' );?>/components/com_jcomments/libraries/joomlatune/ajax.js?v=2"></script>
<script type="text/javascript" src="<?php echo $app->getCfg( 'live_site' );?>/administrator/components/com_jcomments/assets/jcomments-backend-v2.1.js"></script>
<style type="text/css">
html {overflow-y: hidden !important;}
</style>
<div style="margin-top: 5px;">
	<div id="jcomments-message-ajax">
		<div id="jcomments-message" class="jcomments-message-wait"><?php echo addslashes(JText::_('A_REFRESH_OBJECTS_INFO_WAIT')); ?></div>
	</div>
</div>
<script type="text/javascript">
<!--
function JCommentsRefreshObjectsAJAX(hash, step, group, language) {
	try{
		var urls = {<?php echo implode(',', $js); ?>};
		var ajaxUrl = language ? (urls[language] ? urls[language] : urls['']) : urls[''];
		jtajax.setup({url:ajaxUrl});
		return jtajax.call('JCommentsRefreshObjects', arguments, 'post');
	}catch(e){
		return false;
	}
}
function JCommentsRefreshObjectsProgress(ready, total) {
	if (ready != total) {
		var percent = Math.ceil((ready / total) * 100);
		var message = '<?php echo addslashes(JText::_('A_REFRESH_OBJECTS_INFO_PROGRESS')); ?>' + ' ' + percent + '% ('+ready+'/'+total+')';
		jcbackend.showMessage(message, 'wait', 'jcomments-message-ajax');
	} else {
		jcbackend.showMessage('<?php echo addslashes(JText::_('A_REFRESH_OBJECTS_INFO_COMPLETE')); ?>', 'info', 'jcomments-message-ajax');
	}
}

JCommentsRefreshObjectsAJAX('<?php echo $hash; ?>', 0);
//-->
</script>
<?php
	}
}
?>