
/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

function toggle(showHideDiv, switchTextDiv, textMore, textClose) {
            var ele = document.getElementById(showHideDiv);
            var text = document.getElementById(switchTextDiv);
                if(ele.style.display == 'block') {
                        ele.style.display = 'none';
                        text.innerHTML = textMore;
                }
                else {
                        ele.style.display = 'block';
                        text.innerHTML = textClose;
                }
            }


