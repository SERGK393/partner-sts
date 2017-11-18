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


$arResult['product_id']=$arParams['ELEMENT_ID'];
$arResult['product_name']=$arParams['ELEMENT_NAME'];
$arResult['product_price']=$arParams['ELEMENT_PRICE'];
$user=IntVal($USER->GetID());
$arResult['user']=$user?$user:24;
$arResult['name']=$USER->GetFirstName();
$arResult['city']=$_SESSION['TF_LOCATION_SELECTED_CITY_NAME'];

$this->includeComponentTemplate();



?>