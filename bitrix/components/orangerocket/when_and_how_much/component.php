<?
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (CModule::IncludeModule("iblock")) {
$workDay=$_SESSION['WORK_TIME'];	
////////////////////////////////////////////////////////////////
$arOrder = Array("SORT"=>"ASC");
$arFilter = Array('IBLOCK_ID'=>$arParams['IBLOC_ID'],"NAME"=>$_SESSION['TF_LOCATION_SELECTED_CITY_NAME']);
$select=array('UF_DEL_TIME','UF_LI1_PRICE','UF_LI2_PRICE','UF_LI3_PRICE','UF_LI4_PRICE');
$res=CIBlockSection::GetList(
$arOrder ,$arFilter,false,$select);
$res=$res->getNext();
$id=$res['ID'];


$delTime=$res['UF_DEL_TIME'];
$logIndex=array(1=>$res['UF_LI1_PRICE'],$res['UF_LI2_PRICE'],$res['UF_LI3_PRICE'],$res['UF_LI4_PRICE']);
if($arParams['QUANTITY']>0){
    $del_time_int=$workDay['TIME']+60*60*24*($delTime+1);
    if(intval(date('d',$del_time_int))==(intval(date('d'))+1)) $arResult['DEL_TIME']='Доставим <b>завтра</b>';
    else $arResult['DEL_TIME']='Доставим до '.date('d.m.y',$del_time_int);//ARRESULT
}else $arResult['DEL_TIME']="Сроки доставки уточняйте у менеджеров.";
/*
	$arOrder = Array("SORT"=>"ASC");
$arFilter = Array('IBLOCK_ID'=>$arParams['IBLOC_ID'],'SECTION_ID'=>"$id");
$arNavStartParams=array('nTopCount'=>1);------------------
$arSelectFields=array('NAME');

$res=CIBlockElement::GetList(
$arOrder,
$arFilter,
$arGroupBy = false,
$arNavStartParams,
$arSelectFields
);
$res=$res->getNext();
$arResult['DEL_POINT']=$res['NAME'];*////DELPOINT NAME HERE

///////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////	
$id= $arParams['ELEMENT_ID'];
$arSelect=array('ID');
$res=CIBlockElement::GetElementGroups(
$id,false,
$arSelect
);
$res=$res->getNext();
$sId=$res['ID'];
GarbageStorage::set('ElEM_CAT_ID', $sId);
////////////////////////////////////////////////////////////////
$arOrder = Array("SORT"=>"ASC");
$arFilter = Array('IBLOCK_ID'=>'10','ID'=>$sId);
$select = Array('UF_LOGISTYC_INDEX');

$res=CIBlockSection::GetList(
    $arOrder,
    $arFilter,false,
    $select,false  
);
$res=$res->getNext();
if($arParams['ELEMENT_PRICE']>50000 || $logIndex[$res['UF_LOGISTYC_INDEX']]<=$arParams['MORZ'] / 6 )
$arResult['DEL_PRICE']=$arResult['DEL_PRICE_POINT']=0;//'<b>Бесплатно</b>';
elseif($arParams['ELEMENT_PRICE']<30000)
{
    $arResult['DEL_PRICE_POINT']=$logIndex[$res['UF_LOGISTYC_INDEX']];//($logIndex[$res['UF_LOGISTYC_INDEX']]).'р.';//ARRESULT
    $arResult['DEL_PRICE']=($logIndex[$res['UF_LOGISTYC_INDEX']])*2;
	$arResult['DEL_PRICE']=(string)((round($arResult['DEL_PRICE']/10))*10);
	//$arResult['DEL_PRICE'].='р.';
}
else {
    $arResult['DEL_PRICE']=($logIndex[$res['UF_LOGISTYC_INDEX']])*1.25;
	$arResult['DEL_PRICE']=(string)((round($arResult['DEL_PRICE']/100))*100);
	//$arResult['DEL_PRICE'].='р.';
    $arResult['DEL_PRICE_POINT']=0;//'<b>Бесплатно</b>';
}	
if($_SESSION['TF_LOCATION_SELECTED_CITY_NAME']=="Воронеж"){
    $arResult['DEL_PRICE_POINT']=0;//'<b>Бесплатно</b>';
    if($arParams['ELEMENT_PRICE']<6000){
        if($res['UF_LOGISTYC_INDEX']>1)$arResult['DEL_PRICE']=500;//'500р.';
        else $arResult['DEL_PRICE']=200;//'200р.';
    }else $arResult['DEL_PRICE']=0;//'<b>Бесплатно</b>';
}

///////////PRICE EDIT ADDITIONAL/////////////////////
//$arResult['DEL_PRICE']=$arResult['DEL_PRICE']*0.5;
//$arResult['DEL_PRICE_POINT']=$arResult['DEL_PRICE_POINT']*0.5;
if(empty($arResult['DEL_PRICE'])) $arResult['DEL_PRICE']='<b>Бесплатно</b>';
else $arResult['DEL_PRICE'].='р.';
if(empty($arResult['DEL_PRICE_POINT'])) $arResult['DEL_PRICE_POINT']='<b>Бесплатно</b>';
else $arResult['DEL_PRICE_POINT'].='р.';

///////////////////////////////////////////////////////////////
}
$this->includeComponentTemplate();
