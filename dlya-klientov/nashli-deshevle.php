<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Нашли Дешевле");
?><?$APPLICATION->IncludeComponent("bitrix:form", "template1", Array(
	"AJAX_MODE" => "N",	// Включить режим AJAX
		"SEF_MODE" => "N",	// Включить поддержку ЧПУ
		"WEB_FORM_ID" => "1",	// ID веб-формы
		"RESULT_ID" => $_REQUEST[RESULT_ID],	// ID результата
		"START_PAGE" => "new",	// Начальная страница
		"SHOW_LIST_PAGE" => "Y",	// Показывать страницу со списком результатов
		"SHOW_EDIT_PAGE" => "Y",	// Показывать страницу редактирования результата
		"SHOW_VIEW_PAGE" => "Y",	// Показывать страницу просмотра результата
		"SUCCESS_URL" => "",	// Страница с сообщением об успешной отправке
		"SHOW_ANSWER_VALUE" => "N",	// Показать значение параметра ANSWER_VALUE
		"SHOW_ADDITIONAL" => "N",	// Показать дополнительные поля веб-формы
		"SHOW_STATUS" => "Y",	// Показать текущий статус результата
		"EDIT_ADDITIONAL" => "N",	// Выводить на редактирование дополнительные поля
		"EDIT_STATUS" => "Y",	// Выводить форму смены статуса
		"NOT_SHOW_FILTER" => "",	// Коды полей, которые нельзя показывать в фильтре
		"NOT_SHOW_TABLE" => "",	// Коды полей, которые нельзя показывать в таблице
		"CHAIN_ITEM_TEXT" => "",	// Название дополнительного пункта в навигационной цепочке
		"CHAIN_ITEM_LINK" => "",	// Ссылка на дополнительном пункте в навигационной цепочке
		"IGNORE_CUSTOM_TEMPLATE" => "N",	// Игнорировать свой шаблон
		"USE_EXTENDED_ERRORS" => "N",	// Использовать расширенный вывод сообщений об ошибках
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "3600",	// Время кеширования (сек.)
		"CACHE_NOTES" => "",
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"VARIABLE_ALIASES" => array(
			"action" => "action",
		)
	),
	false
);?> 
<br />
 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>