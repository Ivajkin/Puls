<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: russian.php 1071 2008-02-03 08:42:28Z alex_rus $
* @package VirtueMart
* @subpackage languages
* @copyright Copyright (C) 2004-2007 soeren - All rights reserved.
* @translator soeren
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
* http://www.alex-rus.com
* http://www.virtuemart.ru
* http://www.joomlaforum.ru
*/

global $VM_LANG;
$langvars = array (
	'CHARSET' => 'utf-8',
	'PHPSHOP_USER_FORM_FIRST_NAME' => 'Имя',
	'PHPSHOP_USER_FORM_LAST_NAME' => 'Фамилия',
	'PHPSHOP_USER_FORM_MIDDLE_NAME' => 'Отчество',
	'PHPSHOP_USER_FORM_COMPANY_NAME' => 'Название компании',
	'PHPSHOP_USER_FORM_ADDRESS_1' => 'Адрес 1',
	'PHPSHOP_USER_FORM_ADDRESS_2' => 'Адрес 2',
	'PHPSHOP_USER_FORM_CITY' => 'Город',
	'PHPSHOP_USER_FORM_STATE' => 'Регион',
	'PHPSHOP_USER_FORM_ZIP' => 'Индекс',
	'PHPSHOP_USER_FORM_COUNTRY' => 'Страна',
	'PHPSHOP_USER_FORM_PHONE' => 'Телефон',
	'PHPSHOP_USER_FORM_PHONE2' => 'Мобильный телефон',
	'PHPSHOP_USER_FORM_FAX' => 'Факс',
	'PHPSHOP_ISSHIP_LIST_PUBLISH_LBL' => 'Активные',
	'PHPSHOP_ISSHIP_FORM_UPDATE_LBL' => 'Настроить вариант доставки',
	'PHPSHOP_STORE_FORM_FULL_IMAGE' => 'Логотип',
	'PHPSHOP_STORE_FORM_UPLOAD' => 'Загрузить изображение',
	'PHPSHOP_STORE_FORM_STORE_NAME' => 'Название магазина',
	'PHPSHOP_STORE_FORM_COMPANY_NAME' => 'Название компании владельца магазина',
	'PHPSHOP_STORE_FORM_ADDRESS_1' => 'Адрес 1',
	'PHPSHOP_STORE_FORM_ADDRESS_2' => 'Адрес 2',
	'PHPSHOP_STORE_FORM_CITY' => 'Город',
	'PHPSHOP_STORE_FORM_STATE' => 'Регион',
	'PHPSHOP_STORE_FORM_COUNTRY' => 'Страна',
	'PHPSHOP_STORE_FORM_ZIP' => 'Индекс',
	'PHPSHOP_STORE_FORM_CURRENCY' => 'Валюта',
	'PHPSHOP_STORE_FORM_LAST_NAME' => 'Фамилия',
	'PHPSHOP_STORE_FORM_FIRST_NAME' => 'Имя',
	'PHPSHOP_STORE_FORM_MIDDLE_NAME' => 'Отчество',
	'PHPSHOP_STORE_FORM_TITLE' => 'Обращение',
	'PHPSHOP_STORE_FORM_PHONE_1' => 'Телефон 1',
	'PHPSHOP_STORE_FORM_PHONE_2' => 'Телефон 2',
	'PHPSHOP_STORE_FORM_DESCRIPTION' => 'Описание',
	'PHPSHOP_PAYMENT_METHOD_LIST_LBL' => 'Список способов оплаты',
	'PHPSHOP_PAYMENT_METHOD_LIST_NAME' => 'Название',
	'PHPSHOP_PAYMENT_METHOD_LIST_CODE' => 'Код',
	'PHPSHOP_PAYMENT_METHOD_LIST_SHOPPER_GROUP' => 'Группа покупателей',
	'PHPSHOP_PAYMENT_METHOD_LIST_ENABLE_PROCESSOR' => 'Способ оплаты',
	'PHPSHOP_PAYMENT_METHOD_FORM_LBL' => 'Добавить способ оплаты',
	'PHPSHOP_PAYMENT_METHOD_FORM_NAME' => 'Название способа оплаты',
	'PHPSHOP_PAYMENT_METHOD_FORM_SHOPPER_GROUP' => 'Группа покупателей',
	'PHPSHOP_PAYMENT_METHOD_FORM_DISCOUNT' => 'Скидка',
	'PHPSHOP_PAYMENT_METHOD_FORM_CODE' => 'Код',
	'PHPSHOP_PAYMENT_METHOD_FORM_LIST_ORDER' => 'Порядок отображения',
	'PHPSHOP_PAYMENT_METHOD_FORM_ENABLE_PROCESSOR' => 'Способ оплаты',
	'PHPSHOP_PAYMENT_FORM_CC' => 'Кредитная карта',
	'PHPSHOP_PAYMENT_FORM_USE_PP' => 'Использовать платёжный процессор',
	'PHPSHOP_PAYMENT_FORM_BANK_DEBIT' => 'Банковский дебет',
	'PHPSHOP_PAYMENT_FORM_AO' => 'Доставка по адресу (оплата наличными при получении)',
	'PHPSHOP_STATISTIC_STATISTICS' => 'Статистика',
	'PHPSHOP_STATISTIC_CUSTOMERS' => 'Покупатели',
	'PHPSHOP_STATISTIC_ACTIVE_PRODUCTS' => 'Активные товары',
	'PHPSHOP_STATISTIC_INACTIVE_PRODUCTS' => 'Неактивные товары',
	'PHPSHOP_STATISTIC_NEW_ORDERS' => 'Новые заказы',
	'PHPSHOP_STATISTIC_NEW_CUSTOMERS' => 'Новые покупатели',
	'PHPSHOP_CREDITCARD_NAME' => 'Название кредитной карты',
	'PHPSHOP_CREDITCARD_CODE' => 'Кредитная карта - короткий код',
	'PHPSHOP_YOUR_STORE' => 'Электронный магазин',
	'PHPSHOP_CONTROL_PANEL' => 'Панель управления',
	'PHPSHOP_CHANGE_PASSKEY_FORM' => 'Показать/Изменить Пароль/Ключ транзакции',
	'PHPSHOP_TYPE_PASSWORD' => 'Пожалуйста, введите пароль пользователя',
	'PHPSHOP_CURRENT_TRANSACTION_KEY' => 'Текущий ключ транзакции',
	'PHPSHOP_CHANGE_PASSKEY_SUCCESS' => 'Ключ транзакций успешно изменен.',
	'VM_PAYMENT_CLASS_NAME' => 'Класс имени платежа',
	'VM_PAYMENT_CLASS_NAME_TIP' => 'например,  <strong>ps_netbanx</strong>):<br />
по умолчанию: ps_payment<br />
<em>Выберите ps_payment, если не уверены что точно требуется!</em>',
	'VM_PAYMENT_EXTRAINFO' => 'Дополнительная информация по платежу',
	'VM_PAYMENT_EXTRAINFO_TIP' => 'Показывается на странице подтверждения товара. Может быть: HTML код от Вашей платежной системы, подсказка клиенту и т.д.',
	'VM_PAYMENT_ACCEPTED_CREDITCARDS' => 'Кредитные карточки, принимаемые в нашем магазине',
	'VM_PAYMENT_METHOD_DISCOUNT_TIP' => 'Чтобы преобразовать скидку в доплату, используйте отрицательное число(Например: <strong>-2.00</strong>).',
	'VM_PAYMENT_METHOD_DISCOUNT_MAX_AMOUNT' => 'Максимальный размер скидки',
	'VM_PAYMENT_METHOD_DISCOUNT_MIN_AMOUNT' => 'Минимальный размер скидки',
	'VM_PAYMENT_FORM_FORMBASED' => 'HTML-форма (например, для PayPal)',
	'VM_ORDER_EXPORT_MODULE_LIST_LBL' => 'Список модулей экспорта',
	'VM_ORDER_EXPORT_MODULE_LIST_NAME' => 'Название',
	'VM_ORDER_EXPORT_MODULE_LIST_DESC' => 'Описание',
	'VM_STORE_FORM_ACCEPTED_CURRENCIES' => 'Список допустимых валют',
	'VM_STORE_FORM_ACCEPTED_CURRENCIES_TIP' => 'Этот список определяет список валют, которые Вы хотите использовать в магазине. <strong>Примечание:</strong> Все валюты, указанные здесь, могут использоваться при оформлении заказа! Если Вы этого не хотите, то укажите - использовать валюты Ваше страны (=по умолчанию).',
	'VM_EXPORT_MODULE_FORM_LBL' => 'Форма модуля экспорта',
	'VM_EXPORT_MODULE_FORM_NAME' => 'Название модуля экспорта',
	'VM_EXPORT_MODULE_FORM_DESC' => 'Описание',
	'VM_EXPORT_CLASS_NAME' => 'Имя класса экспорта',
	'VM_EXPORT_CLASS_NAME_TIP' => '(например:  <strong>ps_orders_csv</strong>):<br /> по умолчанию: ps_xmlexport<br /> <i>Оставьте поле пустым, если не уверены что надо указать!</i>',
	'VM_EXPORT_CONFIG' => 'Дополнительные настройки для экспорта',
	'VM_EXPORT_CONFIG_TIP' => 'Укажите настройки для пользовательских модулей экспорта или укажите дополнительные настройки. Необходимо использовать PHP.',
	'VM_SHIPPING_MODULE_LIST_NAME' => 'Название',
	'VM_SHIPPING_MODULE_LIST_E_VERSION' => 'Версия',
	'VM_SHIPPING_MODULE_LIST_HEADER_AUTHOR' => 'Автор',
	'PHPSHOP_STORE_ADDRESS_FORMAT' => 'Формат адреса магазина',
	'PHPSHOP_STORE_ADDRESS_FORMAT_TIP' => 'Вы можете использовать здесь следующие значения',
	'PHPSHOP_STORE_DATE_FORMAT' => 'Формат даты магазина',
	'VM_PAYMENT_METHOD_ID_NOT_PROVIDED' => 'Ошибка: Не указан ID метода оплаты.',
	'VM_SHIPPING_MODULE_CONFIG_LBL' => 'Настройки модуля доставки',
	'VM_SHIPPING_MODULE_CLASSERROR' => 'Не могу инициировать класс {shipping_module}'
); $VM_LANG->initModule( 'store', $langvars );
?>