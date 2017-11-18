<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("razdel");
?><?

$APPLICATION->set_cookie("andrey","balbes ne balbes");
echo "ОБАМА -".$APPLICATION->get_cookie("andrey")."!";


?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>