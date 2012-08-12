<?php echo 3 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" dir="ltr" >

    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" />
        <!--<base href="http://coreatrade.com" />-->
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Корея Трейд - лучшие автобусы из Кореи</title>
        <link href="images/1.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
        <link rel="stylesheet" href="../css/system.css" type="text/css" />
        <link rel="stylesheet" href="../css/general.css" type="text/css" />
        <link rel="stylesheet" href="../css/reset.css" type="text/css" />
        <link rel="stylesheet" href="../css/typography.css" type="text/css" />
        <link rel="stylesheet" href="../css/forms.css" type="text/css" />
        <link rel="stylesheet" href="../css/modules.css" type="text/css" />
        <link rel="stylesheet" href="../css/joomla.css" type="text/css" />
        <link rel="stylesheet" href="../css/layout.css" type="text/css" />
        <link rel="stylesheet" href="../css/mod_iceslideshow/default/assets/style.css" type="text/css" />
        <link rel="stylesheet" href="../css/template_languages.css" type="text/css" />
        <link rel="stylesheet" href="../css/mod_icemegamenu/css/default_icemegamenu.css" type="text/css" />
        <link rel="stylesheet" type="text/css" href="uploadify.css" />
        <link rel="stylesheet" href="../css/expauto/assets/css/expautospro.css" type="text/css" />
        <link rel="stylesheet" href="../css/expauto/skins/explist/default/css/default.css" type="text/css" />
        <link rel="stylesheet" href="../css/expauto/mod_expautospro_mortgage.css" type="text/css" />
        <link rel="stylesheet" href="../css/expauto/mod_expautospro_stats.css" type="text/css" />
        <link rel="stylesheet" href="../css/expauto/mod_expautospro_categories.css" type="text/css" />
        <link rel="stylesheet" href="../css/expauto/mod_expautospro_listfilter.css" type="text/css" />
        <link rel="stylesheet" href="../css/expauto/mod_expautospro_keyword.css" type="text/css" />

	<script src="../js/jquery-1.7.2.js" type="text/javascript"></script>
	<script type="text/javascript">
             var $j = jQuery.noConflict();
        </script>
	<script src="../js/dproc.js" type="text/javascript"></script>
        <style type="text/css" media="screen">
            /* Select the style */
            /*\*/@import url("../css/style1.css");
            /**/
            /* Right Column Parameters */
            #outer-column-container
            {
                border-right-width: 220px;
            }
            #right-column
            {
                margin-right: -220px;
                width: 220px;
            }
            #middle-column .inside
            {
                padding-right: 15px;
            }
            .mark_show
            {
            	width: 8%;
            }
            .mark_show img
            {
            	width: 60px;
            	height: 60px;
            }
            .table 
            {
                display:block;
                font-weight:bold;
            	float:left;
            }
            .left 
            {
            	width:45%;
            	font-size:1.2em;
            }
            .middle
            {
            	width: 10%;
            }
            .right 
            {
            	width: 50%;
            }
            .newrow 
            {
            	clear:both;
            }
            h1 
            {
            	font-size:2em;
            }
            h2 
            {
            	font-size:1.7em;
                padding-bottom:10px;
                padding-top:20px;
            }
        </style>

        <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="jquery.uploadify-3.1.min.js"></script>
    <script type="text/javascript">
    $(function() {
        $('#file_upload').uploadify({
            'swf'      : 'uploadify.swf',
            'uploader' : 'uploadify.php'
            // Your options here
        });
    });
    var= "<echo $_POST[button]>"
    alert('11');
    </script>

        <!-- Google Fonts -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
        <!--[if lte IE 8]>
            <link rel="stylesheet" type="text/css" href="css/ie.css" />
        <![endif]-->

        <!--[if lte IE 9]>
            <style type="text/css" media="screen">
                #left-column  .col-module h3.mod-title span:after {
                border-width: 0.82em;
            </style>	
        <![endif]-->

    </head>

    <body class="">

     <!-- Content -->
	    <div id="content">
           <div class="wrapper">
                <!-- Columns Container -->    
                <div id="columns-container">
                    <div id="outer-column-container">
                        <div id="inner-column-container" class="clearfix">
                            <div id="source-order-container">
                                <!-- Middle Column -->   
                                <div id="middle-column">                        
                                    <div class="inside"> 
                                        <div id="system-message-container">
                                        </div>
                                        <div class="blog">
      <h1>Форма добавления автомобилей</h1>
      <div class="table middle"></div>
      <div style="display: block; float: left; width:50%">
        <form action="todb.php" method='post' enctype="multipart/form-data" style="padding-bottom:40px">
            <h2>Основная информация</h2>
            <div class="table left">Название<br />(для заголовка)</div>
            <div class="table right">
                <input type="text" name="name"/>
            </div>
            <div class="newrow"></div>
            <div class="table left">Тип</div>
            <div class="table right">
                <select name="type" size=1>
                    <option value=0>Легковая</option>
                    <option value=1 selected>Внедорожник</option>
                    <option value=2>Малогрузная</option>
                    <option value=3>Большегрузная</option>
                    <option value=4>Автобус</option>
                    <option value=5>Микроавтобус</option>
                    <option value=6>Сельхозтехника</option>
                    <option value=7>Спецтехника</option>
                </select>
            </div>
            <div class="newrow"></div>
            <div class="table left">Марка</div>
            <div class="table right">
                <select name="mark" size=1>
                    <option value=0>Chevrolet</option>
                    <option value=1 selected>Daewoo</option>
                    <option value=2>Honda</option>
                    <option value=3>Hyundai</option>
                    <option value=4>KIA</option>
                    <option value=5>SsangYong</option>
                    <option value=6>Toyota</option>
                </select>
            </div>
            <div class="newrow"></div>
            <div class="table left">Модель</div>
            <div class="table right">
                <input type="text" name="model"/>
            </div>
            <div class="newrow"></div>
            <div class="table left">Цена (р.)</div>
            <div class="table right">
                <input type="text" name="price"/>
            </div>
            <div class="newrow"></div>
            <div class="table left">Цвет</div>
            <div class="table right">
                <input type="text" name="color"/>
            </div>
            <div class="newrow"></div>
            <div class="table left">Год</div>
            <div class="table right">
                <input type="text" name="year" />
            </div>
            <div class="newrow"></div>
            <div class="table left">Объём (в литрах)</div>
            <div class="table right">
                <input type="text" name="vvv"/>
            </div>
            <div class="newrow"></div>
            <div class="table left">Топливо</div>
            <div class="table right">
                <input type="text" name="fuel"/>
            </div>
            <div class="newrow"></div>
            <div class="table left">Привод</div>
            <div class="table right">
                <input type="text" name="drive"/>
            </div>
            <div class="newrow"></div>
            <div class="table left">Каробка передач</div>
            <div class="table right">
                <input type="text" name="kpp"/>
            </div>
            <div class="newrow"></div>
            <div class="table left">Мощность (кВт)</div>
            <div class="table right">
                <input type="text" name="power"/>
            </div>
            <div class="newrow"></div>
            <div class="table left">Двери (кол-во)</div>
            <div class="table right">
                <input name="door" type="radio" value="2">2
                <input name="door" type="radio" checked="checked" value="4">4
            </div>
            <div class="newrow"></div>
            <div class="table left">Сиденья (кол-во)</div>
            <div class="table right">
                <input name="seat" type="radio" value="2">2
                <input name="seat" type="radio" value="4">4
                <input name="seat" type="radio" checked="checked" value="5">5
            </div>
            <div class="newrow"></div>

           <!--<h2>Изображения</h2>
            <div class="table left" style="font-size: 0.9em; font-weight:normal">Первое изображение в списке отображается в перечне всех автомобилей</div>
            <div class="table right">
                <input type="file" name="imgfile" id="file_upload" multiple="multiple" accept="image/*" />
            </div>
            <div class="newrow"></div>-->


            <h2>Комплектация</h2>
            <div style="padding-bottom: 10px">Пожалуйста вводите каждый компонент с новой строчки</div>
            <textarea name="complect" cols="60" rows="33">
Пример:
ABS
Airbag д/водителя
Airbag д/пассажира
Бортовой компьютер
ГУР
Иммобилайзер
Климат-контроль
Круиз-контроль
Обогрев сидений
Центральный замок
Airbag боковые
Airbag оконные
ESP	Handsfree
Датчик дождя
Камера заднего хода
Корректор фар
Ксеноновые фары
Обогрев зеркал
Панорамный люк
Парктроник
Подлокотник передний
Противотуманные фары
Разд. спинка задн. сидений
Регул. сид. пасс. по высоте
Электроантенна
Электрозеркала
Магнитола: с MP3
Салон: кожа
Электростёкла: все
Регулировка руля: в 2 пл.</textarea>
            <div style="padding:10px"></div>
            <input type="submit" value="Сохранить данные" />
            &nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" value="Очистить форму" />	
        </form>
      </div>
        <div style="display: block; float: left; width: 50%">
            <h2>Изображения</h2>
            <div class="table left" style="font-size: 0.9em; font-weight:normal">Первое изображение в списке отображается в перечне автомобилей</div>
            <div class="table right" style="width: 50%" >
                <input type="file" name="file_upload" id="file_upload" />               
                <!--<input type="file" name="imgfile" id="file_upload" accept="image/*" />-->
            
            <div class="newrow"></div>
       </div>
       <div class="newrow"></div>
                                        </div>
                                    </div>	
                                </div><!-- Middle Column -->   
                            </div><!-- Source Order Container -->
                            <div class="clear-columns"></div>
                        </div>
                    </div>           
                </div><!-- Columns Container -->         
            </div><!-- Content Main -->
            
            <!-- Bottom -->
            <div id="bottom" class="clearfix">
                <div class="wrapper">
                    <div id="car-logos">
                        <div class="custom"  >
                            <img src="../images/car_logos.jpg" border="0" alt="Car Logos" width="960" height="74" />
                        </div>
                    </div>                                             
                </div>                                   
            </div><!-- Bottom -->      
        </div>
    
    </body>
</html>