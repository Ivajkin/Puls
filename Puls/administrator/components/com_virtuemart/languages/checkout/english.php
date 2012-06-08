<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @package VirtueMart
* @subpackage languages
* @copyright Copyright (C) 2004-2008 soeren - All rights reserved.
* @translator soeren
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
*
* http://virtuemart.net
* http://www.alex-rus.com
* http://www.virtuemart.ru
* http://www.joomlaforum.ru
*/
global $VM_LANG;
$langvars = array (
	'CHARSET' => 'utf-8',
	'PHPSHOP_NO_CUSTOMER' => 'Вы не являетесь зарегистрированным клиентом. Пожалуйста, введите информацию для оформления заказа.',
	'PHPSHOP_THANKYOU' => 'Спасибо за Ваш заказ.',
	'PHPSHOP_EMAIL_SENDTO' => 'Подтверждающее письмо было выслано по адресу',
	'PHPSHOP_CHECKOUT_NEXT' => 'Следующий',
	'PHPSHOP_CHECKOUT_CONF_BILLINFO' => 'Контактная информация плательщика',
	'PHPSHOP_CHECKOUT_CONF_COMPANY' => 'Компания',
	'PHPSHOP_CHECKOUT_CONF_NAME' => 'Имя',
	'PHPSHOP_CHECKOUT_CONF_ADDRESS' => 'Адрес',
	'PHPSHOP_CHECKOUT_CONF_EMAIL' => 'E-mail',
	'PHPSHOP_CHECKOUT_CONF_SHIPINFO' => 'Информация о доставке',
	'PHPSHOP_CHECKOUT_CONF_SHIPINFO_COMPANY' => 'Компания',
	'PHPSHOP_CHECKOUT_CONF_SHIPINFO_NAME' => 'Имя',
	'PHPSHOP_CHECKOUT_CONF_SHIPINFO_ADDRESS' => 'Адрес',
	'PHPSHOP_CHECKOUT_CONF_SHIPINFO_PHONE' => 'Телефон',
	'PHPSHOP_CHECKOUT_CONF_SHIPINFO_FAX' => 'Факс',
	'PHPSHOP_CHECKOUT_CONF_PAYINFO_METHOD' => 'Способ оплаты',
	'PHPSHOP_CHECKOUT_CONF_PAYINFO_REQINFO' => 'необходимая информация для оплаты по кредитной карте',
	'PHPSHOP_PAYPAL_THANKYOU' => 'Благодарим за оплату. 
         Операция прошла успешно. Вы получите подтверждение по e-mail об оплате через PayPal. 
         Вы можете продолжить или пройти на <a href=http://www.paypal.com>www.paypal.com</a>, чтобы увидеть подробности операции.',
	'PHPSHOP_PAYPAL_ERROR' => 'При обработке операции произошла ошибка. Статус Вашего заказа не изменился.',
	'PHPSHOP_THANKYOU_SUCCESS' => 'Ваш заказ принят!',
	'VM_CHECKOUT_TITLE_TAG' => 'Оформление: Шаг %s из %s',
	'VM_CHECKOUT_ORDERIDNOTSET' => 'Номер заказа ну указан или пуст!',
	'VM_CHECKOUT_FAILURE' => 'Ошибка',
	'VM_CHECKOUT_SUCCESS' => 'Успешно',
	'VM_CHECKOUT_PAGE_GATEWAY_EXPLAIN_1' => 'Эта страница расположена на сайте продавца.',
	'VM_CHECKOUT_PAGE_GATEWAY_EXPLAIN_2' => 'Результаты выполнения запроса будут отображены на зашифрованной странице.',
	'VM_CHECKOUT_CCV_CODE' => 'Проверочный (CCV) код кредитной карты',
	'VM_CHECKOUT_CCV_CODE_TIPTITLE' => 'Что такое проверочный (CCV) код кредитной карты?',
	'VM_CHECKOUT_MD5_FAILED' => 'Контрольная сумма MD5 не совпадает',
	'VM_CHECKOUT_ORDERNOTFOUND' => 'Заказ не найдет',
	'PHPSHOP_EPAY_PAYMENT_CARDTYPE' => 'Платеж сделан
 %s <img
src="/components/com_virtuemart/shop_image/ps_image/epay_images/%s"
border="0">'
); $VM_LANG->initModule( 'checkout', $langvars );
?>