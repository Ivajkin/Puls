// Ещё какой-то левый код загрузки
function onLoad() {
	/*new JCaption('img.caption');

	$$('.hasTip').each(function(el) {
		var title = el.get('title');
		if (title) {
			var parts = title.split('::', 2);
			el.store('tip:title', parts[0]);
			el.store('tip:text', parts[1]);
		}
	});
	var JTooltips = new Tips($$('.hasTip'), {
		maxTitleChars : 50,
		fixed : false
	});

	if ($('item-127') != null) {
		$('item-127').setStyle('display', 'none');
	}
	if ($('item-147') != null) {
		$('item-147').setStyle('display', 'none');
	}
*/
}

// Слайдшоу
function slidestart() {
	/*$$('.hasTip').each(function(el) {
		var title = el.get('title');
		if (title) {
			var parts = title.split('::', 2);
			el.store('tip:title', parts[0]);
			el.store('tip:text', parts[1]);
		}
	});
	var JTooltips = new Tips($$('.hasTip'), {
		fixed : false
	});*/
}

// Загрузка новостей на домашней странице.
function loadNews() {
	//Обзор размером 2 абзаца для статьи. Формирование позиции
	function readlitle(str) {
		word = 700;
		//количество букв в обзоре, включая html-теги

		tmp = str.substr(0, word);
		end = tmp.lastIndexOf('>');
		start = tmp.lastIndexOf('<');

		if (end == word)
			return word + 1;
		if (start == word)
			return word;
		if (end > start)
			return word + 1;
		return start;
	}

	function news_gen(feeds) {
		for (var i = 0; i < feeds.entries.length; i++) {
			var entry = feeds.entries[i];
			// Данные статьи (фида)
			pos = readlitle(entry.content);
			//alert(pos + " " + entry.content.substring(0, pos)); // полуотрезок типа [+ )-
			$j('#news').append('<a name="new_' + i + '"></a>' + '<div id="news_id_' + i + '" class="items-row cols-1 row-0">' + '<div class="item column-1">' + '<h2 style="cursor: pointer">' + entry.title + '</h2>' +
			/*'<p>' +
			 '<img src="" border="0" alt="Image" width="160" height="105" style="border: 0;" />' +
			 '</p>' +*/
			entry.content.substring(0, pos) + '<p id="news_id_' + i + '" class="readmore news_class_' + i + '">' + '<a href="javascript:void(0);">Подробнее...</a>' + '</p>' + '<div>' + entry.content.substring(pos) + '<footer><author>Автор: ' + entry.author + '</author> <date>' + entry.publishedDate + '</date> <a href="' + entry.link + '">Источник</a></footer>' + '<div class="item-separator"></div>' + '<p id="news_id_' + i + '" class="readmore ">' + '<a href="javascript:void(0);">Свернуть</a>' + '</p>' + '</div>' + '</div>' + '<span class="row-separator"></span>' + '</div>');
			//Fill front page
			if (i == 3)
				$j('#news').append('<div class="items-more">' + '<div id="more_news" >' + '<h3 style="cursor: pointer; width: 30%; float: left; margin-bottom: 30px"><i>Больше новостей</i></h3>' + '<p class="readmore" id="more_news" style="width: 8%; float: left;">' + '<a name="news_expand" href="javascript:void(0);">Развернуть</a>' + '</p>' + '</div>' + '<div style="clear: both"></div>' + '</div>');
			// Fill SlideShow
			if (i < 5) {
				$j('li#slide_' + i).attr('title', entry.title);
				$j('li#slide_' + i + ' span').html(entry.title);

				$j('div#slide_' + i + ' img').attr('title', entry.title).attr('alt', entry.title).attr('src', $j('div#news_id_' + i + ' img').attr('src'));
				$j('div#slide_' + i + ' h3').html(entry.title);
				$j('div#slide_' + i + ' p').html(entry.contentSnippet);

				(i != 4) ? $j('#slide_a_' + i).attr('href', '#new_' + i) : $j('#slide_a_' + i).attr('href', '#news_expand');

				$j('#slide_a_' + i).click( function(index) {
					if (index != 4)
						$j('.news_class_' + index).click();
					else
						$j('div#more_news').click();
					$j('.news_class_' + index).click();
					window.location = '#new_4';
				}.bind(this, i));
			}
			// entry.title, entry.link, entry.author,
			// entry.publishedDate, entry.contentSnippet, entry.content
		}
		slidestart();
	}

	function news_back(feeds) {
		for (var i = feeds.entries.length - 1; i >= 0; i--) {
			var entry = feeds.entries[i];
			// Данные статьи (фида)
			pos = readlitle(entry.content);
			//alert(pos + " " + entry.content.substring(0, pos)); // полуотрезок типа [+ )-
			$j('#news').append('<a name="new_' + i + '"></a>' + '<div id="news_id_' + i + '" class="items-row cols-1 row-0">' + '<div class="item column-1">' + '<h2 style="cursor: pointer">' + entry.title + '</h2>' +
			/*'<p>' +
			 '<img src="" border="0" alt="Image" width="160" height="105" style="border: 0;" />' +
			 '</p>' +*/
			entry.content.substring(0, pos) + '<p id="news_id_' + i + '" class="readmore">' + '<a href="javascript:void(0);">Подробнее...</a>' + '</p>' + '<div>' + entry.content.substring(pos) + '<footer><author>Автор: ' + entry.author + '</author> <date>' + entry.publishedDate + '</date> <a href="' + entry.link + '">Источник</a></footer>' + '<div class="item-separator"></div>' + '<p id="news_id_' + i + '" class="readmore">' + '<a href="javascript:void(0);">Свернуть</a>' + '</p>' + '</div>' + '</div>' + '<span class="row-separator"></span>' + '</div>');
			if (i == 3)
				$j('#news').append('<div class="items-more">' + '<div id="more_news" >' + '<h3 style="cursor: pointer; width: 30%; float: left; margin-bottom: 30px"><i>Больше новостей</i></h3>' + '<p class="readmore" id="more_news" style="width: 8%; float: left;">' + '<a href="javascript:void(0);">Развернуть</a>' + '</p>' + '</div>' + '<div style="clear: both"></div>' + '</div>');
			// Fill SlideShow
			if (i > 5) {
				$j('li#slide_' + i).attr('title', entry.title);
				$j('li#slide_' + i + ' span').html(entry.title);

				$j('div#slide_' + i + ' img').attr('title', entry.title).attr('alt', entry.title).attr('src', $j('div#news_id_' + i + ' img').attr('src'));
				$j('div#slide_' + i + ' h3').html(entry.title);
				$j('div#slide_' + i + ' p').html(entry.contentSnippet);

				$j('#slide_a_' + i).attr('href', '#new_' + i);
				$j('#slide_a_' + i).click( function(index) {
					$j('.news_class_' + index).click();
				}.bind(this, i));
			}
			// entry.title, entry.link, entry.author,
			// entry.publishedDate, entry.contentSnippet, entry.content
		}
		slidestart();
	}


	$j.jGFeed('http://www.k-window.com/category/economics/auto/feed/', function(feeds) {
		// Проверяем ошибки
		if (!feeds) {
			// была ошибка
			return false;
		}

		//Math.round(Math.random()) ? news_gen(feeds) : news_back(feeds);
		news_gen(feeds);

		$j('div.items-more').append($j('div.items-row.cols-1.row-0:gt(3)'));
		$j('div.items-more').append('   <div id="less_news">' + '<h3 style="cursor: pointer; width: 30%; float: left;"><i>Меньше новостей</i></h3>' + '<p class="readmore" id="more_news" style="width: 8%; float: left;">' + '<a href="javascript:void(0);">Cвернуть</a>' + '</p>' + '</div>' + '<div style="clear: both"></div>');
		$j('#news').find('img').attr('width', '160').attr('height', '100').css('border', '0px');
		$j('p.readmore + div').slideToggle();
		$j('div.items-more > div.items-row.cols-1.row-0').slideUp();
		$j('div.items-more > div:contains("Меньше новостей")').slideUp();

		//Обработка полной инфы новостей
		$j('p:contains("Подробнее...")').click(function() {
			$j(this).next().slideDown();
			$j(this).css('display', 'none');

			tmp = $j(this).attr('id');
			$j('div#' + tmp).find('img').attr('width', '300').attr('height', '100%');
		});
		$j('p:contains("Свернуть")').click(function() {
			$j('p.readmore + div').slideUp();
			$j('p:contains("Подробнее...")').css('display', 'inline-block');

			tmp = $j(this).attr('id');
			$j('div#' + tmp).find('img').attr('width', '160').attr('height', '100%');
		});

		//Обработка дополнительных новостей
		ok = true;
		$j('div#more_news').click(function() {
			$j('div.items-more > div.items-row.cols-1.row-0').slideToggle();
			$j('div#less_news').slideToggle();
			if (ok) {
				$j(this).find('i').html('Меньше новостей');
				$j(this).find('a').html('Свернуть');
				ok = !ok;
			} else {
				$j(this).find('i').html('Больше новостей');
				$j(this).find('a').html('Развернуть');
				ok = !ok;
			}
		});
		$j('div#less_news').click(function() {
			$j('div.items-more > div.items-row.cols-1.row-0').slideUp();
			$j(this).slideUp();
			$j('div#more_news').find('i').html('Больше новостей');
			$j('div#more_news').find('a').html('Развернуть');
			ok = !ok;
		});
	}, 10);
}

// Twitter panel
function makeTwitterWidget() {

	new TWTR.Widget({
		version : 2,
		type : 'profile',
		rpp : 3,
		interval : 6000,
		width : 180,
		height : 300,
		theme : {
			shell : {
				background : '#353535',
				color : '#ffffff'
			},
			tweets : {
				background : '#353535',
				color : '#ffffff',
				links : '#408EDA'
			}
		},
		features : {
			scrollbar : false,
			loop : true,
			live : true,
			hashtags : true,
			timestamp : true,
			avatars : true,
			behavior : 'default'
		},
		id : "twitter-widget-placeholder"
	}).render().setUser('cometokorea').start();
}

// Главная функция загрузки всех внутренних элементов
function loadBodyPage(pageName, callback) {
	makeRequest(pageName + '.body.html', function(body_html) {
		$j('#main-window').empty();
		$j('#main-window').append(body_html);
		callback();
	});
}

// Загружаем тело страницы index
function loadHome() {
	loadBodyPage('home', function() {
		
		onLoad();

		loadNews();

		makeTwitterWidget();
		
		// Слайдшоу
		new SmoothScroll({
			duration : 800
		});

		/*// Какой-то левый код
		window.addEvent('load', function() {
			new JCaption('img.caption');
		});
		window.addEvent('domready', function() {
			$$('.hasTip').each(function(el) {
				var title = el.get('title');
				if (title) {
					var parts = title.split('::', 2);
					el.store('tip:title', parts[0]);
					el.store('tip:text', parts[1]);
				}
			});
			var JTooltips = new Tips($$('.hasTip'), {
				maxTitleChars : 50,
				fixed : false
			});
		});
		window.addEvent("load", function() {
			if ($('item-127') != null)
				$('item-127').setStyle('display', 'none')
		});
		window.addEvent("load", function() {
			if ($('item-147') != null)
				$('item-147').setStyle('display', 'none')
		});
		// Конец левого кода*/

		var _lofmain = $('iceslideshow157');
		var object = new IceSlideShow(_lofmain.getElement('.ice-main-wapper'), _lofmain.getElement('.ice-navigator-outer .ice-navigator'), _lofmain.getElement('.ice-navigator-outer'), {
			fxObject : {
				transition : Fx.Transitions.Sine.easeInOut,
				duration : 2200
			},
			fxCaptionObject : {
				transition : Fx.Transitions.Sine.easeInOut,
				duration : 1200
			},
			transition : Fx.Transitions.Sine.easeInOut,
			slideDuration : 1800,
			slideCaption : false,
			captionHeight : 80,
			captionOpacity : 1,
			mainItemSelector : 'div.ice-main-item',
			interval : 5500,
			direction : 'opacity',
			navItemHeight : 12,
			navItemWidth : 12,
			navItemsDisplay : 3,
			navPos : 'top',
			zoom : 50,
			pan : 50,
			pansize : 100,
			wdirection : "left"
		});
		object.registerButtonsControl('click', {
			next : _lofmain.getElement('.ice-next'),
			previous : _lofmain.getElement('.ice-previous')
		});
		object.start(1, _lofmain.getElement('.preload'));

		// promo

		//Отрисовка первой строки
		function plot(int, dbrand) {
			for ( td = 0; td < int; td++) {
				$j("div#mark_table").append('<div class="mark_show separator floatleft" id="tr_mark_' + td + '">' + '<div class="moduletable has-subtitle">' +
				/*'<div class="moduletable-header">' +
				 '<div class="moduletable-header-inside">' +
				 '<h5 class="mod-title" align="center">'+ dbrand[td].mark +'</h5>' +
				 '</div>' +
				 '</div>' +*/
				'<div class="moduletable_content clearfix">' + '<a title="' + dbrand[td].mark + ' (' + dbrand[td].model.length + ' авто)" href="javascript:getByMark(' + td + ')">' + '<img src="' + dbrand[td].img + '" alt="" />' + '</a>' + '</div>' + '</div>' + '</div>');
			}
		}

		jgetBrand(function(dbrand) {
			//Отрисовка первой строки. Проверка на многострочность
			if (dbrand.length < 13)
				plot(dbrand.length, dbrand);
			//Если 2 строки, то проверка на чётность. отрисовка
			else {
				int = parseInt(dbrand.length / 2);
				if (dbrand.length - int - int)
					plot(++int, dbrand);
				else
					plot(int, dbrand);
				for ( td = int; td < dbrand.length; td++) {

					name = dbrand[td].mark.toLowerCase();
					$j('div#tr_mark_' + (td - int)).append('<div class="mark_show separator floatleft" id="tr_mark_' + td + '">' +
					/*'<div class="moduletable-header">' +
					 '<div class="moduletable-header-inside">' +
					 '<h5 class="mod-title" align="center">'+ dbrand[td].mark +'</h5>' +
					 '</div>' +
					 '</div>' +*/
					'<div class="moduletable_content clearfix">' + '<a title="' + dbrand[td].mark + ' (' + dbrand[td].model.length + ' авто)" href="javascript:getByMark(' + td + ')">' + '<img src="' + dbrand[td].img + '" alt="" />' + '</a>' + '</div>' + '</div>');
				}
			}
		});

	});
};

// Загружаем тело страницы about, "О компании"
function loadAbout() {
	loadBodyPage('about', function() {
		makeTwitterWidget();
	});
}

// Загружаем тело страницы по нажатию
$j("button").click(loadHome);
$j("#home-button").click(loadHome);
$j("#about-button").click(loadAbout);
