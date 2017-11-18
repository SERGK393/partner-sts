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

CModule::IncludeModule("iblock");

$arFilter = Array(
    "IBLOCK_TYPE"=>$arParams['IBLOCK_TYPE'],
    "IBLOCK_ID"=>$arParams['IBLOCK_ID'],
    "ACTIVE"=>"Y",
);
$arOrder = Array(
    $arParams['SORT_BY']=>$arParams['SORT_ORDER'],
);
$res = CIBlockElement::GetList($arOrder, $arFilter, false, array("nPageSize"=>$arParams['NEWS_COUNT']),array("ID","NAME","DETAIL_PICTURE"));
while($ar_fields = $res->fetch())
{
    if ($ar_fields['DETAIL_PICTURE'])
    {
        $arFileTmp = CFile::ResizeImageGet(
            $ar_fields['DETAIL_PICTURE'],
            array("width" => $arParams['PICTURE_WIDTH'], "height" => $arParams['PICTURE_HEIGHT']),
            BX_RESIZE_IMAGE_PROPORTIONAL,
            true
        );
        $ar_fields['DETAIL_PICTURE'] = $arFileTmp;
    }
    unset($ar_fields['ACTIVE_FROM']);
    $arResult['ITEMS'][]=$ar_fields;
}

$this->includeComponentTemplate();

?>