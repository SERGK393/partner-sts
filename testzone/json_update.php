<?php
if($_SERVER['REQUEST_METHOD']=='POST'){
    $send=json_decode($_REQUEST['json'],true);
    if($send['action']=='send'){
        $category_id=0;
        $arResult=array();
        foreach($send['product'] as $prod){
            $category_id=$prod['IBLOCK_SECTION_ID'];
            break;
        }
        if($category_id){
            $el = new CIBlockElement;
            $ar_props=CIBlockSectionPropertyLink::GetArray(10, $category_id);
            $arProperties=array();
            foreach($ar_props as $prop){
                $prop_id=$prop['PROPERTY_ID'];
                $prop=CIBlockProperty::GetByID($prop_id)->getNext();
                $prop_code=$prop['CODE'];
                $prop_name=$prop['NAME'];
                $arProperties[$prop_code]=array(
                    'ID'=>$prop_id,
                    'CODE'=>$prop_code,
                    'NAME'=>$prop_name
                );
            }
            unset($prop_id,$prop,$prop_code,$prop_name);

            //параметры и свойства уже заготовлены, осталось исправить списки, картинки, итд.
            foreach($send['product'] as $id=>$prod){
                //adding unused properties
                $db_props=CIBlockElement::GetProperty(10,$id,array(),array('EMPTY'=>'NO','ACTIVE'=>'Y'));
                while($prop=$db_props->getNext(true,false)){
                    if(!empty($prop['VALUE'])){
                        if(!isset($arProperties[$prop['CODE']])&&!isset($prod['PROPERTY_VALUES'][$prop['CODE']])){
                            $send['product'][$id]['PROPERTY_VALUES'][$prop['CODE']]=$prop['VALUE'];
                        }
                    }
                }
                //adding new list elements (and delete images)
                $db_props=CIBlockElement::GetProperty(10,$id,array(),array('ACTIVE'=>'Y'));
                while($prop=$db_props->getNext(true,false)){
                    if($prop['CODE']=='dop_pictures'){
                        CIBlockElement::SetPropertyValuesEx($id, 10, array($prop['ID'] => Array ("VALUE" => array("del" => "Y"))));
                    }
                    if($prop['PROPERTY_TYPE']=='L'){//ADDING NEW VALUES TO LIST
                        if(isset($prod['PROPERTY_VALUES'][$prop['CODE']])){
                            if(!is_array($prod['PROPERTY_VALUES'][$prop['CODE']])&&!is_numeric($prod['PROPERTY_VALUES'][$prop['CODE']])){
                                $prop_val_result=CIBlockPropertyEnum::GetList(array(),array('VALUE'=>$prod['PROPERTY_VALUES'][$prop['CODE']],'PROPERTY_ID'=>$prop['CODE']));
                                if($prop_val_get=$prop_val_result->fetch()){
                                    $send['product'][$id]['PROPERTY_VALUES'][$prop['CODE']]=$prop_val_get['ID'];
                                }else{
                                    $ibpenum = new CIBlockPropertyEnum;
                                    $prop_val_add=$ibpenum->Add(array('PROPERTY_ID'=>$prop['ID'], 'VALUE'=>$prod['PROPERTY_VALUES'][$prop['CODE']]));
                                    if($prop_val_add){
                                        $send['product'][$id]['PROPERTY_VALUES'][$prop['CODE']]=$prop_val_add;
                                    }
                                }
                            }
                            if(is_array($prod['PROPERTY_VALUES'][$prop['CODE']])){
                                foreach($prod['PROPERTY_VALUES'][$prop['CODE']] as $i=>$p){
                                    if(!is_numeric($p)){
                                        $prop_val_result=CIBlockPropertyEnum::GetList(array(),array('VALUE'=>$p,'PROPERTY_ID'=>$prop['CODE']));
                                        if($prop_val_get=$prop_val_result->fetch()){
                                            $send['product'][$id]['PROPERTY_VALUES'][$prop['CODE']][$i]=$prop_val_get['ID'];
                                        }else{
                                            $ibpenum = new CIBlockPropertyEnum;
                                            $prop_val_add=$ibpenum->Add(array('PROPERTY_ID'=>$prop['ID'], 'VALUE'=>$p));
                                            if($prop_val_add){
                                                $send['product'][$id]['PROPERTY_VALUES'][$prop['CODE']][$i]=$prop_val_add;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                //adding new images to server
                if(isset($prod['PROPERTY_VALUES']['dop_pictures'])){
                    if(is_array($prod['PROPERTY_VALUES']['dop_pictures'])){
                        foreach($prod['PROPERTY_VALUES']['dop_pictures'] as $i=>$pic){
                            if(filter_var($pic, FILTER_VALIDATE_URL)){
                                $send['product'][$id]['PROPERTY_VALUES']['dop_pictures'][$i]=CFile::MakeFileArray($pic);
                            }
                        }
                    }else{
                        if(filter_var($prod['PROPERTY_VALUES']['dop_pictures'], FILTER_VALIDATE_URL)){
                            $send['product'][$id]['PROPERTY_VALUES']['dop_pictures']=CFile::MakeFileArray($prod['PROPERTY_VALUES']['dop_pictures']);
                        }
                    }
                }
                if(!empty($prod['DETAIL_PICTURE'])){
                    //if(filter_var($prod['DETAIL_PICTURE'], FILTER_VALIDATE_URL)){
                        $send['product'][$id]['DETAIL_PICTURE']=CFile::MakeFileArray($prod['DETAIL_PICTURE']);
                    //}
                }
                //updating product**********************
                $edit_result=$el->Update($id, $send['product'][$id], false, false, true);
                $res = CIBlockElement::GetByID($id);
                if($ar_res = $res->GetNext()){
                    $img=CFile::GetFileArray($ar_res['PREVIEW_PICTURE']);
                    if($img)$img=$img['SRC'];
                    else $img='';
                    $arItem=array(
                        'XML_ID'=>$ar_res['XML_ID'],
                        'NAME'=>$ar_res['NAME'],
                        'PREVIEW_PICTURE'=>$img,
                        'DETAIL_TEXT'=>$ar_res['DETAIL_TEXT'],
                        'DETAIL_PAGE_URL'=>$ar_res['DETAIL_PAGE_URL']
                    );
                    if(!$edit_result)$arItem['ERROR']='ERROR '.$el->LAST_ERROR;
                    $arResult[]=$arItem;
                }
            }
        }
        
        //print_r($send);
        //print_r($arResult);
        echo json_encode($arResult);
    }
}
