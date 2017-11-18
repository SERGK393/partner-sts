<?
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

	
 $arOrder = Array("SORT"=>"DESC");	
 $arFilter = Array("IBLOCK_ID"=>17,"ACTIVE"=>"Y");
 $arNavStartParams = Array();
 $arSelectFields = Array("ID","DETAIL_PICTURE","PROPERTY_publish","PROPERTY_content","PROPERTY_name","PROPERTY_urll","PROPERTY_action");
 
 $res=CIBlockElement::GetList(
 $arOrder,
 $arFilter,
 false,
 $arNavStartParams,
 $arSelectFields);
 while($resArr=$res->GetNext()){
	$arResult[]=$resArr;	
	}
$this->includeComponentTemplate();
