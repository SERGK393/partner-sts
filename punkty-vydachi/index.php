<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Пункты выдачи");
?> <?$APPLICATION->IncludeComponent(
	"orangerocket:delivery_points",
	".default",
	Array(
		"IBLOC_ID" => "18",
		"CACHE_TYPE" => "N",
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
