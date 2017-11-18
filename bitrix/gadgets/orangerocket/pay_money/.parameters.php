<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arParameters = Array(
		"USER_PARAMETERS"=> Array(
			"ORDER_NUMBER" => array(
				"PARENT" => "BASE",
				"NAME" => GetMessage("OR_MONEYPAY_ORDER_NUMBER"),
				"TYPE" => "STRING",
				"DEFAULT" => '0',
			),
			"TERMINAL" => array(
				"PARENT" => "BASE",
				"NAME" => GetMessage("OR_MONEYPAY_TERMINAL"),
				"TYPE" => "STRING",
				"DEFAULT" => '',
			),
			"MERC_GMT" => array(
				"PARENT" => "BASE",
				"NAME" => GetMessage("OR_MONEYPAY_MERC_GMT"),
				"TYPE" => "STRING",
				"DEFAULT" => '+3',
			),
		),
	);
?>
