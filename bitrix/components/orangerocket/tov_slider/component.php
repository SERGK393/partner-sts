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
$arFilter=array(
"IBLOCK_TYPE"=>$arParams["IBLOCK_TYPE"],
"IBLOCK_ID"=>$arParams["IBLOCK_ID"],
"ACTIVE"=>"Y",
"PROPERTY_top3_VALUE"=>"Y",

	);
$arOrder=array(
$arParams["SORT_BY1"]=>$arParams["SORT_ORDER1"],
"PROPERTY_TOP3"=>"DESC",
	);
$res=CIBlockElement::GetList(
	$arOrder,$arFilter,	false,false,Array("ID","IBLOCK_ID","IBLOCK_SECTION_ID","NAME","CATALOG_GROUP_1","DETAIL_PAGE_URL","DETAIL_PICTURE")
);
//$res->SetUrlTemplates("#SITE_DIR#/catalog/#SECTION_CODE#/#ELEMENT_CODE#.html");
$arResult=array();
while ($result=$res->GetNext(true,false)) {
$arButtons = CIBlock::GetPanelButtons(
    $result["IBLOCK_ID"],
    $result["ID"],
    $result["IBLOCK_SECTION_ID"],
    array("SECTION_BUTTONS"=>false, "SESSID"=>false, "CATALOG"=>true)
);

$reslt['href']=$result['DETAIL_PAGE_URL'];
$reslt['edit_href']=$arButtons["edit"]["edit_element"]["ACTION_URL"];
$reslt['delete_href']=$arButtons["edit"]["delete_element"]["ACTION_URL"];
$reslt['name']=$result['NAME'];
$reslt['price']=$result['CATALOG_PRICE_1'];
$reslt['id']=$result['ID'];
$reslt['iblock']=$result['IBLOCK_ID'];

 if ($result['DETAIL_PICTURE'])
    {
        $arFileTmp = CFile::ResizeImageGet(
            $result['DETAIL_PICTURE'],
            array("width" => $arParams['PICTURE_WIDTH'], "height" => $arParams['PICTURE_HEIGHT']),
            BX_RESIZE_IMAGE_PROPORTIONAL,
            true
        );
        $reslt['img'] = $arFileTmp;
    }

	$arResult['PRODUCTS'][]=$reslt;

}
$this->includeComponentTemplate();

?>