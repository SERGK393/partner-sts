<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
if(isPartnerClient())$APPLICATION->SetTitle("Информация о менеджере");
?> <?$APPLICATION->IncludeComponent(
	"orangerocket:partner_manager",
	"",
	Array(
	)
);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>