<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Карты");
?><?$APPLICATION->IncludeComponent(
	"speak:maps",
	"",
	Array(
		"SEF_MODE" => "Y",
		"IS_DETAIL_LINK" => "Y",
		"IS_PREVIEW_TEXT" => "Y",
		"IS_MAP_SHOW" => "N",
		"IS_DETAIL" => "Y",
		"IS_BACK_LINK" => "Y",
		"IS_MAP" => "Y",
		"IS_BACK_LINK_O" => "Y",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "N",
		"INCLUDE_MAP_INTO_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"CHECK_DATES" => "Y",
		"IS_JQUERY" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"SEF_FOLDER" => "/maps/",
		"SEF_URL_TEMPLATES" => Array(
			"map.detail" => "#SECTION_CODE#/",
			"object.detail" => "#SECTION_CODE#/#ELEMENT_CODE#/"
		),
		"VARIABLE_ALIASES" => Array(
			"list" => Array(),
			"map.detail" => Array(),
			"object.detail" => Array(),
		)
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>