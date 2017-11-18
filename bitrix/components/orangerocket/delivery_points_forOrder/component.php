<?
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (CModule::IncludeModule("iblock")) {
 $arOrder = Array("SORT"=>"ASC");
 $arFilter = Array('IBLOCK_ID'=>$arParams['IBLOC_ID'],"NAME"=>$_SESSION['TF_LOCATION_SELECTED_CITY_NAME']);
 $prm=array('UF_DEL_TIME');
$razdel=CIBlockSection::GetList(
$arOrder ,$arFilter,false,$prm );
$arOrder=array('NAME'=>'ASC');
$id=$razdel->getNext();	
$id=$id['ID'];

//////////////////	/////////////////////////////////////////////////////////////////////////////

$arFilter=array('IBLOCK_ID'=>$arParams['IBLOC_ID'],'CNT_ACTIVE'=>'Y','SECTION_ID'=>$id);
$arOrder = Array("SORT"=>"ASC");
$arSelectFields=Array('NAME');
$list=CIBlockElement::GetList(
$arOrder,
$arFilter,false,false,
$arSelectFields
);
while($listRes=$list->getNext()){
	$arResult[]=$listRes['NAME'];
}

}
$this->includeComponentTemplate();
