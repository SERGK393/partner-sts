<?php
$IBLOCK_ID=10;

$arResult=array();
$result=CIBlockElement::GetList(array(),array('IBLOCK_ID'=>$IBLOCK_ID,'ACTIVE'=>'Y'),false,false,array('ID','XML_ID'));
while($get=$result->getNext()){
    $db_set=CCatalogProductSet::getAllSetsByProduct($get['ID'], '2');
    foreach($db_set as $ar_sets){
        foreach($ar_sets['ITEMS'] as $ar_set){
            if(!isset($arResult['SETS'][$ar_set['ITEM_ID']])){
                $res = CIBlockElement::GetByID($ar_set['ITEM_ID']);
                if($ar_res = $res->GetNext())
                    $arResult['ITEMS'][$get['XML_ID']][$ar_set['ITEM_ID']] = $ar_res['XML_ID'];
            }
        }
    }
}

echo json_encode($arResult);
