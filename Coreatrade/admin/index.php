<?php
$logon = true;
	if(isset($_REQUEST[session_name()]))
		session_start();
	if (isset($_SESSION['userKey']) AND $_SESSION['ip'] == $_SERVER['REMOTE_ADDR'])
		$logon = true;
	header("Cache-Control: no-cache, must-revalidate"); 
	header("Pragma: no-cache");
	header("Expires: Sat, 26 Jul 1997 00:00:00 GMT"); 
	if ( !preg_match("/MSIE/i", $_SERVER["HTTP_USER_AGENT"]) ) {
		header('Content-type: text/html; charset=utf-8');
	} else {
		header('Content-type: text/html; charset=windows-1251');
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" dir="ltr" >

	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" />
		<!--<base href="http://127.0.0.1" />-->
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Корея Трейд - лучшие автобусы из Кореи</title>
		<link href="images/1.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
		<link rel="stylesheet" href="../css/system.css" type="text/css" />
		<link rel="stylesheet" href="../css/general.css" type="text/css" />
		<link rel="stylesheet" href="../css/reset.css" type="text/css" />
		<link rel="stylesheet" href="../css/typography.css" type="text/css" />
		<link rel="stylesheet" href="../css/forms.css" type="text/css" />
		<link rel="stylesheet" href="../css/modules.css" type="text/css" />
		<link rel="stylesheet" href="../css/maincss.css" type="text/css" />
		<link rel="stylesheet" href="../css/layout.css" type="text/css" />
		<link rel="stylesheet" href="../css/mod_iceslideshow/default/assets/style.css" type="text/css" />
		<link rel="stylesheet" href="../css/template_languages.css" type="text/css" />
		<link rel="stylesheet" href="../css/mod_icemegamenu/css/default_icemegamenu.css" type="text/css" />
	<link rel="stylesheet" href="../css/uploadify.css" type="text/css" />
	<link rel="stylesheet" href="../css/tooltip.css" type="text/css" />
		<link rel="stylesheet" href="../css/expauto/assets/css/expautospro.css" type="text/css" />
		<link rel="stylesheet" href="../css/expauto/skins/explist/default/css/default.css" type="text/css" />
		<link rel="stylesheet" href="../css/expauto/mod_expautospro_mortgage.css" type="text/css" />
		<link rel="stylesheet" href="../css/expauto/mod_expautospro_stats.css" type="text/css" />
		<link rel="stylesheet" href="../css/expauto/mod_expautospro_categories.css" type="text/css" />
		<link rel="stylesheet" href="../css/expauto/mod_expautospro_listfilter.css" type="text/css" />
		<link rel="stylesheet" href="../css/expauto/mod_expautospro_keyword.css" type="text/css" />
	<link rel="stylesheet" href="../css/imageExpand.css" type="text/css" />

	<script src="../js/jquery-1.7.2.js" type="text/javascript"></script>
		<script src="../js/jquery.uploadify-3.1.min.js" type="text/javascript" ></script>
	<script src="../js/dproc.js" type="text/javascript"></script>
	<script type="text/javascript">
		var $j = jQuery.noConflict();
		function parseGetParams() { 
			var $_GET = {}; 
			var __GET = window.location.search.substring(1).split("&"); 
			for(var i=0; i<__GET.length; i++) { 
				var getVar = __GET[i].split("="); 
				$_GET[getVar[0]] = typeof(getVar[1])=="undefined" ? "" : getVar[1]; 
			} 
			return $_GET; 
		}
		$j.ajaxSetup({
			cache: false
		}); 
		/*	$j.post( "AuthFiles/authenticator.php", 
			function(data) {
				//alert(data);
			}
		).success(function(data) { 
			//alert(data);
			if(data == 'true')
				$j(document).ready(function() {
					$j(".login").hide();
					$j(".logout").show();
				});
		});
		 $j(document).ready(function() {
			 $j("button:submit").click( function(){
				$j.post( "AuthFiles/authenticator.php", { 
				login : $j(":text#username").attr("value"), 
				pass : $j(":password#password").attr("value") }, 
				function(data) {
					if(data == 'true')
						$j(".login").hide('slow');
						setTimeout("$j('.logout').show('slow');",400);
				}
			);
			 });
		 });*/
		</script>
	<script src="../js/mootools-core.js" type="text/javascript"></script>
		<script src="../js/core.js" type="text/javascript"></script>
		<script src="../js/caption.js" type="text/javascript"></script>
		<script src="../js/mootools-more.js" type="text/javascript"></script>
		<script src="../js/script_16.js" type="text/javascript"></script>
		<script src="../js/icemegamenu.js" type="text/javascript"></script>
		<script type="text/javascript">
			window.addEvent('load', function () {
				new JCaption('img.caption');
			});
			window.addEvent('domready', function () {
				$$('.hasTip').each(function (el) {
					var title = el.get('title');
					if (title) {
						var parts = title.split('::', 2);
						el.store('tip:title', parts[0]);
						el.store('tip:text', parts[1]);
					}
				});
				var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false });
			});
			window.addEvent("load", function () { if ($('item-127') != null) $('item-127').setStyle('display', 'none') });
			window.addEvent("load", function () { if ($('item-147') != null) $('item-147').setStyle('display', 'none') });
		</script>
	
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
			tr:hover 
			{
				background-color:#171917;
			}
			tr {
				cursor: pointer;
			}
			.add {
				padding-top: 10px;
				float: left;
				display: block;
			}
			input[type="text"] {
				width: 120px;
			}
			#insertTr {
				cursor:default;
			}
			button, input.button, a.button, input[type="submit"] {
			width: 100px;
			margin-top: 5px;
			margin-bottom: 5px;
		}       
		#password, #username {
			width: auto;
		}
		</style>

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

		<!-- Header -->	
		<div id="header">
			<div id="header_inside" class="clearfix">
				<div class="wrapper">
					<div id="logo">
						<p>
							<a href="/">
								<img src="../images/logo.png" alt="Корея Трейд - автомобили, спецтехника, автобусы из Кореи!!!" /></a></p>
					</div>
					<div id="mainmenu">
						<div class="icemegamenu">
							<ul id="icemegamenu">
								<li id="iceMenu_110" class="iceMenuLiLevel_1 parent"><a class=" iceMenuTitle" href="index.php?page=CarTable"><span
									class="icemega_title icemega_nosubtitle">Автомобили</span></a>
									<ul class="icesubMenu sub_level_1"
										style="width: 280px">
										<li>
											<div style="float: left; width: 280px" class="iceCols">
												<ul>
													<li id="iceMenu_117" class="iceMenuLiLevel_2">
														<a class=" iceMenuTitle" href="index.php?page=CarAdd">
															<span class="icemega_title icemega_nosubtitle">Добавить</span></a>
													</li>
													<li id="iceMenu_118" class="iceMenuLiLevel_2">
														<a class=" iceMenuTitle" href="index.php?page=CarTable">
															<span class="icemega_title icemega_nosubtitle">Редактировать</span></a>
													</li>
													<!--<li id="iceMenu_119" class="iceMenuLiLevel_2">
														<a class=" iceMenuTitle" href="index.php?page=CarTable">
															<span class="icemega_title icemega_nosubtitle">Удалить</span></a>
													</li>-->
												</ul>
											</div>
										</li>
									</ul>
								</li>
								<li id="iceMenu_120" class="iceMenuLiLevel_1 parent"><a class=" iceMenuTitle" href="index.php?page=MarkEdit"><span
									class="icemega_title icemega_nosubtitle">Марки</span></a>
									<!--<ul class="icesubMenu sub_level_1"
										style="width: 280px">
										<li>
											<div style="float: left; width: 280px" class="iceCols">
												<ul>
													<li id="iceMenu_127" class="iceMenuLiLevel_2">
														<a class=" iceMenuTitle" href="index.php?page=MarkEdit">
															<span class="icemega_title icemega_nosubtitle">Добавить</span></a>
													</li>
													<li id="iceMenu_128" class="iceMenuLiLevel_2">
														<a class=" iceMenuTitle" href="index.php?page=MarkEdit">
															<span class="icemega_title icemega_nosubtitle">Изменить</span></a>
													</li>
													<li id="iceMenu_129" class="iceMenuLiLevel_2">
														<a class=" iceMenuTitle" href="index.php?page=MarkEdit">
															<span class="icemega_title icemega_nosubtitle">Удалить</span></a>
													</li>
												</ul>
											</div>
										</li>
									</ul>-->
								</li>
								<li id="iceMenu_130" class="iceMenuLiLevel_1 parent"><a class=" iceMenuTitle" href="index.php?page=TypeEdit"><span
									class="icemega_title icemega_nosubtitle">Типы</span></a>
									<!--<ul class="icesubMenu sub_level_1"
										style="width: 280px">
										<li>
											<div style="float: left; width: 280px" class="iceCols">
												<ul>
													<li id="iceMenu_137" class="iceMenuLiLevel_2">
														<a class=" iceMenuTitle" href="index.php?page=TypeEdit">
															<span class="icemega_title icemega_nosubtitle">Добавить</span></a>
													</li>
													<li id="iceMenu_138" class="iceMenuLiLevel_2">
														<a class=" iceMenuTitle" href="index.php?page=TypeEdit">
															<span class="icemega_title icemega_nosubtitle">Изменить</span></a>
													</li>
													<li id="iceMenu_139" class="iceMenuLiLevel_2">
														<a class=" iceMenuTitle" href="index.php?page=TypeEdit">
															<span class="icemega_title icemega_nosubtitle">Удалить</span></a>
													</li>
												</ul>
											</div>
										</li>
									</ul>-->
								</li>
				<li id="iceMenu_11" class="iceMenuLiLevel_1"><a class=" iceMenuTitle" style="cursor: default"><span
									class="icemega_title icemega_nosubtitle"></span></a>
								</li>
								<li id="Li12" class="iceMenuLiLevel_1"><a class=" iceMenuTitle" style="cursor: default"><span
									class="icemega_title icemega_nosubtitle"></span></a>
								</li>
								<li id="Li13" class="iceMenuLiLevel_1"><a class=" iceMenuTitle" style="cursor: default"><span
									class="icemega_title icemega_nosubtitle"></span></a>
								</li>
								<li id="Li14" class="iceMenuLiLevel_1"><a class=" iceMenuTitle" style="cursor: default"><span
									class="icemega_title icemega_nosubtitle"></span></a>
								</li>
								<li id="Li15" class="iceMenuLiLevel_1"><a class=" iceMenuTitle" style="cursor: default"><span
									class="icemega_title icemega_nosubtitle"></span></a>
								</li>
								<li id="Li1" class="iceMenuLiLevel_1"><a class=" iceMenuTitle" style="cursor: default"><span
									class="icemega_title icemega_nosubtitle"></span></a>
								</li>
								<li id="Li2" class="iceMenuLiLevel_1"><a class=" iceMenuTitle" style="cursor: default"><span
									class="icemega_title icemega_nosubtitle"></span></a>
								</li>
								<li id="Li3" class="iceMenuLiLevel_1"><a class=" iceMenuTitle" style="cursor: default"><span
									class="icemega_title icemega_nosubtitle"></span></a>
								</li>
								<li id="Li4" class="iceMenuLiLevel_1"><a class=" iceMenuTitle" style="cursor: default"><span
									class="icemega_title icemega_nosubtitle"></span></a>
								</li>
								<li id="Li5" class="iceMenuLiLevel_1"><a class=" iceMenuTitle" style="cursor: default"><span
									class="icemega_title icemega_nosubtitle"></span></a>
								</li>
								<li id="Li6" class="iceMenuLiLevel_1"><a class=" iceMenuTitle" style="cursor: default"><span
									class="icemega_title icemega_nosubtitle"></span></a>
								</li>
								<li id="Li7" class="iceMenuLiLevel_1"><a class=" iceMenuTitle" style="cursor: default"><span
									class="icemega_title icemega_nosubtitle"></span></a>
								</li>
 
							</ul>
						</div>
						<script type="text/javascript">
							window.addEvent('domready', function () {
								if (document.getElementById('icemegamenu') != null)
									var myMenu = new MenuMatic({ id: 'icemegamenu',
										subMenusContainerId: 'subMenusContainer',
										effect: 'slide & fade',
										duration: 600,
										physics: Fx.Transitions.Pow.easeOut,
										hideDelay: 1000,
										opacity: 95
									});
							});
						</script>
					</div>
				</div>
			</div>
		</div>

	<!-- Content -->
		<?php
			if($logon){
				if(!isset($_GET["page"]) || $_GET["page"] == "login")
					$page = "welcome";
				else 
					$page = $_GET["page"];
			}
			else 
				$page = "login";
			if($page != "welcome" && $page != "login" && $page != "MarkEdit" && $page != "TypeEdit" && $page != "CarEdit" && $page != "CarAdd" && $page != "CarTable") { 
				die("wrong page parameter"); 
			}
			include $page.".html";
		?>
	<!-- Content Main -->
			
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