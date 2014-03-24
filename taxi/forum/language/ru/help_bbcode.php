<?php
/**
*
* help_bbcode [Russian]
*
* @package language
* @version $Id$
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*/
if (!defined('IN_PHPBB'))
{
exit;
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$help = array(
array(
			0 => '--',
			1 => 'Вступление'
	),
array(
			0 => 'Что такое BBCode?',
			1 => 'BBCode — это специальная реализация языка HTML, предоставляющая более удобные возможности по форматированию сообщений. Возможность использования BBCode в сообщениях определяется администратором форума. Кроме этого, BBCode может быть отключен вами в любое время в любом размещаемом сообщении прямо из формы его написания. Сам BBCode по стилю очень похож на HTML, но теги в нем заключаются в квадратные скобки [ … ], а не в &lt; … &gt;. При использовании некоторых шаблонов вы сможете добавлять BBCode в сообщения, пользуясь простым интерфейсом, расположенным над полем для ввода текста. Но даже в этом случае чтение данного руководства может оказаться вам полезным.'
	),
array(
			0 => '--',
			1 => 'Форматирование текста'
	),
array(
			0 => 'Как сделать текст жирным, курсивным или подчеркнутым?',
			1 => 'BBCode включает теги для быстрого изменения стиля основного текста. Сделать это можно следующими способами: <ul><li>Чтобы сделать текст жирным, заключите его в теги <strong>[b][/b]</strong>. Пример: <br /><br /><strong>[b]</strong>Привет<strong>[/b]</strong><br /><br />выдаст <strong>Привет</strong></li><li>Для подчеркивания используйте теги <strong>[u][/u]</strong>. Пример:<br /><br /><strong>[u]</strong>Доброе утро<strong>[/u]</strong><br /><br />выдаст <span style="text-decoration: underline">Доброе утро</span></li><li>Курсив делается тегами <strong>[i][/i]</strong>. Пример:<br /><br />Это <strong>[i]</strong>здорово!<strong>[/i]</strong><br /><br />выдаст Это <i>здорово!</i></li></ul>'
	),
array(
			0 => 'Как изменить цвет или размер текста?',
			1 => 'Для изменения цвета или размера шрифта могут быть использованы следующие теги (окончательный вид будет зависеть от системы и браузера пользователя): <ul><li>Цвет текста можно изменить, окружив его тегами <strong>[color=][/color]</strong>. Вы можете указать либо известное имя цвета (red, blue, yellow и т.п.), либо его шестнадцатеричное представление (#FFFFFF, #000000 и т.п.). Таким образом, для создания красного текста вы можете использовать:<br /><br /><strong>[color=red]</strong>Привет!<strong>[/color]</strong><br /><br />или<br /><br /><strong>[color=#FF0000]</strong>Привет!<strong>[/color]</strong><br /><br />Оба способа дадут в результате <span style="color:red">Привет!</span></li><li>Изменение размера достигается аналогичным образом при использовании тега <strong>[size=][/size]</strong>. Этот тег зависит от используемых шаблонов, но рекомендуемым форматом является числовое значение, показывающее размер текста в процентах, начиная от 20 (очень маленький) до 200 (очень большой) от размера по умолчанию. Пример:<br /><br /><strong>[size=30]</strong>МЕЛКИЙ<strong>[/size]</strong><br /><br />скорее всего выдаст <span style="font-size:30%;">МЕЛКИЙ</span><br /><br />в то время как:<br /><br /><strong>[size=200]</strong>ОЧЕНЬ БОЛЬШОЙ!<strong>[/size]</strong><br /><br />выдаст <span style="font-size:200%;">ОЧЕНЬ  БОЛЬШОЙ!</span></li></ul>'
	),
array(
			0 => 'Можно ли комбинировать теги форматирования?',
			1 => 'Да, конечно можно. Например, для привлечения внимания вы можете написать:<br /><br /><strong>[size=200][color=red][b]</strong>ПОСМОТРИТЕ НА МЕНЯ!<strong>[/b][/color][/size]</strong><br /><br />что выдаст <span style="color:red;font-size:200%;"><strong>ПОСМОТРИТЕ НА МЕНЯ!</strong></span><br /><br />Мы не рекомендуем писать таким вот образом длинные тексты! Помните, что вы — автор сообщения и должны сами позаботиться о корректном закрытии и вложенности тегов. Например, следующая запись некорректна:<br /><br /><strong>[b][u]</strong>Такая запись некорректна<strong>[/b][/u]</strong>'
	),
array(
			0 => '--',
			1 => 'Цитирование и вывод форматированных текстов'
	),
array(
			0 => 'Цитирование текста в ответах',
			1 => 'Имеется два способа цитирования текстов: с указанием автора и без указания.<ul><li>При использовании кнопки «Цитата» для ответа на сообщение, текст сообщения добавляется в поле для ввода текста, окруженный тегами <strong>[quote=&quot;&quot;][/quote]</strong>. Этот метод позволяет цитировать со ссылкой на автора, либо на что-то еще, что вы впишете в кавычках. Например, для цитирования фрагмента текста, написанного автором Mr. Blobby, введите:<br /><br /><strong>[quote=&quot;Mr. Blobby&quot;]</strong>Сюда введите текст от Mr. Blobby<strong>[/quote]</strong><br /><br />В результате перед вашим ответом на сообщение будет вставлен текст «Mr. Blobby писал(а):». Помните, <strong>необходимо</strong> заключить имя в кавычки (&quot;&quot;), они не могут быть опущены.</li><li>Второй метод просто позволяет вам процитировать что-либо. Для этого поместите текст между тегами <strong>[quote][/quote]</strong>. При просмотре сообщения этот текст будет находиться в блоке цитирования.</li></ul>'
	),
array(
			0 => 'Вывод кода или форматированного текста',
			1 => 'Если вам надо вывести часть кода программы или еще что-нибудь, что должно быть отображено шрифтом фиксированной ширины (Courier), то заключите текст в теги <strong>[code][/code]</strong>. Пример:<br /><br /><strong>[code]</strong>echo &quot;Это код программы&quot;;<strong>[/code]</strong><br /><br />Все форматирование, используемое внутри тегов <strong>[code][/code]</strong>, будет сохранено. Подсветку синтаксиса кода PHP можно произвести с помощью тега <strong>[code=php][/code]</strong> и рекомендуется при размещении сообщений, содержащих фрагменты кодов PHP.'
	),
array(
			0 => '--',
			1 => 'Создание списков'
	),
array(
			0 => 'Создание маркированного списка',
			1 => 'BBCode поддерживает два вида списков: маркированные и нумерованные. Они практически идентичны своим эквивалентам из HTML. В маркированном списке все элементы выводятся последовательно, каждый отмечается символом-маркером. Для создания маркированного списка используйте теги <strong>[list][/list]</strong> и определяйте каждый элемент списка при помощи <strong>[*]</strong>. Например, для вывода своих любимых цветов вы можете использовать:<br /><br /><strong>[list]</strong><br /><strong>[*]</strong>Красный<br /><strong>[*]</strong>Синий<br /><strong>[*]</strong>Желтый<br /><strong>[/list]</strong><br /><br />Это выдаст такой список:<ul><li>Красный</li><li>Синий</li><li>Желтый</li></ul>'
	),
array(
			0 => 'Создание нумерованного списка',
			1 => 'Второй тип списка — нумерованный, позволяет выбрать, что именно будет выводиться перед каждым элементом. Для создания нумерованного списка используйте теги <strong>[list=1][/list]</strong>, или <strong>[list=a][/list]</strong> для создания алфавитного списка. Как и в случае маркированного списка, элементы списка определяются с помощью <strong>[*]</strong>. Пример:<br /><br /><strong>[list=1]</strong><br /><strong>[*]</strong>Пойти в магазин<br /><strong>[*]</strong>Купить новый компьютер<br /><strong>[*]</strong>Обругать компьютер, когда случится ошибка<br /><strong>[/list]</strong><br /><br />выдаст следующее:<ol style="list-style-type: arabic-numbers"><li>Пойти в магазин</li><li>Купить новый компьютер</li><li>Обругать компьютер, когда случится ошибка</li></ol>Для алфавитного списка используйте следующее:<br /><br /><strong>[list=a]</strong><br /><strong>[*]</strong>Первый возможный ответ<br /><strong>[*]</strong>Второй возможный ответ<br /><strong>[*]</strong>Третий возможный ответ<br /><strong>[/list]</strong><br /><br />что выдаст<ol style="list-style-type: lower-alpha"><li>Первый возможный ответ</li><li>Второй возможный ответ</li><li>Третий возможный ответ</li></ol>'
	),
	// This block will switch the FAQ-Questions to the second template column
	array(
		0 => '--',
		1 => '--'
	),
array(
			0 => '--',
			1 => 'Создание ссылок'
	),
array(
			0 => 'Ссылки на другой сайт',
			1 => 'phpBB поддерживает несколько способов создания ссылок, также известных под названием URL.<ul><li>Первый из них использует тег <strong>[url=][/url]</strong>. После знака = должен вставляться требуемый адрес URL. Например, для создания ссылки на сайт phpBB.com вы могли бы использовать:<br /><br /><strong>[url=http://www.teosofia.ru/]</strong>Посетите сайт www.teosofia.ru!<strong>[/url]</strong><br /><br />В конечном виде такой код будет выглядеть так: <a href="http://www.teosofia.ru/">Посетите сайт www.teosofia.ru!</a> Ссылка будет открываться в том же или в новом окне, в зависимости от настроек браузера пользователя.</li><li>Если вы хотите, чтобы в качестве текста ссылки показывался URL, то вы можете просто сделать следующее:<br /><br /><strong>[url]</strong>http://www.teosofia.ru/<strong>[/url]</strong><br /><br />Такой код выдаст следующую ссылку: <a href="http://www.teosofia.ru/">http://www.teosofia.ru/</a></li><li>Кроме этого в phpBB внедрена функция, называемая <i>Автоматические ссылки</i>. Эта функция преобразовывает любой синтаксически правильный URL в ссылку без необходимости указания тегов и префикса http://. Например, ввод в сообщение фразы www.teosofia.ru приведет при просмотре этого сообщения к автоматической выдаче <a href="http://www.teosofia.ru/">www.teosofia.ru</a>.</li><li>То же самое относится и к адресам электронной почты: вы можете указать адрес в явном виде:<br /><br /><strong>[email]</strong>no.one@domain.adr<strong>[/email]</strong><br /><br />что выдаст <a href="mailto:no.one@domain.adr">no.one@domain.adr</a> или просто ввести адрес no.one@domain.adr в сообщение, и он будет автоматически преобразован при просмотре этого сообщения.</li></ul>Как и со всеми прочими тегами BBCode, вы можете заключать в ссылки любые другие теги. Например, <strong>[img][/img]</strong> (см. следующий пункт), <strong>[b][/b]</strong> и т.п. Как и с тегами форматирования, правильная вложенность тегов зависит от вас. Например следующая запись:<br /><br /><strong>[url=http://www.teosofia.ru/][img]</strong>http://www.teosofia.ru/my-picture.gif<strong>[/url][/img]</strong><br /><br /><span style="text-decoration: underline">не</span> является корректной, что может привести к последующему удалению вашего сообщения. Будьте внимательнее.'
	),
array(
			0 => '--',
			1 => 'Отображение рисунков в сообщениях'
	),
array(
			0 => 'Добавление рисунка в сообщение',
			1 => 'BBCode содержит тег для добавления рисунков в размещаемых сообщениях. При этом следует помнить две важные вещи: во-первых, многих пользователей раздражает большое количество рисунков в сообщениях, во-вторых, рисунки должны быть размещены где-то в интернете (т.е. они не могут располагаться только на вашем компьютере, если, конечно, вы не запустили на нем веб-сервер). Для отображения рисунка необходимо окружить его URL-адрес тегами <strong>[img][/img]</strong>. Пример:<br /><br /><strong>[img]</strong>http://www.teosofia.ru/my-picture.gif<strong>[/img]</strong><br /><br />Вы можете заключить рисунок в теги <strong>[url][/url]</strong>, как это указано в разделе «Создание ссылок». То есть<br /><br /><strong>[url=http://www.teosofia.ru/][img]</strong>http://www.teosofia.ru/my-picture.gif<strong>[/img][/url]</strong><br /><br />выдаст:<br /><br /><a href="http://www.teosofia.ru/"><img src="http://www.teosofia.ru/my-picture.gif" alt="" /></a>'
	),
array(
			0 => 'Добавление вложений в сообщение',
			1 => 'Теперь вложения могут быть отображены в любой части сообщения при помощи нового тега <strong>[attachment=][/attachment]</strong>, если вложения разрешены администратором, и если вы имеете необходимые права доступа. На странице размещения сообщения находится выпадающий список для размещения вложений в сообщении.'
	),
array(
			0 => '--',
			1 => 'Прочее'
	),
array(
			0 => 'Могу ли я добавить собственные теги?',
			1 => 'Если вы являетесь администратором этого форума имеете соответствующие права, то вы можете добавлять новые теги BBCode в администраторском разделе.'
	),
array(
			0 => 'Перевод руководства',
			1 => 'Адаптирован перевод руководства, выполненный <a href="http://www.teosofia.ru/phpbb3-documentation/" target="_blank">Kastaneda</a>'
)
);

?>