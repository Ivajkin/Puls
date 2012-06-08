<?php
/**
 * JComments - Joomla Comment System
 *
 * Backend toolbar viewer
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

class JCommentsSubMenuHelper
{
	public static function addSubmenu()
	{
		if (JCOMMENTS_JVERSION == '1.7') {
			$task = JRequest::getCmd('task', 'comments');

			JSubMenuHelper::addEntry(
				JText::_('A_SUBMENU_COMMENTS'),
				'index.php?option=com_jcomments&task=comments',
				$task == 'comments'
			);
			JSubMenuHelper::addEntry(
				JText::_('A_SUBMENU_SETTINGS'),
				'index.php?option=com_jcomments&task=settings',
				$task == 'settings'
			);
			JSubMenuHelper::addEntry(
				JText::_('A_SUBMENU_SMILES'),
				'index.php?option=com_jcomments&task=smiles',
				$task == 'smiles'
			);
			JSubMenuHelper::addEntry(
				JText::_('A_SUBMENU_SUBSCRIPTIONS'),
				'index.php?option=com_jcomments&task=subscriptions',
				$task == 'subscriptions'
			);
			JSubMenuHelper::addEntry(
				JText::_('A_SUBMENU_CUSTOM_BBCODE'),
				'index.php?option=com_jcomments&task=custombbcodes',
				$task == 'custombbcodes'
			);
			JSubMenuHelper::addEntry(
				JText::_('A_SUBMENU_BLACKLIST'),
				'index.php?option=com_jcomments&task=blacklist',
				$task == 'blacklist'
			);
			JSubMenuHelper::addEntry(
				JText::_('A_SUBMENU_IMPORT'),
				'index.php?option=com_jcomments&task=import',
				$task == 'import'
			);
			JSubMenuHelper::addEntry(
				JText::_('A_SUBMENU_ABOUT'),
				'index.php?option=com_jcomments&task=about',
				$task == 'about'
			);
		}
	}
}

class JCommentsToolbarButtonHelper
{
	public static function refreshObjects()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			$text = JText::_('A_REFRESH_OBJECTS_INFO');
			echo "<td><a class=\"toolbar\" style=\"width: auto;\" href=\"#\" onclick=\"jcbackend.popup('index3.php?option=com_jcomments&task=refresh.objects','refresh', function(){window.location.reload();});\"><img src=\"components/com_jcomments/assets/icon-32-refresh.png\" alt=\"\" /><br />$text</a></td>";
		} else {
			$bar = JToolBar::getInstance('toolbar');
			$bar->addButtonPath(JPATH_COMPONENT.DS.'classes'.DS.'button');
			$bar->appendButton('JCommentsPopup', 'refresh', 'A_REFRESH_OBJECTS_INFO', 'index.php?option=com_jcomments&amp;task=refresh.objects&amp;tmpl=component', 'window.location.reload();');
		}
	}
}

class JCommentsToolbarHelper
{
	public static function import()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			mosMenuBar::startTable();
			mosMenuBar::spacer();
			JCommentsToolbarButtonHelper::refreshObjects();
			mosMenuBar::spacer();
			mosMenuBar::cancel();
			mosMenuBar::spacer();
			mosMenuBar::endTable();
		} else {
			JToolBarHelper::title(JText::_('A_IMPORT'), 'jcomments-import');
			JCommentsToolbarButtonHelper::refreshObjects();
			JToolBarHelper::cancel();
			JCommentsSubMenuHelper::addSubMenu();
		}
	}

	public static function settings()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			mosMenuBar::startTable();
			mosMenuBar::custom('settings.restore', 'restore.png', 'restore_f2.png', JText::_('A_SETTINGS_RESTORE_DEFAULT'), false);
			mosMenuBar::spacer();
			mosMenuBar::save('settings.save');
			mosMenuBar::spacer();
			mosMenuBar::cancel();
			mosMenuBar::spacer();
			mosMenuBar::endTable();
		} else {
			JToolBarHelper::title(JText::_('A_SETTINGS'), 'jcomments-settings');
			JToolBarHelper::custom('settings.restore', 'restore.png', 'restore_f2.png', 'A_SETTINGS_RESTORE_DEFAULT', false);
			JToolBarHelper::apply('settings.save');
			JToolBarHelper::cancel('settings.cancel');
			JCommentsSubMenuHelper::addSubMenu();
		}
	}

	public static function smiles()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			mosMenuBar::startTable();
			mosMenuBar::save('smiles.save');
			mosMenuBar::spacer();
			mosMenuBar::cancel();
			mosMenuBar::spacer();
			mosMenuBar::endTable();
		} else {
			JToolBarHelper::title(JText::_('A_SMILES'), 'jcomments-smiles');
			JToolBarHelper::save('smiles.save');
			JToolBarHelper::cancel();
			JCommentsSubMenuHelper::addSubMenu();
		}
	}

	public static function postInstall()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			mosMenuBar::startTable();
			mosMenuBar::custom('settings', 'next.png', 'next_f2.png', JText::_('A_INSTALL_BUTTON_NEXT'), false);
			mosMenuBar::endTable();
		}
	}

	public static function about()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			mosMenuBar::startTable();
			mosMenuBar::spacer();
			mosMenuBar::back();
			mosMenuBar::spacer();
			mosMenuBar::endTable();
		} else {
			JToolBarHelper::title(JText::_('A_SUBMENU_ABOUT'), 'jcomments-logo');
			JToolBarHelper::back();
			JCommentsSubMenuHelper::addSubMenu();
		}
	}

	public static function comments()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			mosMenuBar::startTable();
			mosMenuBar::spacer();
			JCommentsToolbarButtonHelper::refreshObjects();
			mosMenuBar::spacer();
			mosMenuBar::publishList('comments.publish');
			mosMenuBar::spacer();
			mosMenuBar::unpublishList('comments.unpublish');
			mosMenuBar::spacer();
			mosMenuBar::editList('comments.edit');
			mosMenuBar::spacer();
			mosMenuBar::deleteList('', 'comments.remove');
			mosMenuBar::endTable();
		} else {
			JToolBarHelper::title(JText::_('A_COMMENTS'), 'jcomments-logo');
			JCommentsToolbarButtonHelper::refreshObjects();
			JToolBarHelper::divider();
			JToolBarHelper::editList('comments.edit');
			JToolBarHelper::divider();
			JToolBarHelper::publishList('comments.publish');
			JToolBarHelper::unpublishList('comments.unpublish');
			JToolBarHelper::divider();
			JToolBarHelper::deleteList('', 'comments.remove');
			JCommentsSubMenuHelper::addSubMenu();
		}
	}

	public static function commentsEdit()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			mosMenuBar::startTable();
			mosMenuBar::spacer();
			mosMenuBar::apply('comments.apply');
			mosMenuBar::spacer();
			mosMenuBar::save('comments.save');
			mosMenuBar::spacer();
			mosMenuBar::cancel('comments.cancel');
			mosMenuBar::endTable();
		} else {
			JToolBarHelper::title(JText::_('A_COMMENT_EDIT'), 'jcomments-logo');
			JToolBarHelper::apply('comments.apply');
			JToolBarHelper::save('comments.save');
			JToolBarHelper::cancel();
		}
	}

	public static function subscriptions()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			mosMenuBar::startTable();
			mosMenuBar::spacer();
			mosMenuBar::publishList('subscription.publish');
			mosMenuBar::spacer();
			mosMenuBar::unpublishList('subscription.unpublish');
			mosMenuBar::spacer();
			mosMenuBar::addNewX('subscription.new');
			mosMenuBar::spacer();
			mosMenuBar::editList('subscription.edit');
			mosMenuBar::spacer();
			mosMenuBar::deleteList('', 'subscription.remove');
			mosMenuBar::endTable();
		} else {
			JToolBarHelper::title(JText::_('A_SUBSCRIPTIONS'), 'jcomments-subscriptions');
			JToolBarHelper::addNewX('subscription.new');
			JToolBarHelper::editList('subscription.edit');
			JToolBarHelper::divider();
			JToolBarHelper::publishList('subscription.publish');
			JToolBarHelper::unpublishList('subscription.unpublish');
			JToolBarHelper::divider();
			JToolBarHelper::deleteList('', 'subscription.remove');
			JCommentsSubMenuHelper::addSubMenu();
		}
	}

	public static function subscriptionsEdit()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			mosMenuBar::startTable();
			mosMenuBar::spacer();
			mosMenuBar::apply('subscription.apply');
			mosMenuBar::spacer();
			mosMenuBar::save('subscription.save');
			mosMenuBar::spacer();
			mosMenuBar::cancel('subscription.cancel');
			mosMenuBar::endTable();
		} else {
			JToolBarHelper::title(JText::_('A_SUBSCRIPTION_EDIT'), 'jcomments-subscriptions');
			JToolBarHelper::apply('subscription.apply');
			JToolBarHelper::save('subscription.save');
			JToolBarHelper::cancel('subscription.cancel');
		}
	}

	public static function subscriptionsNew()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			mosMenuBar::startTable();
			mosMenuBar::spacer();
			mosMenuBar::apply('subscription.apply');
			mosMenuBar::spacer();
			mosMenuBar::save('subscription.save');
			mosMenuBar::spacer();
			mosMenuBar::cancel('subscription.cancel');
			mosMenuBar::endTable();
		} else {
			JToolBarHelper::title(JText::_('A_SUBSCRIPTION_NEW'), 'jcomments-subscriptions');
			JToolBarHelper::apply('subscription.apply');
			JToolBarHelper::save('subscription.save');
			JToolBarHelper::cancel('subscription.cancel');
		}
	}

	public static function customBBCode()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			mosMenuBar::startTable();
			mosMenuBar::spacer();
			mosMenuBar::publishList('custombbcodes.publish');
			mosMenuBar::spacer();
			mosMenuBar::unpublishList('custombbcodes.unpublish');
			mosMenuBar::spacer();
			mosMenuBar::addNewX('custombbcodes.new');
			mosMenuBar::spacer();
			mosMenuBar::editList('custombbcodes.edit');
			mosMenuBar::spacer();
			mosMenuBar::custom( 'custombbcodes.copy', 'copy.png', 'copy_f2.png', JText::_('A_COPY'), true );
			mosMenuBar::spacer();
			mosMenuBar::deleteList('', 'custombbcodes.remove');
			mosMenuBar::endTable();
		} else {
			JToolBarHelper::title(JText::_('A_CUSTOM_BBCODE'), 'jcomments-custombbcodes');
			JToolBarHelper::addNewX('custombbcodes.new');
			JToolBarHelper::editList('custombbcodes.edit');
			JToolBarHelper::custom( 'custombbcodes.copy', 'copy.png', 'copy_f2.png', 'A_COPY', true);
			JToolBarHelper::divider();
			JToolBarHelper::publishList('custombbcodes.publish');
			JToolBarHelper::unpublishList('custombbcodes.unpublish');
			JToolBarHelper::divider();
			JToolBarHelper::deleteList('', 'custombbcodes.remove');
			JCommentsSubMenuHelper::addSubMenu();
		}
	}

	public static function customBBCodeNew()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			mosMenuBar::startTable();
			mosMenuBar::spacer();
			mosMenuBar::apply('custombbcodes.apply');
			mosMenuBar::spacer();
			mosMenuBar::save('custombbcodes.save');
			mosMenuBar::spacer();
			mosMenuBar::cancel('custombbcodes.cancel');
			mosMenuBar::endTable();
		} else {
			JToolBarHelper::title(JText::_('A_CUSTOM_BBCODE_NEW'), 'jcomments-custombbcodes');
			JToolBarHelper::apply('custombbcodes.apply');
			JToolBarHelper::save('custombbcodes.save');
			JToolBarHelper::cancel('custombbcodes.cancel');
		}
	}

	public static function customBBCodeEdit()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			mosMenuBar::startTable();
			mosMenuBar::spacer();
			mosMenuBar::apply('custombbcodes.apply');
			mosMenuBar::spacer();
			mosMenuBar::save('custombbcodes.save');
			mosMenuBar::spacer();
			mosMenuBar::cancel('custombbcodes.cancel');
			mosMenuBar::endTable();
		} else {
			JToolBarHelper::title(JText::_('A_CUSTOM_BBCODE_EDIT'), 'jcomments-custombbcodes');
			JToolBarHelper::apply('custombbcodes.apply');
			JToolBarHelper::save('custombbcodes.save');
			JToolBarHelper::cancel('custombbcodes.cancel');
		}
	}

	public static function blacklist()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			mosMenuBar::startTable();
			mosMenuBar::spacer();
			mosMenuBar::addNewX('blacklist.new');
			mosMenuBar::spacer();
			mosMenuBar::editList('blacklist.edit');
			mosMenuBar::spacer();
			mosMenuBar::deleteList('', 'blacklist.remove');
			mosMenuBar::endTable();
		} else {
			JToolBarHelper::title(JText::_('A_BLACKLIST'), 'jcomments-blacklist');
			JToolBarHelper::addNewX('blacklist.new');
			JToolBarHelper::editList('blacklist.edit');
			JToolBarHelper::divider();
			JToolBarHelper::deleteList('', 'blacklist.remove');
			JCommentsSubMenuHelper::addSubMenu();
		}
	}

	public static function blacklistNew()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			mosMenuBar::startTable();
			mosMenuBar::spacer();
			mosMenuBar::apply('blacklist.apply');
			mosMenuBar::spacer();
			mosMenuBar::save('blacklist.save');
			mosMenuBar::spacer();
			mosMenuBar::cancel('blacklist.cancel');
			mosMenuBar::endTable();
		} else {
			JToolBarHelper::title(JText::_('A_BLACKLIST_NEW'), 'jcomments-blacklist');
			JToolBarHelper::apply('blacklist.apply');
			JToolBarHelper::save('blacklist.save');
			JToolBarHelper::cancel('blacklist.cancel');
		}
	}

	public static function blacklistEdit()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			mosMenuBar::startTable();
			mosMenuBar::spacer();
			mosMenuBar::apply('blacklist.apply');
			mosMenuBar::spacer();
			mosMenuBar::save('blacklist.save');
			mosMenuBar::spacer();
			mosMenuBar::cancel('blacklist.cancel');
			mosMenuBar::endTable();
		} else {
			JToolBarHelper::title(JText::_('A_BLACKLIST_EDIT'), 'jcomments-blacklist');
			JToolBarHelper::apply('blacklist.apply');
			JToolBarHelper::save('blacklist.save');
			JToolBarHelper::cancel('blacklist.cancel');
		}
	}
}
?>