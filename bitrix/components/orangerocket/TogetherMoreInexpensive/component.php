<?
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("highloadblock");
CModule::IncludeModule("sale");
 $catId=GarbageStorage::get('ElEM_CAT_ID');
 $elem_price=$arParams["ELEMENT_PRICE"];
 $hlblock_id=7;
 use Bitrix\Highloadblock as HL;
 use Bitrix\Main\Entity;
 
 	$hlblock_requests = HL\HighloadBlockTable::getById($hlblock_id)->fetch();
    $entity_requests=HL\HighloadBlockTable::compileEntity($hlblock_requests);
	$entity_requests_data_class = $entity_requests->getDataClass();
	$main_query_requests = new Entity\Query($entity_requests_data_class);
	$main_query_requests->setSelect(array('*'));
	$main_query_requests->setFilter(
		array("UF_TI_CAT"=>$catId,
		)
	);
	$result_requests = $main_query_requests->exec();
	$result_requests = new CDBResult($result_requests);

	while ($row_requests=$result_requests->GetNext()) {
		$requests[] = $row_requests; //массив выбранных элементов
	}
	
	foreach($requests as $request){
	$section=$request["UF_TI_CAT_P"];

	$colVo=$request['UF_TI_N'];
	$minP=$request['UF_TI_MIN']/100*$elem_price;
	$maxP=$request['UF_TI_MAX']/100*$elem_price;
	$disCount=$request['UF_TI_D'];
	
 $arOrder = Array("PROPERTY_morz_index"=>"ASC");	
 $arFilter = Array("SECTION_ID"=>$section,"><CATALOG_PRICE_1"=>array($minP,$maxP),"!PROPERTY_morz_index"=>0,">CATALOG_QUANTITY"=>0);
 $arNavStartParams = Array("nTopCount" => $colVo);
 $arSelectFields = Array("CATALOG_GROUP_1","PREVIEW_PICTURE",'DETAIL_PAGE_URL','NAME','IBLOCK_ID','ID','PROPERTY_morz');
 
 if(!empty($request["UF_TI_EX_PROP"])){
	foreach($request["UF_TI_EX_PROP"] as $propRow){
	 $propRow=explode('*',$propRow);
	 $exPr='!PROPERTY_'.$propRow[0];
	 $exPrVal=$propRow[1];
	 $arFilter[$exPr]=$exPrVal;
	  }
	 }

$res=CIBlockElement::GetList(
 $arOrder,
 $arFilter,
 false,
 $arNavStartParams,
 $arSelectFields);
 $arResult["COMPONENT_DIRECTORY"] = $this->GetPath();
 $arResult["PARENT_ID"] = $arParams['PARENT_ID'];	

		while($sup_res=$res->GetNext()){
		$id=$sup_res['ID'];
		$arResult['ITEMS'][$id]['ID'] = $id;
		$arResult['ITEMS'][$id]['NAME'] = $sup_res['NAME'];
		$arResult['ITEMS'][$id]['IMG'] = CFile::ShowImage($sup_res["PREVIEW_PICTURE"],140,140);
		$arResult['ITEMS'][$id]['DISCOUNT'] = ((round($sup_res["PROPERTY_MORZ_VALUE"]/1000*$disCount))*10);
		$arResult['ITEMS'][$id]['PRICE'] = CurrencyFormat($sup_res['CATALOG_PRICE_1'],"RUB");
		$arResult['ITEMS'][$id]['DISCOUNT_PRICE'] = CurrencyFormat($sup_res['CATALOG_PRICE_1']-$arResult['ITEMS'][$id]['DISCOUNT'],"RUB");
		$arResult['ITEMS'][$id]["DETAIL_PAGE_URL"] =$sup_res["DETAIL_PAGE_URL"];
		$arResult['ITEMS'][$id]['DISCOUNT_R']=CurrencyFormat($arResult['ITEMS'][$id]['DISCOUNT'],"RUB");
		 if($cntBasketItems = CSaleBasket::GetList(
   array(),
   array( 
      "FUSER_ID" => CSaleBasket::GetBasketUserID(),
      "LID" => SITE_ID,
      "ORDER_ID" => "NULL",
      "PRODUCT_ID"=>$id
   ), 
   array()
)==0){
									$arResult['ITEMS'][$id]['IN_CART']=false;
																	}else{
									$arResult['ITEMS'][$id]['IN_CART']=true;
								}
	}
}
$this->includeComponentTemplate();
