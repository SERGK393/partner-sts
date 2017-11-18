<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */


include_once $_SERVER["DOCUMENT_ROOT"].'/testzone/util/platform.php';
include_once getPlatformPath();

$pl = new Platform();
$partner_id = isPartnerClient();
if($partner_id) {
    $arManager = $pl->getManagerByPartnerId($partner_id);
    $arResult['MANAGER'] = $arManager;
}else $arResult['MANAGER'] = false;

$this->includeComponentTemplate();



?>