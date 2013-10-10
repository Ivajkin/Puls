<!--<link type="text/css" rel="Stylesheet" href="stylesheet.css" />-->
<!-- include the captcha stylesheet -->
<!--<link type="text/css" rel="Stylesheet" href="<?php echo CaptchaUrls::LayoutStylesheetUrl() ?>" />-->
<header>
    <h2>закажите услуги профессионалов</h2>
    <p>По телефонам <a href="tel:+74212757854" class="nowrap">+7(4212) 75-78-54</a>, <a href="tel:+79242005039" class="nowrap">+7(924) 200-50-39</a>.<br />
    Или заполните форму ниже.</p>
</header>
<div class="feedback-form">
    <div>
        <input type="text" name="Name" value="ФИО" autocomplete="on" data-def="ФИО" title="Представьтесь (ФИО)"/>
        <input type="text" name="Email" value="E-MAIL" autocomplete="on" data-def="E-MAIL" title="Ваш e-mail"/>
        <input type="text" name="Phone" value="4212456789" autocomplete="on" data-def="+7 4212-456-789" title="Ваш номер телефона"/>
        <!--<div class="captcha">
            <img src="add/amslider/img/the-battle.jpg" alt="Tecno Media | Техно Медиа" />
        </div>-->
        <?php
        // only show the Captcha if it hasn't been already solved for the current message
        if(/*!$ContactCaptcha->IsSolved*/true) { ?>
            <?php echo $ContactCaptcha->Html(); ?>
            <input type="text" name="captchacode" id="captchacode" value="КОД С КАРТИНКИ" data-def="КОД С КАРТИНКИ" lang="en" title="Введите код с картинки"/>
        <?php }?>
    </div>
    <div class="invisible">...</div>
    <div>
        <div class="chk-wrapper">
            <div class="chk-box clearfix">
                <div class="cl-left bk-adv">
                    <div><input type="checkbox" name="seo" /><span>Поисковое продвижение</span></div>
                    <div><input type="checkbox" name="context" /><span>Контекстная реклама</span></div>
                    <div><input type="checkbox" name="media" /><span>Медийная реклама</span></div>
                </div>
                <div class="cl-right bk-site" style="vertical-align: bottom;">
                    <div><input type="checkbox" name="site" /><span>Создание сайтов</span></div>
                    <div><input type="checkbox" name="support" /><span>Сопровождение сайтов</span></div>
                </div>
            </div>
            <div class="chk-box bk-soft clearfix" style="width: 99%;">
                <div class="cl-left">
                    <div><input type="checkbox" name="apps" /><span>Разработка мобильных приложений</span></div>
                </div>
                <div class="cl-right">
                    <div><input type="checkbox" name="soft" /><span>Разработка сложных программных комплексов</span></div>
                </div>
            </div>
        </div>
        <textarea name="Message" data-def="НАЗВАНИЕ САЙТА, ДОПОЛНИТЕЛЬНАЯ ИНФОРМАЦИЯ ИЛИ ВАШИ КОММЕНТАРИИ" title="Название сайта, дополнительная информация или Ваши комментарии">НАЗВАНИЕ САЙТА, ДОПОЛНИТЕЛЬНАЯ ИНФОРМАЦИЯ ИЛИ ВАШИ КОММЕНТАРИИ</textarea>
        <button>Отправить</button>
    </div>
</div>