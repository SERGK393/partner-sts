<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("IBLOCK_TOVSLI_NAME"),
	"DESCRIPTION" => GetMessage("IBLOCK_TOVSLI_DESCRIPTION"),
	"ICON" => "/images/news_all.gif",
	"COMPLEX" => "N",
	"PATH" => array(
		"ID" => "orangeRocket",
		"CHILD" => array(
			"ID" => "TOVSLIDER",
			"NAME" => GetMessage("T_IBLOCK_DESC_TOVSLI"),
			"SORT" => 10,
			"CHILD" => array(
				"ID" => "tovslider_cmpx",
			),
		),
	),
);

?>