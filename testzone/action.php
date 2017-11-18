<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if ((CModule::IncludeModule("iblock")&&CModule::IncludeModule("catalog")&&CModule::IncludeModule("highloadblock"))or die ('error on include modules')){
    
    if(isset($_REQUEST['deletebysku'])){
        echo 'deletebysku ';
        $result=CIBlockElement::GetList(array(),array('IBLOCK_ID'=>10,'XML_ID'=>$_REQUEST['deletebysku']),false,false,array('ID'));
        if($get=$result->getNext()){
            echo $get['ID'].' is '.CIBlockElement::Delete($get['ID']).' ';
        }
        return;
    }

    if(isset($_REQUEST['brands'])){
        $arVendors=array();
        $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById(2)->fetch();
        $hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
        $hlDataClass = $hldata['NAME'].'Table';

        $result = $hlDataClass::getList(array(
            'order' => array('UF_NAME' =>'ASC'),
            'select' => array('UF_XML_ID','UF_NAME','UF_FILE')
        ));

        while($res = $result->fetch()){
            $img=CFile::GetFileArray($res['UF_FILE']);
            if($img)$img='http://santehsmart.ru'.$img['SRC'];
            else $img='';
            $arVendors[]=array(
                "XML_ID"=>$res['UF_XML_ID'],
                "NAME"=>$res['UF_NAME'],
                "FILE"=>$img
            );
        }

        file_put_contents('brands.json',json_encode($arVendors));
        return;
    }
    
    if(isset($_REQUEST['groupstest'])){
        $product_id=137012;
        $set=true;
        $iblock=new CIBlockElement();
        $bs = new CIBlockSection();
        echo '<pre>';
        $sale_group_id=372;
        $arGroups=array();
        $sale_found=false;
        $db_old_groups = CIBlockElement::GetElementGroups($product_id, true);
        while($ar_group = $db_old_groups->Fetch()){
            $arGroups[]=$ar_group;
            if($ar_group['IBLOCK_SECTION_ID']==$sale_group_id)$sale_found=true;
            //else $arGroups[]=$ar_group;
        }
        print_r($arGroups);
        if($set){
            if(!$sale_found){
                $ar_new_groups=array();
                foreach($arGroups as $group){
                    $ar_new_groups[]=$group['ID'];
                }
                
                $gr=$arGroups[0];
                for($gr_depth=$gr['DEPTH_LEVEL'];$gr_depth>1;$gr_depth--){
                    $gr_section=$gr['IBLOCK_SECTION_ID'];
                    $db_res = CIBlockSection::GetList(array(), array('IBLOCK_ID'=>10,'ID'=>$gr_section),false,array('CODE','NAME','IBLOCK_SECTION_ID','UF_LOGISTYC_INDEX'));
                    if($ar_res = $db_res->GetNext())
                        $gr=$ar_res;
                }
                print_r($gr);
                  
                print_r($ar_new_groups);          
                $db_res = CIBlockSection::GetList(array(), array('IBLOCK_ID'=>10,'CODE'=>'sale-'.$gr['CODE']));
                if($ar_res = $db_res->GetNext()){
                    $ar_new_groups[]=$ar_res['ID'];
                }else{
                    $arFields = Array(
                      "ACTIVE" => 'Y',
                      "IBLOCK_SECTION_ID" => $sale_group_id,
                      "IBLOCK_ID" => 10,
                      "NAME" => $gr['NAME'],
                      "CODE" => 'sale-'.$gr['CODE'],
                      "UF_LOGISTYC_INDEX" => empty($gr['UF_LOGISTYC_INDEX'])?1:$gr['UF_LOGISTYC_INDEX'],
                    );
                    print_r($arFields);        
                    $new_group_id = $bs->Add($arFields);
                    echo $bs->LAST_ERROR;
                    if(!empty($new_group_id)) $ar_new_groups[]=$new_group_id;
                }
                
                print_r($ar_new_groups);
                
                echo '** ',$iblock->SetElementSection($product_id, $ar_new_groups);
            }
        }else{
            if($sale_found){
                $ar_new_groups=array();
                
                foreach($arGroups as $group){
                    if(intval($group['IBLOCK_SECTION_ID'])!=$sale_group_id) $ar_new_groups[]=$group['ID'];
                }
                
                print_r($ar_new_groups); 
                echo '## ',$iblock->SetElementSection($product_id, $ar_new_groups);
            }
        }
        return;
    }
    
    if(isset($_REQUEST['test1'])){
        echo 'test1 ';
        $result=CIBlockElement::GetList(array(),array('IBLOCK_ID'=>10,'XML_ID'=>61459),false,false,array('ID'));
        if($get=$result->getNext()){
            $db_props = CIBlockElement::GetProperty(10, $get['ID'], array("sort" => "asc"), Array("CODE"=>"OLD_PRICE"));
            if($ar_props = $db_props->Fetch())
                $old_price = IntVal($ar_props["VALUE"]);
            else $old_price = 0;
            echo $get['ID'].' is '.$old_price.' ';
        }
        return;
    }
    
    if(isset($_REQUEST['buyzero'])){
        $result=CIBlockElement::GetList(array(),array('IBLOCK_ID'=>10,'SECTION_CODE'=>'dopolnitelnoe-oborudovanie'),false,false,array('ID'));
        while($get=$result->getNext()){
            echo $get['ID'].' is '.CCatalogProduct::Update($get['ID'],array('CAN_BUY_ZERO'=>'N','QUANTITY_TRACE'=>'N')).' ';
        }
        return;
    }
    
    if(isset($_REQUEST['getnamebysku'])&&!empty($_REQUEST['getnamebysku'])){
        $arResult=array(
            'NAME'=>'',
            'SECTIONS'=>array()
        );
        $result=CIBlockElement::GetList(array(),array('IBLOCK_ID'=>10,'property_product_sku'=>$_REQUEST['getnamebysku']),false,false,array('ID','NAME','IBLOCK_SECTION_ID'));
        while($get=$result->getNext()){
            $arResult['NAME']=$get['NAME'];
            $section_id=$get['IBLOCK_SECTION_ID'];
            while($section_id){
                $res = CIBlockSection::GetByID($section_id);
                if($ar_res = $res->GetNext()){
                    array_unshift($arResult['SECTIONS'], $ar_res['NAME']);
                    $section_id=$ar_res['IBLOCK_SECTION_ID'];
                }else $section_id=0;
            }
        }
        echo json_encode($arResult,JSON_UNESCAPED_UNICODE);
        return;
    }
    
    if(isset($_REQUEST['delsets'])&&!empty($_REQUEST['delsets'])){
        echo CCatalogProductSet::deleteAllSetsByProduct($_REQUEST['delsets'],CCatalogProductSet::TYPE_GROUP);
        return;
    }

    if(isset($_REQUEST['viewprops'])&&!empty($_REQUEST['viewprops'])){
        echo '<pre>';
        $result=CIBlockElement::GetProperty(10,$_REQUEST['viewprops'],array(),array());
        while($prop=$result->GetNext(true,false)){
            print_r($prop);
        }
        return;
    }
    
    if(isset($_REQUEST['cat'])&&!empty($_REQUEST['cat'])
    &&isset($_REQUEST['prop'])&&!empty($_REQUEST['prop'])
    &&isset($_REQUEST['val'])&&!empty($_REQUEST['val'])
    &&isset($_REQUEST['prod'])&&!empty($_REQUEST['prod'])){
        $category=$_REQUEST['cat'];
        $prop_name=$_REQUEST['prop'];
        $prop_value=$_REQUEST['val'];
        $combo=$_REQUEST['prod'];
        
        if(isset($_REQUEST['prod_prop'])&&!empty($_REQUEST['prod_prop'])){
            $combo_prop=$_REQUEST['prod_prop'];
            $arFilter=array(
                'IBLOCK_ID'=>10,
                $combo_prop=>$combo
            );
            
            $i=1;
            while(isset($_REQUEST['prod_prop'.$i])&&!empty($_REQUEST['prod_prop'.$i])&&isset($_REQUEST['prod'.$i])&&!empty($_REQUEST['prod'.$i])){
                $prop_name=$_REQUEST['prod_prop'.$i];
                $prop_value=$_REQUEST['prod'.$i];
                
                $arFilter[$prop_name]=$prop_value;
                $i++;
            }
            
            $combo=array();
            $result=CIBlockElement::GetList(array(),$arFilter,false,false,array('ID','NAME','XML_ID'));
            while($get=$result->getNext()){
                $combo[]=$get['ID'];
                echo $get['ID']." ".$get['XML_ID'].' '.$get['NAME']."\n";
            }
            $combo=implode(',',$combo);
            echo "\n";
        }
        
        $arFilter=array(
            'IBLOCK_ID'=>10,
            'SECTION_CODE'=>$category,
            $prop_name=>$prop_value
        );
        $i=1;
        while(isset($_REQUEST['prop'.$i])&&!empty($_REQUEST['prop'.$i])&&isset($_REQUEST['val'.$i])&&!empty($_REQUEST['val'.$i])){
            $prop_name=$_REQUEST['prop'.$i];
            $prop_value=$_REQUEST['val'.$i];
            
            $arFilter[$prop_name]=$prop_value;
            $i++;
        }
        $result=CIBlockElement::GetList(array(),$arFilter,false,false,array('ID','NAME','XML_ID'));
        while($get=$result->getNext()){
            $idElement=$get['ID'];
            if(isset($_REQUEST['clear'])&&!empty($_REQUEST['clear'])&&$_REQUEST['clear']=='on'){
                $status_clear=CCatalogProductSet::deleteAllSetsByProduct($idElement,CCatalogProductSet::TYPE_GROUP);
                echo "$idElement ".$get['XML_ID'].' '.$get['NAME']." ($status_clear)\n";
            }elseif(isset($_REQUEST['delete'])&&!empty($_REQUEST['delete'])&&$_REQUEST['delete']=='on'){
                $items=explode(',',$combo);
                $db_set=CCatalogProductSet::getAllSetsByProduct($idElement, '2');
                //print_r($db_set);
                foreach($db_set as $ar_sets){
                    foreach($ar_sets['ITEMS'] as $ar_set){
                        if(in_array($ar_set['ITEM_ID'],$items)) {
                            if (!isset($_REQUEST['check']) || empty($_REQUEST['check']) || $_REQUEST['check'] != 'on') {
                                $status_clear = CCatalogProductSet::delete($ar_sets['SET_ID']);
                            }
                            echo "$idElement " . $get['XML_ID'] . ' ' . $get['NAME'] . " ($status_clear)\n";
                        }
                    }
                }
                CCatalogProductSet::recalculateSetsByProduct($idElement);
            }else{
                if(!empty($combo)){
                    $arSaveSet = array(
                        'TYPE' => '2',
                        'ITEM_ID' => $idElement,
                        'ACTIVE' => 'Y',
                        'ITEMS' => array()
                    );
                    foreach(explode(',',$combo) as $c_item_id){
                        $arSaveItem = array(
                            'ITEM_ID' => $c_item_id,
                            'QUANTITY' => 1,
                            'DISCOUNT_PERCENT' => false,
                            'SORT' => 90
                        );
                        $arSaveSet['ITEMS'][] = $arSaveItem;
                    }
                    echo "$idElement ".$get['XML_ID'].' '.$get['NAME'];
                    if(!isset($_REQUEST['check'])||empty($_REQUEST['check'])||$_REQUEST['check']!='on'){
                        CCatalogProductSet::add($arSaveSet) or die('combo not added');
                        echo ' added';
                    }
                    echo "\n";
                }
            }
        } 
        return;
    }
}
