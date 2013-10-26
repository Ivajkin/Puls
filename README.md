<h1><span style="color: coral">T</span><span style="color: magenta">M</span>edia.true</h1>
====

<h2>Переключение на JS-версию сайта</h2>
<p>index=.html <span style="color: red">--></span> index.html</p>
<h3>Изменение эффекта перехода страниц</h3>
<p>js <span style="color: red">--></span> main.js <span style="color: red">--></span> showpage()</p>

<h2>Архитектура</h2>
<ul>
<li>.idea -- проект для WebStorm/PhPStorm</li>
<li>add -- модули/библиотеки в несколько файлов/с темами оформления</li>
<li>content -- js+html+img+... для каждой страницы</li>
<li>css -- общий css</li>
<li>img -- общие img</li>
<li>js -- общий js + однофайловые библиотеки</li>
<li>tpl -- шаблоны страниц/контента</li>
<li>namespace.php + *.php в корне -- определения структуры контента/изменяемых частей контента + основные страницы на основе шаблонов</li>
<li>404.html/.htaccess/другое в корне -- дополнительный необходимый код</li>
</ul>

<h2>Структура страницы для PHP</h2>
<ul>
<li>require_once 'namespace.php';</li>
<li>$header_h1 -- заголовок страницы</li>
<li>$main_block -- главный блок</li>
<li>$more_block -- нижний блок (футер)</li>
<li>$script_block -- js страеицы</li>
<li>$csslocal -- для js/css для определения локальной области видимости в DOM</li>
<li>$css_lib -- css библиотеки страницы</li>
<li>$script_lib -- js библиотеки страницы</li>
<li>include $sitedir.'tpl/tpl.php';</li>
</ul>


