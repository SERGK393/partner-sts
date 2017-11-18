<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(

	"PARAMETERS" => array(
		"IBLOC_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("DP_IBLOC"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N"),
		"CACHE_TIME"  =>  Array("DEFAULT"=>36000000),
		))

?>
