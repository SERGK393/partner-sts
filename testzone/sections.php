<?php
$IBLOCK_ID=10;
$list_db = CIBlockSection::GetList(array('LEFT_MARGIN' => 'ASC'), Array("IBLOCK_ID"=>$IBLOCK_ID), false, Array("ID","NAME","DEPTH_LEVEL"));
$arResult=array();
$arSections=array();
while($get=$list_db->fetch()){
    $path[$get['DEPTH_LEVEL']]=$get['NAME'];
    $name=array();
    for($i=1;$i<=$get['DEPTH_LEVEL'];$i++){
        $name[]=$path[$i];
    }
    $name=implode('/',$name);
    if(!$noprop){
        $props=array();
        if((isset($section_id)||isset($section_name))&&($section_id==$get['ID']||$section_name==$name)){
            foreach(CIBlockSectionPropertyLink::GetArray($IBLOCK_ID, $get['ID']) as $prop){
                $prop_id=$prop['PROPERTY_ID'];
                $prop=CIBlockProperty::GetByID($prop_id)->getNext();
                $prop_code=$prop['CODE'];
                $prop_name=$prop['NAME'];
                $prop_values=array();
                $arSort=$prop_code=='GLOBAL_TYPE'?Array('SORT'=>'ASC','ID'=>'ASC'):Array('VALUE'=>'ASC','ID'=>'ASC');
                $db_enum_list = CIBlockPropertyEnum::GetList($arSort, Array("IBLOCK_ID"=>$IBLOCK_ID, "CODE"=>$prop_code));
                while($ar_enum_list = $db_enum_list->GetNext(false,false)) {
                    $prop_values[''.$ar_enum_list['ID']]=$ar_enum_list['VALUE'];
                }
                if($prop_code=='vendor'){
                    $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById(2)->fetch();
                    $hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
                    $hlDataClass = $hldata['NAME'].'Table';

                    $result = $hlDataClass::getList(array(
                        'order' => array('UF_SORT' =>'ASC'),
                        'select' => array('UF_XML_ID','UF_NAME')
                    ));

                    while($res = $result->fetch()) {
                        $prop_values[$res['UF_XML_ID']]=$res['UF_NAME'];
                    }
                }
                if($prop_code=='komplekt_postavki'){
                    $result = CIBlockElement::GetList(array(),array('IBLOCK_ID'=>$prop['LINK_IBLOCK_ID']),false,false,array('ID','NAME'));
                    while($res=$result->GetNext(false,false)){
                        $prop_values[$res['ID']]=$res['NAME'];
                    }
                }
                $prop_type=(($prop_code=='vendor')||($prop_code=='komplekt_postavki'))?'L':$prop['PROPERTY_TYPE'];
                if($prop_code!='product_sku'&&$prop_code!='top3'&&$prop_code!='ym_code'){
                    $props[$prop_code]=array(
                        'CODE'=>$prop_code,
                        'NAME'=>$prop_name,
                        'VALUES'=>$prop_values,
                        'PROPERTY_TYPE'=>$prop_type,
                        'MULTIPLE'=>$prop['MULTIPLE']
                    );
                }
            }
        }
        $arSection=array();
        $arSection['ID']=$get['ID'];
        $arSection['NAME']=$name;
        $arSection['PROPERTIES']=$props;
        $arSections[]=$arSection;
    }else{
        $arSection=array();
        $arSection['ID']=$get['ID'];
        $arSection['NAME']=$name;
        $arSections[]=$arSection;
    }
}
$arResult['SECTIONS']=$arSections;
if($print){
    echo json_encode($arResult);
}
