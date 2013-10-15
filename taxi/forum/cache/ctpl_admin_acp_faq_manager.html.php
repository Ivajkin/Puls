<?php if (!defined('IN_PHPBB')) exit; $this->_tpl_include('overall_header.html'); ?>


<a name="maincontent"></a>

<?php if ($this->_rootref['S_UNDO']) {  ?><a href="<?php echo (isset($this->_rootref['U_UNDO'])) ? $this->_rootref['U_UNDO'] : ''; ?>" style="float: <?php echo (isset($this->_rootref['S_CONTENT_FLOW_END'])) ? $this->_rootref['S_CONTENT_FLOW_END'] : ''; ?>;">&laquo; <?php echo ((isset($this->_rootref['L_LOAD_BACKUP'])) ? $this->_rootref['L_LOAD_BACKUP'] : ((isset($user->lang['LOAD_BACKUP'])) ? $user->lang['LOAD_BACKUP'] : '{ LOAD_BACKUP }')); ?></a><?php } ?>


<h1><?php echo ((isset($this->_rootref['L_TITLE'])) ? $this->_rootref['L_TITLE'] : ((isset($user->lang['TITLE'])) ? $user->lang['TITLE'] : '{ TITLE }')); ?></h1>

<p><?php echo ((isset($this->_rootref['L_TITLE_EXPLAIN'])) ? $this->_rootref['L_TITLE_EXPLAIN'] : ((isset($user->lang['TITLE_EXPLAIN'])) ? $user->lang['TITLE_EXPLAIN'] : '{ TITLE_EXPLAIN }')); ?></p>

<p><strong><?php echo (isset($this->_rootref['NAVIGATION'])) ? $this->_rootref['NAVIGATION'] : ''; ?></strong></p>

<?php if ($this->_rootref['S_EDIT'] || $this->_rootref['S_ADD']) {  ?>

	<form id="varedit" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_SETTINGS'])) ? $this->_rootref['L_SETTINGS'] : ((isset($user->lang['SETTINGS'])) ? $user->lang['SETTINGS'] : '{ SETTINGS }')); ?></legend>
		<?php if (! $this->_rootref['S_CAT']) {  ?>

		<dl>
			<dt><label for="var_name"><?php echo ((isset($this->_rootref['L_NAME'])) ? $this->_rootref['L_NAME'] : ((isset($user->lang['NAME'])) ? $user->lang['NAME'] : '{ NAME }')); ?>:</label></dt>
			<dd><input class="text medium" type="text" id="var_name" name="var_name" value="<?php echo (isset($this->_rootref['VARIABLE_NAME'])) ? $this->_rootref['VARIABLE_NAME'] : ''; ?>" maxlength="255" /></dd>
		</dl>
		<?php } ?>

		<dl>
			<dt><label for="var_desc"><?php echo ((isset($this->_rootref['L_VALUE'])) ? $this->_rootref['L_VALUE'] : ((isset($user->lang['VALUE'])) ? $user->lang['VALUE'] : '{ VALUE }')); ?>:</label></dt>
			<dd><textarea id="var_desc" name="var_value" rows="5" cols="45"><?php echo (isset($this->_rootref['VARIABLE_VALUE'])) ? $this->_rootref['VARIABLE_VALUE'] : ''; ?></textarea></dd>
		</dl>
	</fieldset>

	<fieldset class="submit-buttons">
		<input class="button1" type="submit" id="submit" name="submit" value="<?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?>" />&nbsp;
		<input class="button2" type="reset" id="reset" name="reset" value="<?php echo ((isset($this->_rootref['L_RESET'])) ? $this->_rootref['L_RESET'] : ((isset($user->lang['RESET'])) ? $user->lang['RESET'] : '{ RESET }')); ?>" />
	</fieldset>
	<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

	</form>
<?php } if ($this->_rootref['S_DISPLAY_LIST']) {  ?>

	<table cellspacing="1">
		<col class="row1" /><col class="row1" /><col class="row2" />
	<tbody>
	<?php $_file_list_count = (isset($this->_tpldata['file_list'])) ? sizeof($this->_tpldata['file_list']) : 0;if ($_file_list_count) {for ($_file_list_i = 0; $_file_list_i < $_file_list_count; ++$_file_list_i){$_file_list_val = &$this->_tpldata['file_list'][$_file_list_i]; ?>

		<tr>
			<td>
				<strong><?php if ($_file_list_val['U_VIEW']) {  ?><a href="<?php echo $_file_list_val['U_VIEW']; ?>"><?php echo $_file_list_val['NAME']; ?></a><?php } else { echo $_file_list_val['NAME']; } ?></strong>
			</td>
			<td style="vertical-align: top; width: 100px; text-align: right; white-space: nowrap;">
				<?php if ($_file_list_val['S_FIRST_ROW'] && ! $_file_list_val['S_LAST_ROW']) {  ?>

					<?php echo (isset($this->_rootref['ICON_MOVE_UP_DISABLED'])) ? $this->_rootref['ICON_MOVE_UP_DISABLED'] : ''; ?>

					<a href="<?php echo $_file_list_val['U_MOVE_DOWN']; ?>"><?php echo (isset($this->_rootref['ICON_MOVE_DOWN'])) ? $this->_rootref['ICON_MOVE_DOWN'] : ''; ?></a>
				<?php } else if (! $_file_list_val['S_FIRST_ROW'] && ! $_file_list_val['S_LAST_ROW']) {  ?>

					<a href="<?php echo $_file_list_val['U_MOVE_UP']; ?>"><?php echo (isset($this->_rootref['ICON_MOVE_UP'])) ? $this->_rootref['ICON_MOVE_UP'] : ''; ?></a>
					<a href="<?php echo $_file_list_val['U_MOVE_DOWN']; ?>"><?php echo (isset($this->_rootref['ICON_MOVE_DOWN'])) ? $this->_rootref['ICON_MOVE_DOWN'] : ''; ?></a>
				<?php } else if ($_file_list_val['S_LAST_ROW'] && ! $_file_list_val['S_FIRST_ROW']) {  ?>

					<a href="<?php echo $_file_list_val['U_MOVE_UP']; ?>"><?php echo (isset($this->_rootref['ICON_MOVE_UP'])) ? $this->_rootref['ICON_MOVE_UP'] : ''; ?></a>
					<?php echo (isset($this->_rootref['ICON_MOVE_DOWN_DISABLED'])) ? $this->_rootref['ICON_MOVE_DOWN_DISABLED'] : ''; ?>

				<?php } else { ?>

					<?php echo (isset($this->_rootref['ICON_MOVE_UP_DISABLED'])) ? $this->_rootref['ICON_MOVE_UP_DISABLED'] : ''; ?>

					<?php echo (isset($this->_rootref['ICON_MOVE_DOWN_DISABLED'])) ? $this->_rootref['ICON_MOVE_DOWN_DISABLED'] : ''; ?>

				<?php } ?>

				<a href="<?php echo $_file_list_val['U_EDIT']; ?>"><?php echo (isset($this->_rootref['ICON_EDIT'])) ? $this->_rootref['ICON_EDIT'] : ''; ?></a>
				<a href="<?php echo $_file_list_val['U_DELETE']; ?>"><?php echo (isset($this->_rootref['ICON_DELETE'])) ? $this->_rootref['ICON_DELETE'] : ''; ?></a>
			</td>
		</tr>
	<?php }} else { ?>

		<tr>
			<td class="row1" colspan="5" style="text-align: center;"><?php echo ((isset($this->_rootref['L_NO_FAQ_VARS'])) ? $this->_rootref['L_NO_FAQ_VARS'] : ((isset($user->lang['NO_FAQ_VARS'])) ? $user->lang['NO_FAQ_VARS'] : '{ NO_FAQ_VARS }')); ?></td>
		</tr>
	<?php } ?>

	</tbody>
	</table>

	<form id="forums" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

	<fieldset class="quick">
		<input type="hidden" name="action" value="add" />

		<input type="text" name="<?php if (! $this->_rootref['S_CAT']) {  ?>var_name<?php } else { ?>var_value<?php } ?>" value="" maxlength="255" />
		<input class="button2" name="add" type="submit" value="<?php echo ((isset($this->_rootref['L_CREATE'])) ? $this->_rootref['L_CREATE'] : ((isset($user->lang['CREATE'])) ? $user->lang['CREATE'] : '{ CREATE }')); ?>" />
	</fieldset>
	<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

	</form>
<?php } if ($this->_rootref['S_DISPLAY_FILE_LIST']) {  ?>

	<table cellspacing="1">
		<col class="row1" /><col class="row2" /><col class="row2" /><col class="row2" />
	<thead>
	<tr>
		<th><?php echo ((isset($this->_rootref['L_NAME'])) ? $this->_rootref['L_NAME'] : ((isset($user->lang['NAME'])) ? $user->lang['NAME'] : '{ NAME }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_LANGUAGE'])) ? $this->_rootref['L_LANGUAGE'] : ((isset($user->lang['LANGUAGE'])) ? $user->lang['LANGUAGE'] : '{ LANGUAGE }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_LOCATION'])) ? $this->_rootref['L_LOCATION'] : ((isset($user->lang['LOCATION'])) ? $user->lang['LOCATION'] : '{ LOCATION }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_ACTION'])) ? $this->_rootref['L_ACTION'] : ((isset($user->lang['ACTION'])) ? $user->lang['ACTION'] : '{ ACTION }')); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php $_file_list_count = (isset($this->_tpldata['file_list'])) ? sizeof($this->_tpldata['file_list']) : 0;if ($_file_list_count) {for ($_file_list_i = 0; $_file_list_i < $_file_list_count; ++$_file_list_i){$_file_list_val = &$this->_tpldata['file_list'][$_file_list_i]; ?>

		<tr>
			<td><?php echo $_file_list_val['NAME']; ?></td>
			<td><?php echo $_file_list_val['LANGUAGE']; ?></td>
			<td><?php echo $_file_list_val['LOCATION']; ?></td>
			<td><?php echo $_file_list_val['ACTION']; ?></td>
		</tr>
	<?php }} else { ?>

		<tr>
			<td class="row1" colspan="5" style="text-align: center;"><?php echo ((isset($this->_rootref['L_NO_FAQ_FILES'])) ? $this->_rootref['L_NO_FAQ_FILES'] : ((isset($user->lang['NO_FAQ_FILES'])) ? $user->lang['NO_FAQ_FILES'] : '{ NO_FAQ_FILES }')); ?></td>
		</tr>
	<?php } ?>

	</tbody>
	</table>
<?php } $this->_tpl_include('overall_footer.html'); ?>