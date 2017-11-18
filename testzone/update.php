<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$file=$_FILES['file']['tmp_name'];
$iblock_id=10;
global $APPLICATION;

if (CModule::IncludeModule("iblock")&&CModule::IncludeModule("catalog")){
    
    $iblock=new CIBlockElement();
    $count=0;

    if ($handle = fopen($file, 'r')or die('Err on open file')) {
        while ($data = fgetcsv($handle, 0, "^")) {
            $sku=$data[0];
            $stock=$data[1];
            $reserv=$data[2];
            $spec_price=$data[3];//СПЕЦЦЕНА 1
            $spec_currency=$data[4];//СПЕЦЦЕНА 1 ВАЛЮТА
            $base_price=$data[5];//БАЗОВАЯ ЦЕНА
            //$morz=$data[6];//МОРЖ
            //$morz_index=$data[7];//МОРЖ ИНДЕКС
            
            $proverkaSku = $iblock->GetList(Array(), Array("IBLOCK_ID"=>IntVal($iblock_id), "XML_ID"=>IntVal($sku), "ACTIVE"=>"Y"), false, false, Array("ID","NAME"));
            $proverkaSku_result = ($proverkaSku)?($proverkaSku->FieldsCount()):0;

            if($proverkaSku_result){
                while($get=$proverkaSku->Fetch()){
                    $id=$get['ID'];
                    $update_stock_result = CCatalogProduct::Update($id, array('QUANTITY' => $stock,'QUANTITY_RESERVED' => $reserv));
                    if($update_stock_result){
                        if($spec_price){
                            if(stristr($get['NAME'],'grohe')){
                                $spec_price=$spec_price*0.72;
                            }
                            
                            $db_price = CPrice::GetList(array(),array("PRODUCT_ID" => $id,"CATALOG_GROUP_ID" => 2));
                            if($db_price){
                                $price_id=$db_price->Fetch()['ID'];
                                $update_price_result = CPrice::Update($price_id, Array("PRODUCT_ID" => $id,"PRICE" => $spec_price,"CURRENCY" => $spec_currency));
                                if($update_price_result){
                                    $count++;
                                }else{
                                    $arFields = Array(
                                        "PRODUCT_ID" => $id,
                                        "CATALOG_GROUP_ID" => 2,
                                        "PRICE" => $spec_price,
                                        "CURRENCY" => $spec_currency
                                    );
                                    $update_price_result = CPrice::Add($arFields);
                                    if($update_price_result){
                                        $count++;
                                    }else{
                                        echo 'exception:'.$update_price_result;
                                    }
                                }
                            }
                            if($base_price){
                                CIBlockElement::SetPropertyValueCode($id, "value1", $base_price);
                            }
                        }else{
                            $count++;
                        }
                    }
                }
            }
        }
        echo $count;
    }

}
