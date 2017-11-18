<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */


$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
        "ELEMENT_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("ELEMENT_ID"),
            "TYPE" => "STRING",
            "DEFAULT" => '',
        ),
        "ELEMENT_NAME" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("ELEMENT_NAME"),
            "TYPE" => "STRING",
            "DEFAULT" => '',
        ),
        "ELEMENT_PRICE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("ELEMENT_PRICE"),
            "TYPE" => "STRING",
            "DEFAULT" => '',
        )/*,
		"CACHE_TIME"  =>  Array("DEFAULT"=>36000000),*/
	),
);
?>
