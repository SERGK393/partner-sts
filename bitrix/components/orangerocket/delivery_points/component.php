<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (CModule::IncludeModule("iblock")) {
$arOrder=array('NAME'=>'ASC');
$arFilter=array('IBLOCK_ID'=>$arParams['IBLOC_ID']);
//echo "<pre>";
$result=CIBlockSection::GetList($arOrder,$arFilter);
while ($ar=$result->GetNext(true,false)) {//ПОЛУЧАЕМ ГОРОДА


$city=$ar['NAME'];



  $arOrder=array('NAME'=>'ASC');  
  $arFilter=array('SECTION_ID'=>$ar['ID']);
  if($result1=CIBlockElement::GetList($arOrder,$arFilter)){//ПОЛУЧАЕМ ПУНКТЫ

   while ($arE=$result1->GetNext(true,false)) {

      
  //  echo $arE['NAME']."\n";
      $arOrder=array('id'=>'ASC');
      $arFilter=array('ACTIVE'=>'Y');
      $punkt_name=$arE['NAME'];
   $resP=CIBlockElement::GetProperty($arParams['IBLOC_ID'],$arE['ID'],$arOrder,$arFilter);
   while($resPf=$resP->GetNext(true,false))
   {//ПОЛУЧАЕМ СВОЙСТВА
   	if($resPf['CODE']=='photo'){
   		$prop[$city][$punkt_name][($resPf['CODE'])][]=$resPf['VALUE'];
   	}else{
    	$prop[$city][$punkt_name][($resPf['CODE'])]=$resPf['VALUE'];
	}


  
  }//СВОЙСТВа 

  }
}
}//ГОРОДА




}
//echo "<pre>";
$arResult['CITIES_ITOG']=$prop;


$this->includeComponentTemplate();
