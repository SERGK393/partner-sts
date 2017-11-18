<?php
$arResult=array();
$IBLOCK_ID=10;
switch($action){
    case 'search':
        //$products=$json['products'];
        $user_name=$json['user_name'];
        $address=$json['user_address'];

        $arFilter=array();
        if(strstr($address,'@')!=false)$arFilter["USER_EMAIL"]=trim($address);
        elseif(strstr($user_name,' ')==false)$arFilter[]=array(
            "LOGIC" => "OR",
            "USER_NAME" => trim($user_name),
            "USER_LAST_NAME" => trim($user_name),
        );
        else {
            $names=explode(' ',$user_name);
            $arFilter[]=array(
                "LOGIC" => "OR",
                array("USER_NAME" => $names[0], "USER_LAST_NAME" => $names[1]),
                array("USER_NAME" => $names[1], "USER_LAST_NAME" => $names[0]),
            );
        }

        $db_sales = CSaleOrder::GetList(false,$arFilter, false, false, array("ID","ACCOUNT_NUMBER"));
        while ($ar_sale = $db_sales->GetNext(true,false)){
            if(isset($json['user_phone'])){
                $db_vals = CSaleOrderPropsValue::GetList(array("SORT" => "ASC"),array("ORDER_ID" => $ar_sale["ID"],"CODE"=>'PHONE'));
                $arPropOrder = $db_vals->Fetch();
                $arResult[]=$arPropOrder['VALUE'];
                if($json['user_phone']==$arPropOrder['VALUE']){
                    $json['order_id']=$ar_sale["ACCOUNT_NUMBER"];
                }
            }else $json['order_id']=$ar_sale["ACCOUNT_NUMBER"];
        }
    case 'order':
        if(isset($json['order_id'])&&!empty($json['order_id'])) {
            $order_id = $json['order_id'];
            $db_sales = CSaleOrder::GetList(false, array('ACCOUNT_NUMBER' => $order_id), false, false, array(
                "ID", "ACCOUNT_NUMBER", "DELIVERY_ID", "DATE_INSERT", "DATE_UPDATE", "DATE_STATUS", "STATUS_ID", "PRICE",
                "USER_LOGIN", "USER_NAME", "USER_LAST_NAME", "USER_EMAIL",
            ));
            if ($ar_sale = $db_sales->GetNext(true, false)) {
                $arResult = $ar_sale;
                $ar_deliv = CSaleDeliveryHandler::GetBySID(preg_replace("/\:.+/", '', $ar_sale['DELIVERY_ID']))->GetNext(true, false);
                $arResult["DELIVERY_NAME"] = $ar_deliv["NAME"];
                $ar_status = CSaleStatus::GetByID($ar_sale["STATUS_ID"]);
                $arResult["STATUS_NAME"] = $ar_status["NAME"];
                $arResult["STATUS_DESCRIPTION"] = $ar_status["DESCRIPTION"];
                $db_vals = CSaleOrderPropsValue::GetList(array("SORT" => "ASC"),array("ORDER_ID" => $ar_sale["ID"],"CODE"=>'PHONE'));
                $arPropOrder = $db_vals->Fetch();
                $arResult["PHONE"] = $arPropOrder['VALUE'];
                $db_vals = CSaleOrderPropsValue::GetList(array("SORT" => "ASC"),array("ORDER_ID" => $ar_sale["ID"],"CODE"=>'LOCATION'));
                $arPropOrder = $db_vals->Fetch();
                $arLocation=CSaleLocation::GetByID($arPropOrder['VALUE']);
                $arResult["CITY"] = $arLocation['CITY_NAME'];
                
                $deliv_method=preg_replace("/.+?\:/",'',$ar_sale['DELIVERY_ID']);
                $arResult["DELIVERY_METHOD"] = $ar_deliv['PROFILES'][$deliv_method]['TITLE'];
                $db_vals = CSaleOrderPropsValue::GetList(array("SORT" => "ASC"),array("ORDER_ID" => $ar_sale["ID"],"CODE"=>($deliv_method=='curier')?'ADDRESS':'ADDRESS_DELIV'));//ADDRESS
                $arPropOrder = $db_vals->Fetch();
                $arResult["DELIVERY_ADDRESS"] = $arPropOrder['VALUE'];

                $db_basket = CSaleBasket::GetList(false, array('ORDER_ID' => $ar_sale["ID"]));
                while ($ar_basket = $db_basket->GetNext(true, false)) {
                    $arProduct = array();
                    $arProduct["ID"] = $ar_basket["PRODUCT_ID"];
                    $arProduct["XML_ID"] = $ar_basket["PRODUCT_XML_ID"];
                    $arProduct["NAME"] = $ar_basket["NAME"];
                    $arProduct["PRICE"] = $ar_basket["PRICE"];
                    $arProduct["QUANTITY"] = $ar_basket["QUANTITY"];
                    $db_product = CIBlockElement::GetList(false, array('ID' => $ar_basket["PRODUCT_ID"]), false, false, array("PREVIEW_PICTURE"));
                    if ($ar_product = $db_product->GetNext(true, false)) {
                        $img = CFile::GetFileArray($ar_product['PREVIEW_PICTURE']);
                        if ($img) $img = "http://santehsmart.ru" . $img['SRC'];
                        else $img = '';
                        $arProduct["PREVIEW_PICTURE"] = $img;
                    }
                    $arResult["PRODUCT"][] = $arProduct;
                }
            }
        }
        break;
    case 'list':
        if(isset($json['date_begin'])) {
            $arResult['ORDERS']=array();
            $arFilter=array(
                ">=DATE_INSERT" => $json['date_begin']
            );
            $arResult['dateFirst']=$json['date_begin'];
            if(isset($json['date_end'])){
                $arFilter["<=DATE_INSERT"]=$json['date_end']." 23:59:59";

                $arResult['dateLast']=$json['date_end'];
            }
            $db_sales = CSaleOrder::GetList(false, $arFilter, false, false, array(
                "ID", "ACCOUNT_NUMBER", "DELIVERY_ID", "DATE_INSERT", "DATE_UPDATE", "DATE_STATUS", "STATUS_ID", "PRICE",
                "USER_LOGIN", "USER_NAME", "USER_LAST_NAME", "USER_EMAIL",
            ));
            while ($ar_sale = $db_sales->GetNext(true, false)) {
                $arOrder = $ar_sale;
                $arOrder["SITE"]='www.santehsmart.ru';
                $ar_deliv = CSaleDeliveryHandler::GetBySID(preg_replace("/\:.+/", '', $ar_sale['DELIVERY_ID']))->GetNext(true, false);
                $arOrder["DELIVERY_NAME"] = $ar_deliv["NAME"];
                $ar_status = CSaleStatus::GetByID($ar_sale["STATUS_ID"]);
                $arOrder["STATUS_NAME"] = $ar_status["NAME"];
                $arOrder["STATUS_DESCRIPTION"] = $ar_status["DESCRIPTION"];
                $db_vals = CSaleOrderPropsValue::GetList(array("SORT" => "ASC"),array("ORDER_ID" => $ar_sale["ID"],"CODE"=>'PHONE'));
                $arPropOrder = $db_vals->Fetch();
                $arOrder["PHONE"] = $arPropOrder['VALUE'];
                $db_vals = CSaleOrderPropsValue::GetList(array("SORT" => "ASC"),array("ORDER_ID" => $ar_sale["ID"],"CODE"=>'LOCATION'));
                $arPropOrder = $db_vals->Fetch();
                $arLocation=CSaleLocation::GetByID($arPropOrder['VALUE']);
                $arOrder["CITY"] = $arLocation['CITY_NAME'];
                $arResult['ORDERS'][] = $arOrder;
            }
        }
        break;
    case 'status_set':
        if(isset($json['order_id'])){
            $order_id = $json['order_id'];
            $db_sales = CSaleOrder::GetList(false, array('ACCOUNT_NUMBER' => $order_id), false, false, array("ID"));
            if ($ar_sale = $db_sales->GetNext(true, false)) {
                if (CSaleOrder::StatusOrder($ar_sale["ID"], $json['status']))
                    $arResult['MESSAGE'] = "Статус установлен";
                else $arResult['MESSAGE'] = "Ошибка установки нового статуса заказа";
            }else $arResult['MESSAGE'] = "Заказ не найден";
        }
        break;
    case 'status_get':
        if(isset($json['order_id'])){
            $order_id = $json['order_id'];
            $db_sales = CSaleOrder::GetList(false, array('ACCOUNT_NUMBER' => $order_id), false, false, array("STATUS_ID"));
            if ($ar_sale = $db_sales->GetNext(true, false)) {
                $arResult['STATUS_ID'] = $ar_sale["STATUS_ID"];
                $arResult['MESSAGE'] = "Статус получен";
            }else $arResult['MESSAGE'] = "Заказ не найден";
        }
        break;
    case 'status_list_get':
        $arResult['OPEN'] = array();
        $db_status = CSaleStatus::GetList(array(),array('LID'=>'ru',"TYPE" => "O","!~DESCRIPTION" => "%[Завершен]"));
        while($ar_status = $db_status->GetNext(true, false)){
            $arResult['OPEN'][]=$ar_status;
        }
        $arResult['CLOSE'] = array();
        $db_status = CSaleStatus::GetList(array(),array('LID'=>'ru',"TYPE" => "O","~DESCRIPTION" => "%[Завершен]"));
        while($ar_status = $db_status->GetNext(true, false)){
            $arResult['CLOSE'][]=$ar_status;
        }
        break;
    case 'products_info':
        $arResult['PRODUCTS']=array();
        if(isset($json['products'])){
            $arFilter=array("IBLOCK_ID" => $IBLOCK_ID);
            $arFilterOr=array("LOGIC" => "OR");
            foreach($json['products'] as $sku){
                $arFilterOr[]=array('XML_ID'=>$sku);
                $arResult['PRODUCTS'][$sku]=array('XML_ID'=>$sku,'VENDOR'=>'','NAV'=>array(),'NAV_PATH'=>'');
            }
            $arFilter[]=$arFilterOr;
            
            $db_product = CIBlockElement::GetList(false, $arFilter, false, false, array("ID","XML_ID","IBLOCK_SECTION_ID"));
            while ($ar_product = $db_product->GetNext(true, false)) {
                $arProduct=array();

                $arProduct['XML_ID']=$ar_product['XML_ID'];
                $db_props = CIBlockElement::GetProperty($IBLOCK_ID, $ar_product['ID'], array(), Array("CODE"=>"vendor"));
                if($ar_props = $db_props->Fetch())
                    $arProduct['VENDOR'] = $ar_props["VALUE"];
                else $arProduct['VENDOR'] = '';
                $db_nav = CIBlockSection::GetNavChain(false, $ar_product['IBLOCK_SECTION_ID']); 
                while ($ar_nav = $db_nav->Fetch()) {
                	$arProduct['NAV'][]=$ar_nav['NAME'];
                }
                $arProduct['NAV_PATH']=implode('/', $arProduct['NAV']);

                $arResult['PRODUCTS'][$arProduct['XML_ID']]=$arProduct;
            }
        }
        break;
    case 'section_list':
    	$list_db = CIBlockSection::GetList(array('LEFT_MARGIN' => 'ASC'), Array("IBLOCK_ID"=>$IBLOCK_ID), false, Array("ID","CODE","NAME","DEPTH_LEVEL"));
        while($get=$list_db->fetch()){
        	$nav=array();
            $db_nav = CIBlockSection::GetNavChain(false, $get['ID']); 
            while ($ar_nav = $db_nav->Fetch()) {
            	$nav[]=$ar_nav['NAME'];
            }
            $arItem=array();
            $arItem['ID']=$get['ID'];
            $arItem['DEPTH_LEVEL']=$get['DEPTH_LEVEL'];
            $arItem['CODE']=$get['CODE'];
            $arItem['NAME']=$get['NAME'];
            $arItem['FULL_NAME']=implode('/', $nav);
            $arResult['SECTIONS'][]=$arItem;
        }
    	break;
}
echo json_encode($arResult);
