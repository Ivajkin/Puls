<?php if (!defined('IN_PHPBB')) exit; $this->_tpl_include('overall_header.html'); ?>


<a name="maincontent"></a>

<h1><?php echo ((isset($this->_rootref['L_TITLE'])) ? $this->_rootref['L_TITLE'] : ((isset($user->lang['TITLE'])) ? $user->lang['TITLE'] : '{ TITLE }')); ?></h1>

<?php if ($this->_rootref['S_ERROR']) {  ?>

	<div class="errorbox">
		<h3><?php echo ((isset($this->_rootref['L_WARNING'])) ? $this->_rootref['L_WARNING'] : ((isset($user->lang['WARNING'])) ? $user->lang['WARNING'] : '{ WARNING }')); ?></h3>
		<p><?php echo (isset($this->_rootref['ERROR_MSG'])) ? $this->_rootref['ERROR_MSG'] : ''; ?></p>
	</div>
<?php } $_mods_count = (isset($this->_tpldata['mods'])) ? sizeof($this->_tpldata['mods']) : 0;if ($_mods_count) {for ($_mods_i = 0; $_mods_i < $_mods_count; ++$_mods_i){$_mods_val = &$this->_tpldata['mods'][$_mods_i]; ?>


    <fieldset>
        <legend><?php echo ((isset($this->_rootref['L_VERSION_CHECK'])) ? $this->_rootref['L_VERSION_CHECK'] : ((isset($user->lang['VERSION_CHECK'])) ? $user->lang['VERSION_CHECK'] : '{ VERSION_CHECK }')); ?></legend>
        <p style="font-weight: bold; color: <?php if ($_mods_val['S_UP_TO_DATE']) {  ?>#228822<?php } else { ?>#BC2A4D<?php } ?>;"><?php echo $_mods_val['UP_TO_DATE']; ?></p>
        <dl>
            <dt><label><?php echo ((isset($this->_rootref['L_CURRENT_VERSION'])) ? $this->_rootref['L_CURRENT_VERSION'] : ((isset($user->lang['CURRENT_VERSION'])) ? $user->lang['CURRENT_VERSION'] : '{ CURRENT_VERSION }')); ?></label></dt>
            <dd><strong><?php echo $_mods_val['CURRENT_VERSION']; ?></strong></dd>
        </dl>
        <dl>
            <dt><label><?php echo ((isset($this->_rootref['L_LATEST_VERSION'])) ? $this->_rootref['L_LATEST_VERSION'] : ((isset($user->lang['LATEST_VERSION'])) ? $user->lang['LATEST_VERSION'] : '{ LATEST_VERSION }')); ?></label></dt>
            <dd><strong><?php echo $_mods_val['LATEST_VERSION']; ?></strong></dd>
        </dl>
        <?php if (! $_mods_val['S_UP_TO_DATE']) {  ?>

            <dl>
                <dt><label><?php echo ((isset($this->_rootref['L_DOWNLOAD_LATEST'])) ? $this->_rootref['L_DOWNLOAD_LATEST'] : ((isset($user->lang['DOWNLOAD_LATEST'])) ? $user->lang['DOWNLOAD_LATEST'] : '{ DOWNLOAD_LATEST }')); ?></label></dt>
                <dd><strong><a href="<?php echo $_mods_val['DOWNLOAD']; ?>"><?php echo ((isset($this->_rootref['L_DOWNLOAD'])) ? $this->_rootref['L_DOWNLOAD'] : ((isset($user->lang['DOWNLOAD'])) ? $user->lang['DOWNLOAD'] : '{ DOWNLOAD }')); ?> <?php echo $_mods_val['TITLE']; ?> <?php echo $_mods_val['LATEST_VERSION']; ?></a></strong></dd>
            </dl>
            <dl>
                <dt><label><?php echo ((isset($this->_rootref['L_ANNOUNCEMENT_TOPIC'])) ? $this->_rootref['L_ANNOUNCEMENT_TOPIC'] : ((isset($user->lang['ANNOUNCEMENT_TOPIC'])) ? $user->lang['ANNOUNCEMENT_TOPIC'] : '{ ANNOUNCEMENT_TOPIC }')); ?></label></dt>
                <dd><strong><a href="<?php echo $_mods_val['ANNOUNCEMENT']; ?>"><?php echo ((isset($this->_rootref['L_RELEASE_ANNOUNCEMENT'])) ? $this->_rootref['L_RELEASE_ANNOUNCEMENT'] : ((isset($user->lang['RELEASE_ANNOUNCEMENT'])) ? $user->lang['RELEASE_ANNOUNCEMENT'] : '{ RELEASE_ANNOUNCEMENT }')); ?></a></strong></dd>
            </dl>
        <?php } ?>

    </fieldset>
<?php }} ?>


<form id="acp_board" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

<?php $_options_count = (isset($this->_tpldata['options'])) ? sizeof($this->_tpldata['options']) : 0;if ($_options_count) {for ($_options_i = 0; $_options_i < $_options_count; ++$_options_i){$_options_val = &$this->_tpldata['options'][$_options_i]; if ($_options_val['S_LEGEND']) {  if (! $_options_val['S_FIRST_ROW']) {  ?>

			</fieldset>
		<?php } ?>

		<fieldset>
			<legend><?php echo $_options_val['LEGEND']; ?></legend>
	<?php } else { ?>


		<dl>
			<dt><label for="<?php echo $_options_val['KEY']; ?>"><?php echo $_options_val['TITLE']; ?>:</label><?php if ($_options_val['S_EXPLAIN']) {  ?><br /><span><?php echo $_options_val['TITLE_EXPLAIN']; ?></span><?php } ?></dt>
			<dd><?php echo $_options_val['CONTENT']; ?></dd>
		</dl>

	<?php } }} ?>


	<p class="submit-buttons">
		<input class="button1" type="submit" id="submit" name="submit" value="<?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?>" />&nbsp;
		<input class="button2" type="reset" id="reset" name="reset" value="<?php echo ((isset($this->_rootref['L_RESET'])) ? $this->_rootref['L_RESET'] : ((isset($user->lang['RESET'])) ? $user->lang['RESET'] : '{ RESET }')); ?>" />
	</p>
	<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

</fieldset>
</form>

<?php $this->_tpl_include('overall_footer.html'); ?>