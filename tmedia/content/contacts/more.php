<!--<link type="text/css" rel="Stylesheet" href="stylesheet.css" />-->
<!-- include the captcha stylesheet -->
<!--<link type="text/css" rel="Stylesheet" href="<?php echo CaptchaUrls::LayoutStylesheetUrl() ?>" />-->
<header>
    <h2>article section h2</h2>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sodales urna non odio egestas tempor. Nunc vel
        vehicula ante. Etiam bibendum iaculis libero, eget molestie nisl pharetra in. In semper consequat est, eu porta
        velit mollis nec. Curabitur posuere enim eget turpis feugiat tempor. Etiam ullamcorper lorem dapibus velit
        suscipit ultrices. Proin in est sed erat facilisis pharetra.</p>
</header>
<div class="feedback-form">
    <div>
        <input type="text" name="Name" value="ФИО" autocomplete="on" data-def="ФИО"/>
        <input type="text" name="Email" value="E-MAIL" autocomplete="on" data-def="E-MAIL"/>
        <!--<div class="captcha">
            <img src="add/amslider/img/the-battle.jpg" alt="Tecno Media | Техно Медиа" />
        </div>-->
        <?php
        // only show the Captcha if it hasn't been already solved for the current message
        if(/*!$ContactCaptcha->IsSolved*/true) { ?>
            <?php echo $ContactCaptcha->Html(); ?>
            <input type="text" name="captchacode" id="captchacode" value="КОД С КАРТИНКИ" data-def="КОД С КАРТИНКИ" lang="en" />
        <?php }?>
    </div>
    <div class="invisible">...</div>
    <div>
        <textarea name="Message" data-def="ТЕКСТ СООБЩЕНИЯ">ТЕКСТ СООБЩЕНИЯ</textarea>
        <button>Отправить</button>
    </div>
</div>