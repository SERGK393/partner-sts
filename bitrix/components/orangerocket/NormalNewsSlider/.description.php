<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("NNS_NAME"),
	"DESCRIPTION" => GetMessage("NNS_DESC"),
	"ICON" => "/images/news_all.gif",
	"COMPLEX" => "N",
	"PATH" => array(
		"ID" => "orangeRocket",
		"CHILD" => array(
			"ID" => "NORMANEWSSLIDER",
			"NAME" => GetMessage("IBLOCK_NNS_NAME"),
			"SORT" => 10,
		),
	),
);

?>