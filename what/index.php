<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Что с заказом?");
?> <?$APPLICATION->IncludeComponent(
	"orangerocket:what",
	"",
	Array(
	)
);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>