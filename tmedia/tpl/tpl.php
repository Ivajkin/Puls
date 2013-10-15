<?php require_once 'namespace.php'; ?>

<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Веб Агентство, Веб Маркетинг / Техно Медиа</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width"> <!--, user-scalable=false; initial-scale=1, maximum-scale=1-->

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
<!--Additional CSS Libs-->
    <?php echo $css_lib; ?>

    <style media="screen" class="before-after">
            /*body {display: none;}*/
    </style>
    <!--[if IE]>
    <style type="text/css">
        .main article section.sect-more>h3:before {
            background: #800098 url('content/service/img/h_back.png') 0 50% no-repeat;
            font-family: 'Segoe UI, Segoe UI Semibold' !important;
        }
    </style>
    <![endif]-->
    <!--[if gte IE 9]>
    <style type="text/css">
        .gradient {
            filter: none;
        }
    </style>
    <![endif]-->

    <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
</head>
<body>
<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to
    improve your experience.</p>
<![endif]-->

<div class="root-container">
    <div class="west-side">
        <!--<div class="invisible">...</div>-->
        <div class="br-up"></div>
        <div class="br-down"></div>
    </div>
    <div class="central-area">
        <header class="head clearfix" role="header">
            <!--<h1 class="title">h1.title</h1>-->
            <nav class="clearfix" role="navigation">
                <a href="/"><img class="simplehide" src="img/ico/about.png" alt="Techno Media Техно Медиа О нас" title="О нас" /><span>О нас</span></a>
                <a href="portfolio.php"><img class="simplehide" src="img/ico/port.png" alt="Techno Media Техно Медиа Портфолио" title="Портфолио" /><span>Портфолио</span></a>
                <a href="/" role="banner"><img src="img/logo.png"
                                                       alt="Техно Медиа -- разработка сайтов и програмного обеспечения"
                                                       title="Техно Медиа -- разработка сайтов и програмного обеспечения"/>
                </a>
                <a href="service.php"><img class="simplehide" src="img/ico/serv.png" alt="Techno Media Техно Медиа Услуги" title="Услуги" /><span>Услуги</span></a>
                <a href="contacts.php"><img class="simplehide" src="img/ico/cont.png" alt="Techno Media Техно Медиа Контакты" title="Контакты" /><span>Контакты</span></a>
            </nav>
            <div class="clearfix" role="telephone">
                <a class="simplelink" style="font-family: 'Segoe UI Semibold';" href="tel: +74212757854">+7(4212) 75-78-54</a>
                <!--Page Title/Header-->
                <?php echo $header_h1; ?>
            </div>
        </header>
        <div class="main clearfix" role="main">
            <!--Main Content-->
            <?php include $main_block; ?>
        </div>
        <!-- #main -->
        <footer class="allend clearfix" role="complementary">
            <!--Additional Content-->
            <?php include $more_block; ?>
        </footer>
    </div>
    <!-- #main-container -->
    <div class="east-side">
        <!--<div class="invisible">...</div>-->
        <div class="br-up"></div>
        <div class="br-down"></div>
    </div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>

<script src="js/plugins.js"></script>
<script src="js/isMobile.min.js"></script>
<script src="add/nicescroll/jquery.nicescroll.min.js"></script>
<script src="js/main.js"></script>
<!--Additional JS Libs-->
<?php echo $script_lib; ?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter22267441 = new Ya.Metrika({id:22267441, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true, trackHash:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/22267441" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<!-- Google Analytics counter -->
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-43900123-1', 'tmedia.pro');
    ga('send', 'pageview');

</script>
<!-- /Google Analytics counter -->
<script>
    /*******************
     *
     * GLOBAL VARS
     *
     ******************/
    var mediaq= 0;
    var mediawidthmobile = 480;
    var mediawidthmin = 768;
    var mediawidth1 = 870;
    var mediawidthmax = 1140;
    //Dynamic border
    var br_top_h, main_h, wside_w, eside_w

    var fix_resize = function () {
        mediaq= $(document).width();
        br_top_h = $('header.head').height();
        main_h = $('div.main').height()+
            parseFloat($('div.main').css('padding-top'))+ parseFloat($('div.main').css('padding-bottom'))+
            parseFloat($('div.main').css('margin-top'))+ parseFloat($('div.main').css('margin-bottom'));
        wside_w = $('.west-side').width();
        eside_w = $('.east-side').width();

        b_angle= 0.95;
        if (mediaq > mediawidthmobile)
            b_angle= 0.88;
        if (mediaq > mediawidthmin)
            b_angle= 0.76;
        if (mediaq > mediawidth1)
            b_angle= 0.6;

        /*$('style.before-after').empty();
        $('style.before-after').append('.head:before, .head:after {border-top-width: ' + br_top_h + 'px;}' +
            '.main:before, .main:after {border-bottom-width: ' + br_top_h + 'px;}'
        );*/
        /*$('header.head:before, header.head:before').css('border-top-width') ;*/

        $('.west-side, .east-side').css('height', main_h + 2 * br_top_h + 'px');
        $('.east-side [class|="br"], .west-side [class|="br"]').css('height', br_top_h + 'px');
        $('.east-side .br-down, .west-side .br-down').css('top', main_h + 'px');

        $('.east-side [class|="br"], .west-side [class|="br"]').empty();
        $('.east-side .br-up').append(
            '<svg xmlns="http://www.w3.org/2000/svg" version="1.1">'+
                '<polygon points="0,0 0,'+br_top_h+' '+eside_w+','+(b_angle*br_top_h)+' '+eside_w+',0" />'+
                //'<path d="M0 0 L0 '+br_top_h+' L'+eside_w+' '+(0.4*br_top_h)+' L'+eside_w+' 0 Z" stroke-width="0" fill="#fff" />'+
             '</svg>'
        );
        $('.east-side .br-down').append(
            '<svg xmlns="http://www.w3.org/2000/svg" version="1.1">'+
                '<polygon points="0,0 0,'+br_top_h+' '+eside_w+','+br_top_h+' '+eside_w+','+((1-b_angle)*br_top_h)+'" />'+
            '</svg>'
        );
        $('.west-side .br-up').append(
            '<svg xmlns="http://www.w3.org/2000/svg" version="1.1">'+
                '<polygon points="0,0 '+wside_w+',0 '+wside_w+','+br_top_h+' 0,'+(b_angle*br_top_h)+'" />'+
            '</svg>'
        );
        $('.west-side .br-down').append(
            '<svg xmlns="http://www.w3.org/2000/svg" version="1.1">'+
                '<polygon points="0,'+((1-b_angle)*br_top_h)+' '+0+','+br_top_h+' '+wside_w+','+br_top_h+' '+wside_w+',0" />'+
            '</svg>'
        );
    }

    $(document).ready(function () {
        fix_resize();
        setTimeout(fix_resize,250);
        setTimeout(fix_resize,1000);
        setTimeout(fix_resize,3000);
    });
    $(window).resize(function () {
        fix_resize();
    });

</script>
<!--Additional Script-->
<script>
    <?php include $script_block ?>
</script>
</body>
</html>
