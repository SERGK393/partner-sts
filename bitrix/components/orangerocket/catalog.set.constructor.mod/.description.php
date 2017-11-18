<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("IBLOCK_SET_NAME"),
	"DESCRIPTION" => GetMessage("IBLOCK_SET_DESCRIPTION"),
	"ICON" => "/images/cnst.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 100,
	"PATH" => array(
		"ID" => "orangeRocket",
		"CHILD" => array(
			"ID" => "tools",
			"NAME" => GetMessage("T_IBLOCK_DESC_SET"),
			"SORT" => 30,
		)
	),
);

?>