<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("IBLOCK_multiCity_NAME"),
	"DESCRIPTION" => GetMessage("IBLOCK_multiCity_DESCRIPTION"),
	"ICON" => "/images/news_all.gif",
	"COMPLEX" => "Y",
	"PATH" => array(
		"ID" => "orangeRocket",
		"CHILD" => array(
			"ID" => "multiCity",
			"NAME" => GetMessage("T_IBLOCK_DESC_multiCity"),
			"SORT" => 10,
			"CHILD" => array(
				"ID" => "multiCity_cmpx",
			),
		),
	),
);

?>