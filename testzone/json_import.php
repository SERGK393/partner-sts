<?php
if($_SERVER['REQUEST_METHOD']=='POST') {
    include_once "/var/www/west/data/INOUT/sts-platform-api/classes/api.php";
    $products = json_decode($_REQUEST['json'], true);
    $IBLOCK_ID=10;
    $oElement = new CIBlockElement();
    $arProducts = array();
    foreach($products as $product){
        $product_name=trim($product['NAME']);
        $xml_id=trim($product['XML_ID']);
        $quantity=trim($product['QUANTITY']);
        $product_sku=trim($product['XML_ID']);//to property list
        $var_code=trim($product['CODE']);
        $price=trim($product['PRICE']);
        $desc=trim($product['DETAIL_TEXT']);
        $currency='RUB';
        $desc_type='html';
        $full_image=CFile::MakeFileArray(trim($product['DETAIL_PICTURE']));
        $combo=trim(trim($product['COMBO_ADD']),',');
        $ym_code_id=trim($product['YM_CODE']);

        /*if(!empty($product['ADD_PRICE'])){
            $price+=intval($product['ADD_PRICE']);
        }*/

        $prop_vals=array();
        //echo "<b>$product_name</b>\n";
        foreach($product['PROPERTY_VALUES'] as $code=>$var_val){
            foreach($value=explode('@',$var_val) as $key=>$val){
                $value[$key]=trim($val);
                if($code=='dop_pictures')$value[$key]=CFile::MakeFileArray($value[$key]);
                if(!is_numeric($value[$key])&&!empty($value[$key])){
                    //echo "$value edited\n";
                    $prop_result=CIBlockProperty::GetList(array(),array('CODE'=>$code));
                    if($prop_result){
                        if($prop_get=$prop_result->fetch()){
                            if($prop_get['PROPERTY_TYPE']=='L'){
                                $prop_val_result=CIBlockPropertyEnum::GetList(array(),array('VALUE'=>$value[$key],'PROPERTY_ID'=>$code));
                                if($prop_val_get=$prop_val_result->fetch()){
                                    $value[$key]=$prop_val_get['ID'];
                                }else{
                                    $ibpenum = new CIBlockPropertyEnum;
                                    $prop_val_add=$ibpenum->Add(array('PROPERTY_ID'=>$prop_get['ID'], 'VALUE'=>$value[$key]));
                                    //echo "edited to $prop_val_add {$prop_get['ID']}\n";
                                    if($prop_val_add or die('error on add enum')){
                                        $value[$key]=$prop_val_add;
                                    }
                                }
                            }else{
                                //echo "Not List\n";
                            }
                        }
                    }
                }
            }
            if(count($value)){
                $prop_vals[$code]=$value;
            }else{
                $prop_vals[$code]=$value[0];
            }

            //echo "$name: $value\n";
        }
        $prop_vals['product_sku']=$product_sku;
        $prop_vals['ym_code']=$ym_code_id;
        if(!empty($product['ADD_SKU'])){
            $prop_vals['sku_second']=$product['ADD_SKU'];
        }
        if(!empty($product['ADD_PRICE'])){
            $prop_vals['price_plus']=$product['ADD_PRICE'];
        }

        $arFields = array(
            "ACTIVE" => "Y",
            "IBLOCK_ID" => $IBLOCK_ID,
            "IBLOCK_SECTION_ID" => $product['STS_SECTION_ID'],
            "NAME" => $product_name,
            "CODE" => $var_code,
            "DETAIL_TEXT" => $desc,
            "XML_ID" => $xml_id,
            "DETAIL_TEXT_TYPE" => $desc_type,
            "DETAIL_PICTURE" => $full_image,
            "PROPERTY_VALUES" => $prop_vals
        );
        $idElement = $oElement->Add($arFields, false, true, true);
        if(!$idElement) {
            echo 'product not added '.$oElement->LAST_ERROR;
            continue;
        }

        CCatalogProduct::Add(array('ID'=>$idElement,"QUANTITY" => 1)) or die('catalog product not added '.$oElement->LAST_ERROR);

        $arFields = Array(
            "PRODUCT_ID" => $idElement,
            "CATALOG_GROUP_ID" => 1,
            "PRICE" => $price,
            "CURRENCY" => $currency
        );
        CPrice::Add($arFields,true) or die('price not added '.$APPLICATION->GetException());

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
            CCatalogProductSet::add($arSaveSet) or die('combo not added');
        }

        $arProducts[] = $xml_id;

        $res = CIBlockElement::GetByID($idElement);
        if($ar_res = $res->GetNext())
            echo "<a href='http://santehsmart.ru{$ar_res['DETAIL_PAGE_URL']}' target='_blank'>$product_name</a>\n";
        else echo "<b>$product_name</b>\n";
    }

    if(!empty($arProducts)){
        $pl = new Platform();
        echo $pl->setGreen($arProducts);
    }
}

/*
echo 'import:';
$file=$_FILES['file']['tmp_name'];
echo "$file\n";
echo "Opening file...\n";
$IBLOCK_ID=10;
if ($handle = fopen($file, 'r')or die('err6')) {
    if ($data = fgetcsv($handle, 0, "^")) {//1st read for head
        if($data[0]=='xml_id' or die('head not found')){
            $head=$data;
        }
    }
    if ($data = fgetcsv($handle, 0, "^")) {//2nd read for section
        if((!empty($data[6])) or die('section not found')){
            $section_name=$data[6];
        }
    }

    if((isset($head)&&isset($section_name)) or die('head and section not found')){
        echo "<b>$section_name</b>\n\n";
        if ((CModule::IncludeModule("iblock")&&CModule::IncludeModule("catalog")&&CModule::IncludeModule("highloadblock"))or die ('error on include modules')) {
            require_once 'sections.php';
        }
        //echo "\n*****$prop_out";
        $sections=$arResult['SECTIONS'];
        foreach($sections as $sec_params){
            $sec_props = $sec_params['PROPERTIES'];
            if(!empty($sec_props)) {
                $section=array();
                $section['NAME']=$sec_params['NAME'];
                $section['ID']=$sec_params['ID'];
                foreach ($sec_props as $prop_params) {
                    $prop_code = $prop_params['CODE'];
                    $prop_name = $prop_params['NAME'];
                    $section['PROPS'][$prop_code] = $prop_name;
                }
            }
        }
        //print_r($section);
        if(isset($section) or die('section not found!!!')){
            //print_r($prop_values);
        }
    }

    rewind($handle);
    $oElement = new CIBlockElement();

    if($data = fgetcsv($handle, 0, "^")){
        while ($data = fgetcsv($handle, 0, "^")) {
            $product_name=trim($data[array_search('name', $head)]);
            $xml_id=trim($data[array_search('xml_id', $head)]);
            $product_sku=trim($data[array_search('product_sku', $head)]);//to property list
            $var_code=trim($data[array_search('code', $head)]);
            $price=trim($data[array_search('price', $head)]);
            $desc=trim($data[array_search('desc', $head)]);
            $currency=trim($data[array_search('currency', $head)]);
            $desc_type=trim($data[array_search('desc_type', $head)]);
            $full_image=CFile::MakeFileArray(trim($data[array_search('full_image', $head)]));
            $combo=trim($data[array_search('combo', $head)]);
            $ym_code_id=trim($data[array_search('ym_code', $head)]);


        }
    }else{
        echo 'xml_id not found';
        print_r($data);
    }
    fclose($handle);

}
*/
