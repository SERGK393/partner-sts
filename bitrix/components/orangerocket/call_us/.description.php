<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("IBLOCK_CU_NAME"),
	"DESCRIPTION" => GetMessage("IBLOCK_CU_DESCRIPTION"),
	"ICON" => "/images/news_all.gif",
	"COMPLEX" => "N",
	"PATH" => array(
		"ID" => "orangeRocket",
		"CHILD" => array(
			"ID" => "CALL_US",
			"NAME" => GetMessage("IBLOCK_CU_NAME"),
			"SORT" => 10,
		),
	),
);

?>