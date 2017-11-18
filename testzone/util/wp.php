<?php
$IBLOCK_ID=10;
$arResult=array();
$el = new CIBlockElement;
switch($action){
    case 'wp_id':
        $arFilter=array('IBLOCK_ID'=>$IBLOCK_ID,'ACTIVE'=>'Y');
        $arFilterOr=array("LOGIC" => "OR");
        $ar_products=array();
        foreach($json as $props){
            $arFilterOr[]=array("XML_ID"=>$props['xml_id']);
            $ar_products[intval($props['xml_id'])]=$props['wp_id'];
        }
        $arFilter[]=$arFilterOr;
        $result=CIBlockElement::GetList(array(),$arFilter,false,false,array('ID','XML_ID'));
        while($get=$result->getNext()){
            $op_result = $el->SetPropertyValueCode($get['ID'], "WP_ID", "".$ar_products[intval($get['XML_ID'])]);
            $arResult['PRODUCTS'][] = array(
                'ID' => $get['ID'],
                'XML_ID' => $get['XML_ID'],
                'WP_ID' => $ar_products[intval($get['XML_ID'])],
                'OP_RESULT' => $op_result
            );
        }
        break;
}
echo json_encode($arResult);
