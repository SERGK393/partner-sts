<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$module_id = "ipol.sdek";
CModule::IncludeModule($module_id);

if(method_exists('sdekHelper',$_POST['action']))
	sdekHelper::$_POST['action']($_POST);
elseif(method_exists('sdekdriver',$_POST['action']))
	sdekdriver::$_POST['action']($_POST);
elseif(method_exists('CDeliverySDEK',$_POST['action']))
	CDeliverySDEK::$_POST['action']($_POST);
?>