<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("IBLOCK_WHM_NAME"),
	"DESCRIPTION" => GetMessage("IBLOCK_WHM_DESCRIPTION"),
	"ICON" => "/images/news_all.gif",
	"COMPLEX" => "N",
	"PATH" => array(
		"ID" => "orangeRocket",
		"CHILD" => array(
			"ID" => "DELIVERY_POINT_MARK",
			"NAME" => GetMessage("IBLOCK_WHM_NAME"),
			"SORT" => 10,
		),
	),
);

?>