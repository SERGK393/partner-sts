<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(isset($_GET['optype'])){

$sku=$_REQUEST['sku'];
$iblock_id=$_REQUEST['iblock'];
$stock=$_REQUEST['stock'];
$reserv=$_REQUEST['reserv'];
$optype=$_REQUEST['optype'];
$price1c=$_REQUEST['price'];

if (CModule::IncludeModule("iblock")&&CModule::IncludeModule("catalog")){

$iblock=new CIBlockElement();

$proverkaSku = $iblock->GetList(Array(), Array("IBLOCK_ID"=>IntVal($iblock_id), "XML_ID"=>IntVal($sku), "ACTIVE"=>"Y"), false, false, Array("ID"));
$proverkaSku_result = ($proverkaSku)?($proverkaSku->FieldsCount()):0;

if($proverkaSku_result==0){
echo "2 product not found";
}
else{

switch($optype)
{
    case 1://Запись кол-ва
	    while($id=$proverkaSku->Fetch()['ID']){
            $update_stock_result = CCatalogProduct::Update($id, array('QUANTITY' => $stock,'QUANTITY_RESERVED' => $reserv));
        }
echo "3 $sku end ".($update_stock_result?"TRUE":"FALSE".$APPLICATION->GetException());
		break;
case 2://Запись цены
	    while($id=$proverkaSku->Fetch()['ID']){
		    $db_price = CPrice::GetList(array(),array("PRODUCT_ID" => $id));
		    if($db_price){
			    $price_id=$db_price->Fetch()['ID'];
			    $update_price_result = CPrice::Update($price_id, Array("PRODUCT_ID" => $id,"PRICE" => $price1c));
		    }else{
			    $update_price_result = false;
		    }
		}
echo "3 $sku end ".($update_price_result?"TRUE":"FALSE ".$APPLICATION->GetException());
		break;
    case 5://Снятие с публикации
	    while($id=$proverkaSku->Fetch()['ID']){
	        $ibl=new CIBlockElement();
            $update_publ = $ibl->Update($id, array("ACTIVE" => 'N'),true);
        }
echo "ok $sku $id end ".($update_publ?"TRUE":"FALSE ".$ibl->LAST_ERROR);
		break;
    default:
       echo "4 optype invalid";
       break;


}}
}

}//если условие не сработало, то ничего не пишет, поэтому будет ошибка по возвращенной строке

else{
echo "NOT ACCESS";

}
