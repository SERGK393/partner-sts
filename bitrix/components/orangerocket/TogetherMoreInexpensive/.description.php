<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("TMI_NAME"),
	"DESCRIPTION" => GetMessage("TMI_DESC"),
	"ICON" => "/images/news_all.gif",
	"COMPLEX" => "N",
	"PATH" => array(
		"ID" => "orangeRocket",
		"CHILD" => array(
			"ID" => "TOGETHER_MORE_INEXPENSIVE",
			"NAME" => GetMessage("IBLOCK_WHM_NAME"),
			"SORT" => 10,
		),
	),
);

?>