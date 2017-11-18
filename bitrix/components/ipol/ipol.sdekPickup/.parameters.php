<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!\Bitrix\Main\Loader::includeModule("sale"))
	return;

if(!cmodule::includeModule('ipol.sdek'))
	return false;

$arCities = array();
$arList = CDeliverySDEK::getListFile();
$arCities=array_keys($arList);

$arComponentParameters = array(
	"PARAMETERS" => array(
		"NOMAPS" => array(
			"PARENT"   => "BASE",
			"NAME"     => GetMessage('IPOLSDEK_COMPOPT_NOMAPS'),
			"TYPE"     => "CHECKBOX",
		),
		"CNT_DELIV" => array(
			"PARENT"   => "BASE",
			"NAME"     => GetMessage('IPOLSDEK_COMPOPT_CNT_DELIV'),
			"TYPE"     => "CHECKBOX",
		),		
		"CNT_BASKET" => array(
			"PARENT"   => "BASE",
			"NAME"     => GetMessage('IPOLSDEK_COMPOPT_CNT_BASKET'),
			"TYPE"     => "CHECKBOX",
		),
		"FORBIDDEN" => array(
			"PARENT"   => "BASE",
			"NAME"     => GetMessage('IPOLSDEK_COMPOPT_FORBIDDEN'),
			"TYPE"     => "LIST",
			"VALUES"   => array(0 => '', 'pickup' => GetMessage('IPOLSDEK_PROF_PICKUP'), 'courier' => GetMessage('IPOLSDEK_PROF_COURIER')),
			"SIZE"     => 3,
			"MULTIPLE" => "Y",
		),
		"CITIES" => array(
			"PARENT"   => "BASE",
			"NAME"     => GetMessage('IPOLSDEK_COMPOPT_CITIES'),
			"TYPE"     => "LIST",
			"VALUES"   => $arCities,
			"SIZE"     => count($arCities),
			"MULTIPLE" => "Y",
		),
	),
);
?>