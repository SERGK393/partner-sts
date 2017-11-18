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
$_SESSION['DEL_TIME']=$id['UF_DEL_TIME'];
//////////////////	/////////////////////////////////////////////////////////////////////////////
$arFilter=array('CNT_ACTIVE'=>'Y');
$colvo=CIBlockSection::GetSectionElementsCount($id,
 $arFilter
);
switch($colvo){
	case($colvo>1&&$colvo<5):$pnkt="Пункта"; break;
	case($colvo>=5):$pnkt="Пунктов";break;
	default:$pnkt="Пункт";
}$arResult['ctn']=$colvo;
$arResult['pnkt']=$pnkt;
}
$this->includeComponentTemplate();
