<?php
$IBLOCK_ID=10;
$arFilter=array('IBLOCK_ID'=>$IBLOCK_ID,'ACTIVE'=>'Y');
$arFilterOr=array("LOGIC" => "OR");
foreach($json as $xml_id=>$props){
    $arFilterOr[]=array("XML_ID"=>$xml_id);
}
$arFilter[]=$arFilterOr;

$arResult=array();
$result=CIBlockElement::GetList(array(),$arFilter,false,false,array('ID','XML_ID','NAME','IBLOCK_SECTION_ID','DETAIL_TEXT','DETAIL_PAGE_URL'));
while($get=$result->getNext()){
    $db_props=CIBlockElement::GetProperty($IBLOCK_ID,$get['ID'],array(),array('EMPTY'=>'NO','ACTIVE'=>'Y'));
    if($db_props) {
        $arItem = array();
        $arItem['XML_ID'] = $get['XML_ID'];
        $arItem['NAME'] = $get['NAME'];
        $arItem['DETAIL_PAGE_URL'] = $get['DETAIL_PAGE_URL'];
        $arItem['DETAIL_TEXT'] = $get['DETAIL_TEXT'];
        $arProperties = array();
        while ($prop = $db_props->getNext(true, false)) {
            $arProperty = isset($arProperties[$prop['CODE']]) ? $arProperties[$prop['CODE']] : array();
            if($prop['CODE']!='morz'&&$prop['CODE']!='morz_index'&&$prop['CODE']!='BLOG_POST_ID'&&$prop['CODE']!='value1'&&$prop['CODE']!='komplekt_postavki') {
                if (!empty($prop['VALUE'])) {
                    $arProperty['ID'] = $prop['ID'];
                    $arProperty['NAME'] = $prop['NAME'];
                    $arProperty['CODE'] = $prop['CODE'];
                    if ($prop['CODE'] == 'dop_pictures') {
                        $img = CFile::GetFileArray($prop['VALUE']);
                        if ($img) $arResult['PICTURES'][$prop['VALUE']] = $img['SRC'];
                        $arProperty['VALUES'][] = 'http://www.santehsmart.ru' . $img['SRC'];
                    } elseif (is_numeric($prop['VALUE'])) {
                        $prop_val_result = CIBlockPropertyEnum::GetList(array(), array('ID' => $prop['VALUE'], 'PROPERTY_ID' => $prop['CODE']));
                        if ($prop_val_get = $prop_val_result->fetch()) {
                            $arProperty['VALUES'][] = $prop_val_get['VALUE'];
                        } else {
                            $arProperty['VALUES'][] = $prop['VALUE'];
                        }
                    } else $arProperty['VALUES'][] = $prop['VALUE'];
                    $arProperties[$prop['CODE']] = $arProperty;
                }
            }
        }
        $arItem['PROPERTIES'] = $arProperties;
        $db_set=CCatalogProductSet::getAllSetsByProduct($get['ID'], '2');
        foreach($db_set as $ar_sets){
            foreach($ar_sets['ITEMS'] as $ar_set){
                if(!isset($arItem['SETS'][$ar_set['ITEM_ID']])){
                    $res = CIBlockElement::GetByID($ar_set['ITEM_ID']);
                    if($ar_res = $res->GetNext())
                        $arItem['SETS'][$ar_set['ITEM_ID']] = $ar_res['XML_ID'];
                }
            }
        }
        $arResult['ITEMS'][$arItem['XML_ID']] = $arItem;
    }
}

echo json_encode($arResult);