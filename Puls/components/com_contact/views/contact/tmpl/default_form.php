<script src = "/libraries/jquery-1.8.1.min.js"></script>
	<script type="text/javascript">
        var $j = jQuery.noConflict();

	$j(document).ready(function () {
		$j("#send").click(function(){
                  	if(!$j("#contact_email").attr("value") || !$j("#contact_text").val()){
                  		alert('Вы заполнили не все поля');
                  		return;
                  	}
                        if(!$j("#contact_name").attr("value")){
                  		$j("#contact_name").attr("value", "NoName");
                  	}
                        if(!$j("#contact_subject").attr("value")){
                  		$j("#contact_subject").attr("value", "NoSubject");
                  	}
                  	
			$j.post("/components/com_contact/views/contact/tmpl/sendMail.php",{
				name: $j("#contact_name").attr("value"),
				mail: $j("#contact_email").attr("value"),
				subject: $j("#contact_subject").attr("value"),
				message: $j("#contact_text").val(),
				email_copy: $j("#contact_email_copy").attr("checked")
			     },
                             function(data){
                             	alert('Сообщение успешно отправлено!');
                             });
                  
		});
	});
	</script>
<?php
/** $Id: default_form.php 11917 2009-05-29 19:37:05Z ian $ */
defined( '_JEXEC' ) or die( 'Restricted access' );

        $fullpath= dirname(__FILE__);
        $dirpath= explode("bazisvostokmed.ru", $fullpath);

	$script = '<!--
		function validateForm( frm ) {
			var valid = document.formvalidator.isValid(frm);
			if (valid == false) {
				// do field validation
				if (frm.email.invalid) {
					alert( "' . JText::_( 'Please enter a valid e-mail address.', true ) . '" );
				} else if (frm.text.invalid) {
					alert( "' . JText::_( 'CONTACT_FORM_NC', true ) . '" );
				}
				return false;
			} else {
				frm.submit();
			}
		}
		// -->';
	$document =& JFactory::getDocument();
	$document->addScriptDeclaration($script);
	
	if(isset($this->error)) : ?>
<tr>
	<td><?php echo $this->error; ?></td>
</tr>
<?php endif; ?>
<tr>
	<td colspan="2">
	<br /><br /><!--<?php echo JRoute::_( 'index.php' );?>-->
	<form action="<?php echo $dirpath[1] ?>/sendMail.php" method="post" name="emailForm" id="emailForm" class="form-validate">
		<div class="contact_email<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
			<label for="contact_name">
				&nbsp;<?php echo JText::_( 'Ваше имя' );?>:
			</label>
			<br />
			<input type="text" name="name" id="contact_name" size="30" class="inputbox" value="" />
			<br />
			<label id="contact_emailmsg" for="contact_email">
				&nbsp;<?php echo JText::_( 'Ваш Email' );?>:
			</label>
			<br />
			<input type="text" id="contact_email" name="email" size="30" value="" class="inputbox required validate-email" maxlength="100" />
			<br />
			<label for="contact_subject">
				&nbsp;<?php echo JText::_( 'Тема сообщения' );?>:
			</label>
			<br />
			<input type="text" name="subject" id="contact_subject" size="30" class="inputbox" value="" />
			<br /><br />
			<label id="contact_textmsg" for="contact_text">
				&nbsp;<?php echo JText::_( 'Введите сообщение' );?>:
			</label>
			<br />
			<textarea cols="50" rows="10" name="text" id="contact_text" class="inputbox required"></textarea>
			<?php if ($this->contact->params->get( 'show_email_copy' )) : ?>
			<br />
				<input type="checkbox" name="email_copy" id="contact_email_copy" value="1"  />
				<label for="contact_email_copy">
					<?php echo JText::_( 'Отправить ли копию на ваш почтовый ящик?' ); ?>
				</label>
			<?php endif; ?>
			<br />
			<br />
			
		</div>

	<input type="hidden" name="option" value="com_contact" />
	<input type="hidden" name="view" value="contact" />
	<input type="hidden" name="id" value="<?php echo $this->contact->id; ?>" />
	<input type="hidden" name="task" value="submit" />
	<?php echo JHTML::_( 'form.token' ); ?>
	</form><button id = "send" class="button validate" type="submit"><?php echo JText::_('Отправить'); ?></button>
	<br />
	</td>
</tr>