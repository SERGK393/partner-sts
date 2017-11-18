<?php
if(isset($cat_replace)){
    $arVendors=array();
    $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById(2)->fetch();
    $hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
    $hlDataClass = $hldata['NAME'].'Table';

    $result = $hlDataClass::getList(array(
        'order' => array('UF_NAME' =>'ASC'),
        'select' => array('UF_XML_ID','UF_NAME')
    ));

    while($res = $result->fetch()){
        $arVendors[''.$res['UF_XML_ID']]=$res['UF_NAME'];
    }

    $arResult=array();
    if(!empty($cat_replace)&&isset($limit)&&isset($desc_size)){
        $ar_props=CIBlockSectionPropertyLink::GetArray(10, $cat_replace);
        $arProperties=array();
        foreach($ar_props as $prop){
            $prop_id=$prop['PROPERTY_ID'];
            $prop=CIBlockProperty::GetByID($prop_id)->getNext();
            $prop_code=$prop['CODE'];
            $prop_name=$prop['NAME'];
            $prop_values=array();
            $arSort=$prop_code=='GLOBAL_TYPE'?Array('SORT'=>'ASC','ID'=>'ASC'):Array('VALUE'=>'ASC','ID'=>'ASC');
            $db_enum_list = CIBlockPropertyEnum::GetList($arSort, Array("IBLOCK_ID"=>$IBLOCK_ID, "CODE"=>$prop_code));
            while($ar_enum_list = $db_enum_list->GetNext(false,false)){
                $prop_values[''.$ar_enum_list['ID']]=$ar_enum_list['VALUE'];
            }
            if($prop_code=='vendor'){
                $prop_values=$arVendors;
            }
            if($prop_code=='komplekt_postavki'){
                $result = CIBlockElement::GetList(array(),array('IBLOCK_ID'=>$prop['LINK_IBLOCK_ID']),false,false,array('ID','NAME'));
                while($res=$result->GetNext(false,false)){
                    $prop_values[''.$res['ID']]=$res['NAME'];
                }
            }
            $prop_type=($prop_code=='vendor')?'L':$prop['PROPERTY_TYPE'];
            $arProperties[''.$prop_code]=array(
                'ID'=>$prop_id,
                'CODE'=>$prop_code,
                'NAME'=>$prop_name,
                'PROPERTY_TYPE'=>$prop_type,
                'MULTIPLE'=>$prop['MULTIPLE']
            );
            if(count($prop_values))$arProperties[''.$prop_code]['VALUES']=$prop_values;
        }
        $arResult['PROPERTIES']=$arProperties;
        $arResult['ITEMS']=array();
        $arResult['PICTURES']=array();
        $need_props=count($ar_props);
        $arFilter=array('IBLOCK_ID'=>10,'SECTION_ID'=>$cat_replace,'ACTIVE'=>'Y');
        if(!empty($search))$arFilter['NAME']="%$search%";
        if(!empty($vendor))$arFilter['property_vendor']=$vendor;
        $arSort=array();
        if(!empty($quant))$arSort['CATALOG_QUANTITY']=$quant;
        $result=CIBlockElement::GetList($arSort,$arFilter,false,false,array('ID','XML_ID','NAME','IBLOCK_SECTION_ID','DETAIL_TEXT','DETAIL_PICTURE','PREVIEW_PICTURE','DETAIL_PAGE_URL','CATALOG_QUANTITY'));
        while($get=$result->getNext()){
            $db_props=CIBlockElement::GetProperty(10,$get['ID'],array(),array('EMPTY'=>'NO','ACTIVE'=>'Y'));
            if($db_props){
                $arItem=array();
                $arItem['ID']=$get['ID'];
                $arItem['XML_ID']=$get['XML_ID'];
                $arItem['NAME']=$get['NAME'];
                $arItem['DETAIL_PAGE_URL']=$get['DETAIL_PAGE_URL'];
                $arItem['IBLOCK_SECTION_ID']=$get['IBLOCK_SECTION_ID'];
                $arItem['CATALOG_QUANTITY']=$get['CATALOG_QUANTITY'];
                if(strlen($get['DETAIL_TEXT'])<$desc_size)$arItem['DETAIL_TEXT']=$get['DETAIL_TEXT'];
                if(empty($get['DETAIL_PICTURE']))$arItem['DETAIL_PICTURE']='';
                $arItem['PREVIEW_PICTURE']=CFile::GetFileArray($get['PREVIEW_PICTURE']);
                $arProperties=array();
                while($prop=$db_props->getNext(true,false)){
                    $arProperty=isset($arProperties[''.$prop['CODE']])?$arProperties[''.$prop['CODE']]:array();
                    if(!empty($prop['VALUE'])){
                        $arProperty['ID']=$prop['ID'];
                        $arProperty['NAME']=$prop['NAME'];
                        $arProperty['CODE']=$prop['CODE'];
                        $arProperty['VALUES'][]=$prop['VALUE'];
                        $arProperties[''.$prop['CODE']]=$arProperty;
                        if($prop['CODE']=='dop_pictures'){
                            $img=CFile::GetFileArray($prop['VALUE']);
                            if($img)$arResult['PICTURES'][$prop['VALUE']]=$img['SRC'];
                        }
                    }
                }
                $prop_percent=count($arProperties)/$need_props*100;
                $arItem['PROPERTIES']=$arProperties;
                //$arResult['ITEMS'][''.$prop_percent.'_'.rand(1000,9000)]=$arItem;
                $arResult['ITEMS'][]=$arItem;
            }
            //ksort($arResult['ITEMS']);
            $arResult['ITEMS'] = array_slice($arResult['ITEMS'], 0, $limit);
        }
        echo json_encode($arResult);
    }else{
        $list_db = CIBlockSection::GetList(array('LEFT_MARGIN' => 'ASC'), Array("IBLOCK_ID"=>10), false, Array("ID","CODE","NAME","DEPTH_LEVEL"));
        $path=array();
        while($get=$list_db->fetch()){
            $path[$get['DEPTH_LEVEL']]=$get['NAME'];
            $name=array();
            for($i=1;$i<=$get['DEPTH_LEVEL'];$i++){
                $name[]=$path[$i];
            }
            $name=implode('/',$name);
            $arItem=array();
            $arItem['ID']=$get['ID'];
            $arItem['DEPTH_LEVEL']=$get['DEPTH_LEVEL'];
            $arItem['CODE']=$get['CODE'];
            $arItem['NAME']=$get['NAME'];
            $arItem['FULL_NAME']=$name;
            $arResult['SECTIONS'][]=$arItem;
        }
        if(!$vendors_disabled)$arResult['VENDORS']=$arVendors;
        echo json_encode($arResult);
    }
    return;
}