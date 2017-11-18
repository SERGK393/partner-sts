<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("IBLOCK_DP_NAME"),
	"DESCRIPTION" => GetMessage("IBLOCK_DP_DESCRIPTION"),
	"ICON" => "/images/news_all.gif",
	"COMPLEX" => "N",
	"PATH" => array(
		"ID" => "orangeRocket",
		"CHILD" => array(
			"ID" => "DELIVERY_POINT",
			"NAME" => GetMessage("IBLOCK_DP_NAME"),
			"SORT" => 10,
		),
	),
);

?>