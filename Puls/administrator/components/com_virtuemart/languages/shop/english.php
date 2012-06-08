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
* http://virtuemart.net
*/
global $VM_LANG;
$langvars = array (
	'CHARSET' => 'windows-1251',
	'PHPSHOP_BROWSE_LBL' => 'Просмотр',
	'PHPSHOP_FLYPAGE_LBL' => 'Подробнее',
	'PHPSHOP_PRODUCT_FORM_EDIT_PRODUCT' => 'Редактировать товар',
	'PHPSHOP_DOWNLOADS_START' => 'Скачать',
	'PHPSHOP_DOWNLOADS_INFO' => 'Пожалуйста, введите идентификатор для скачивания, который Вы получили по e-mail и нажмите \'Скачать\'.',
	'PHPSHOP_WAITING_LIST_MESSAGE' => 'Пожалуйста, оставьте свой e-mail, чтобы мы известили Вас, когда этот товар снова появится на складе. 
                                         Мы не будем продавать, передавать третьим лицам или другим способом использовать Ваш e-mail, кроме как для 
                                         уведомления о том, что товар снова поступил на склад.<br /><br />Спасибо!',
	'PHPSHOP_WAITING_LIST_THANKS' => 'Спасибо за ожидание! <br />Мы сообщим Вам о появлении товара, как только он поступит на склад.',
	'PHPSHOP_WAITING_LIST_NOTIFY_ME' => 'Уведомить!',
	'PHPSHOP_SEARCH_ALL_CATEGORIES' => 'Поиск во всех категориях',
	'PHPSHOP_SEARCH_ALL_PRODINFO' => 'Поиск в всей информации о товаре',
	'PHPSHOP_SEARCH_PRODNAME' => 'Только в наименовании товара',
	'PHPSHOP_SEARCH_MANU_VENDOR' => 'Только в Производителях/Продавцах',
	'PHPSHOP_SEARCH_DESCRIPTION' => 'Только в описании товара',
	'PHPSHOP_SEARCH_AND' => 'и',
	'PHPSHOP_SEARCH_NOT' => 'не',
	'PHPSHOP_SEARCH_TEXT1' => 'Первый выпадающий список позволяет Вам выбрать категорию для ограничения результатов поиска. 
        Второй выпадающий список позволяет Вам искать товар по свойствам, например, только по наименованию. 
        Выбрав параметры поиска, введите слово, которое Вы ищите. ',
	'PHPSHOP_SEARCH_TEXT2' => ' Вы можете заново осуществить поиск путем добавления второго слова и выбора оператора И или НЕ. 
        При выборе оператора И, в результаты включатся слова, в которых присутствуют все заданные слова. 
        При выборе НЕ, в результаты включатся слова, в которых присутствует первое слово и отсутствует второе.',
	'PHPSHOP_CONTINUE_SHOPPING' => 'Продолжить покупки',
	'PHPSHOP_AVAILABLE_IMAGES' => 'Доступные изображения для',
	'PHPSHOP_BACK_TO_DETAILS' => 'Назад к описанию товара',
	'PHPSHOP_IMAGE_NOT_FOUND' => 'Изображение не найдено!',
	'PHPSHOP_PARAMETER_SEARCH_TEXT1' => 'Вы хотите найти товары в соответствии с их техническими параметрами?<BR>Вы можете использовать любую подготовленную форму:',
	'PHPSHOP_PARAMETER_SEARCH_NO_PRODUCT_TYPE' => 'Извините, но категорий для поиска нет.',
	'PHPSHOP_PARAMETER_SEARCH_BAD_PRODUCT_TYPE' => 'Извините, но товара с таким названием нет.',
	'PHPSHOP_PARAMETER_SEARCH_IS_LIKE' => 'Чтобы присутствовал',
	'PHPSHOP_PARAMETER_SEARCH_IS_NOT_LIKE' => 'Чтобы отсутствовал',
	'PHPSHOP_PARAMETER_SEARCH_FULLTEXT' => 'Полнотекстовый поиск',
	'PHPSHOP_PARAMETER_SEARCH_FIND_IN_SET_ALL' => 'Все выбранные',
	'PHPSHOP_PARAMETER_SEARCH_FIND_IN_SET_ANY' => 'Любое выбранное',
	'PHPSHOP_PARAMETER_SEARCH_RESET_FORM' => 'Очистить форму',
	'PHPSHOP_PRODUCT_NOT_FOUND' => 'Извините, но запрошенный товар не найден!',
	'PHPSHOP_PRODUCT_PACKAGING1' => 'Количество {штук} в упаковке:',
	'PHPSHOP_PRODUCT_PACKAGING2' => 'Количество {штук} в коробке:',
	'PHPSHOP_CART_PRICE_PER_UNIT' => 'Цена за шт.',
	'VM_PRODUCT_ENQUIRY_LBL' => 'Задайте вопрос по этому товару',
	'VM_RECOMMEND_FORM_LBL' => 'Рекомендовать товар другу',
	'PHPSHOP_EMPTY_YOUR_CART' => 'Очистить корзину',
	'VM_RETURN_TO_PRODUCT' => 'Вернуться к товару',
	'EMPTY_CATEGORY' => 'В данной категории нет товаров.',
	'ENQUIRY' => 'Запрос',
	'NAME_PROMPT' => 'Ваше имя',
	'EMAIL_PROMPT' => 'E-mail',
	'MESSAGE_PROMPT' => 'Ваше сообщение',
	'SEND_BUTTON' => 'Отправить',
	'THANK_MESSAGE' => 'Спасибо за Ваш запрос. Мы свяжемся с Вами в ближайшее время.',
	'PROMPT_CLOSE' => 'Закрыть',
	'VM_RECOVER_CART_REPLACE' => 'Заменить содержимое корзины на содержимое сохраненной корзины',
	'VM_RECOVER_CART_MERGE' => 'Добавить содержимое сохраненной корзины к содержимому текущей корзины',
	'VM_RECOVER_CART_DELETE' => 'Удалить содержимое сохраненной корзины',
	'VM_EMPTY_YOUR_CART_TIP' => 'Очистить корзину от всего содержимого',
	'VM_SAVED_CART_TITLE' => 'Корзина сохранена',
	'VM_SAVED_CART_RETURN' => 'Возврат'
); $VM_LANG->initModule( 'shop', $langvars );
?>