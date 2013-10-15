<?php if (!defined('IN_PHPBB')) exit; ?><link href="<?php echo (isset($this->_rootref['T_THEME_PATH'])) ? $this->_rootref['T_THEME_PATH'] : ''; ?>/prime_trash_bin.css" rel="stylesheet" type="text/css" />
<?php if ($this->_rootref['S_POST_DELETED']) {  ?>
<script type="text/javascript"><!--
function show_post(link_obj, post_id)
{
	var e = document.getElementById(post_id);
	if (e)
	{
		var d = e.style.display;
		e.style.display = d == 'none' ? '' : 'none';
		link_obj.innerHTML = d == 'none' ? '<?php echo ((isset($this->_rootref['LA_PRIME_HIDE_DELETED_POST'])) ? $this->_rootref['LA_PRIME_HIDE_DELETED_POST'] : ((isset($this->_rootref['L_PRIME_HIDE_DELETED_POST'])) ? addslashes($this->_rootref['L_PRIME_HIDE_DELETED_POST']) : ((isset($user->lang['PRIME_HIDE_DELETED_POST'])) ? addslashes($user->lang['PRIME_HIDE_DELETED_POST']) : '{ PRIME_HIDE_DELETED_POST }'))); ?>' : '<?php echo ((isset($this->_rootref['LA_PRIME_VIEW_DELETED_POST'])) ? $this->_rootref['LA_PRIME_VIEW_DELETED_POST'] : ((isset($this->_rootref['L_PRIME_VIEW_DELETED_POST'])) ? addslashes($this->_rootref['L_PRIME_VIEW_DELETED_POST']) : ((isset($user->lang['PRIME_VIEW_DELETED_POST'])) ? addslashes($user->lang['PRIME_VIEW_DELETED_POST']) : '{ PRIME_VIEW_DELETED_POST }'))); ?>'
	}
	return(false);
}
// --></script>
<?php } ?>