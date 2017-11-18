<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arDays=array('1'=>'Понедельник','Вторник','Среда','Четверг','Пятница','Суббота','Воскресенье');

$arComponentParameters = array(

	"PARAMETERS" => array(
		"NO_CALL_WEEKDAY" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("NO_CALL_WEEKDAY"),
			"TYPE" => "LIST",
			"VALUES" => $arDays,
			"MULTIPLE" => "Y",
			
		),
		"TIME_BEGIN" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("TIME_BEGIN"),
			"TYPE" => "STRING",
			
		),
		"TIME_END" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("TIME_END"),
			"TYPE" => "STRING",
			
		),
		"SATURDAY_TIME_BEGIN" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SATURDAY_TIME_BEGIN"),
			"TYPE" => "STRING",
			
		),
		"SATURDAY_TIME_END" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SATURDAY_TIME_END"),
			"TYPE" => "STRING",
			
		),
		"SUNDAY_TIME_BEGIN" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SUNDAY_TIME_BEGIN"),
			"TYPE" => "STRING",
			
		),
		"SUNDAY_TIME_END" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SUNDAY_TIME_END"),
			"TYPE" => "STRING",
			
		),
			"HOLLYDAYS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("HOLLYDAYS"),
			"TYPE" => "STRING",
			"ADDITIONAL_VALUES"=>"Y",
			"MULTIPLE" => "Y",
		),)
);

?>
