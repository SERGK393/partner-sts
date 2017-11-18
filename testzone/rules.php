<?php
$IBLOCK_ID=10;
$arResult=array();
switch($action){
    case 'price':
        $sku=$json['product_sku'];
        $res_prod=CIBlockElement::getList(array(),array('IBLOCK_ID'=>$IBLOCK_ID,'XML_ID'=>$sku),false,false,array('ID'));
        if($res_prod){
            if($res_prod->getNext(false,false)){
                $db_res = CPrice::GetList(array(),
                    array(
                        "PRODUCT_ID" => $res_prod['ID'],
                        "CATALOG_GROUP_ID" => 1
                    )
                );
                if ($ar_res = $db_res->Fetch()) {
                    $arResult['PRICE']=$ar_res["PRICE"];
                }
            }
        }
        break;
    case 'info':
        $sku=$json['product_sku'];

        $result=CIBlockElement::GetList(array(),array('IBLOCK_ID'=>$IBLOCK_ID,'XML_ID'=>$sku),false,false,array('ID','NAME','IBLOCK_SECTION_ID'));
        if($get=$result->getNext(false,false)){
            //$arResult['NAME']=$get['NAME'];

            $prop_res=CIBlockElement::GetProperty($IBLOCK_ID,$get['ID'],array(),array('CODE'=>'vendor'));
            if($prop_res){
                if($prop=$prop_res->getNext(false,false)){
                    $arResult['brand']=$prop['VALUE'];
                }
            }

            $section_id=$get['IBLOCK_SECTION_ID'];
            //$arResult['SID']=$section_id;
            while($section_id){
                $res = CIBlockSection::GetByID($section_id);
                if($ar_res = $res->GetNext(false,false)){
                    $arResult['SECTIONS'][]=$ar_res['NAME'];
                    $section_id=$ar_res['IBLOCK_SECTION_ID'];
                }else $section_id=0;
            }
            $sections=$arResult['SECTIONS'];
            $arResult['cat']=implode('/',array_reverse($arResult['SECTIONS']));
            unset($arResult['SECTIONS']);
            //должно быть обязательно только два параметра - brand и cat.
        }
        break;
    case 'categories':
        $list_db = CIBlockSection::GetList(array('LEFT_MARGIN' => 'ASC'), Array("IBLOCK_ID"=>$IBLOCK_ID), false, Array("ID","NAME","DEPTH_LEVEL"));
        while($get=$list_db->fetch()){
            $path[$get['DEPTH_LEVEL']]=$get['NAME'];
            $name=array();
            for($i=1;$i<=$get['DEPTH_LEVEL'];$i++){
                $name[]=$path[$i];
            }
            $name=implode('/',$name);
            $arResult[$get['ID']]=$name;
        }
        break;
    case 'vendors':
        $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById(2)->fetch();
        $hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
        $hlDataClass = $hldata['NAME'].'Table';

        $result = $hlDataClass::getList(array(
            'order' => array('UF_NAME' =>'ASC'),
            'select' => array('UF_XML_ID','UF_NAME')
        ));

        while($res = $result->fetch()){
            $arResult[$res['UF_XML_ID']]=$res['UF_NAME'];
        }
        break;
    case 'vendor_icon':
        $arResult = 'no vendor';
        if(isset($json['vendor'])) {
            //$arResult = $json['vendor'];
            //break;
            $vendor = $json['vendor'];
            $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById(2)->fetch();
            $hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
            $hlDataClass = $hldata['NAME'] . 'Table';

            $result = $hlDataClass::getList(array(
                'order' => array('UF_NAME' => 'ASC'),
                'filter' => array('UF_XML_ID' => $vendor),
                'select' => array('UF_XML_ID', 'UF_FILE')
            ));

            if ($res = $result->fetch()) {
                $img=CFile::GetFileArray($res['UF_FILE']);
                if($img)$img=$img['SRC'];
                else $img='';
                $arResult = $img;
            }
        }
        break;
    case 'images':
        if($_SERVER['REQUEST_METHOD']=='POST'&&isset($_REQUEST['json'])) {
            $json = json_decode($_REQUEST['json'], true);
            $arResult=array('ITEMS'=>$json['ITEMS']);// security
            $el = new CIBlockElement;
            foreach($arResult['ITEMS'] as &$prod){
                $res_prod=CIBlockElement::getList(array(),array('IBLOCK_ID'=>$IBLOCK_ID,'XML_ID'=>$prod['XML_ID']),false,false,array('ID','DETAIL_PICTURE'));
                if($res_prod){
                    if($get=$res_prod->getNext(false,false)){
                        $img=CFile::GetFileArray($get['DETAIL_PICTURE']);
                        $prod['DETAIL_PICTURE']=$img;
                    }
                }
            }
            //$arResult=$json;
        }
        break;
    case 'product_not_enabled':
        $arResult=array();
        if(isset($json['products'])){
            foreach($json['products'] as $sku){
                $result=CIBlockElement::GetList(array(),array('IBLOCK_ID'=>$IBLOCK_ID,'XML_ID'=>$sku),false,false,array('ID'));
                if(!$result->getNext(false,false)){
                    $arResult['PRODUCTS'][]=$sku;
                }
            }
        }
        break;
}
echo json_encode($arResult);
