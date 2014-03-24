<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>Добро пожаловать!</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/normalize.min.css">
        <link rel="stylesheet" href="css/main.css">
        <!--Additional CSS Libs-->
        <?php echo $css_lib; ?>

        <style media="screen" class="before-after">
                /*body {display: none;}*/
        </style>
        <!--[if gte IE 9]>
        <style type="text/css">
            .gradient {
                filter: none;
            }
        </style>
        <![endif]-->
        <!--[if lte IE 8]>
        <style type="text/css">
            .main {
                behavior: url(ie-css3.htc);
            }
        </style>behavior: url(ie-css3.htc);
        <![endif]-->

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <div class="header-container">
            <div>
                <header class="head wrapper clearfix">
                    <div class="img-container">
                        <a href="/" ><img src="img/logo.png" alt="Тахопарк лучшее такси в Хабаровске" text="На главную"/></a>
                    </div>
                    <h1>Всегда вовремя, комфортно и надёжно</h1>
                </header>
            </div>
        </div>

        <div class="main-container">
            <div class="main wrapper clearfix">
                <aside>
                    <nav>
                        <ul>
                            <li><a href="#"><h3>Услуги</h3></a></li>
                            <li><a href="#"><h3>Контакты</h3></a></li>
                            <li><a href="#form-doit"><h3>Заказать такси</h3></a></li>
                            <li class="menu-separator"><a href="http://tforum.coreatrade.com"><h3>Форум</h3></a></li>
                            <li class="menu-separator">
                                <dl>
                                    <dt><h3>Расписание</h3></dt>
                                    <dd><a href="#"><h4>Самолётов</h4></a></dd>
                                    <dd><a href="#"><h4>Поездов</h4></a></dd>
                                    <dd><a href="#"><h4>Автобусов</h4></a></dd>
                                    <dd><a href="#"><h4>Пригородных автобусов</h4></a></dd>
                                </dl>
                            </li>
                            <li class="menu-separator"><a href="#"><h3>Водителю</h3></a></li>
                        </ul>
                    </nav>
                    <div class="s-clearfix"></div>
                </aside>
                <div>
                    <!--Main Content-->
                    <?php include $main_block; ?>
                </div>
            </div> <!-- #main -->
        </div> <!-- #main-container -->

        <div class="footer-container">
            <footer class="wrapper">
                <div class="copyright">Разработано <a href="http://www.tmedia.pro/"><img src="img/copyright.png" alt="Техно Медиа Techno Media"></a> <span class="s-nowrap">© ООО «Техно Медиа»</span>
                </div>
            </footer>
        </div>

        <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>-->
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
        <script src="js/vendor/jquery-migrate-1.2.1.js"></script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <!--<script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src='//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>-->

        <script src="js/jquery.textshadow.js"></script>
        <!--Additional JS Libs-->
        <?php echo $script_lib; ?>

        <script>
            $(document).ready(function(){
                $('p').textShadow();
            });
        </script>

        <!--Additional Script-->
        <script>
            <?php include $script_block ?>
        </script>
    </body>
</html>
