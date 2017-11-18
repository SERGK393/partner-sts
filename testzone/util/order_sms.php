<?php
$IBLOCK_ID=10;
$arResult=array();

foreach($json as $order){
    $id=0;
    $db_sales = CSaleOrder::GetList(false,array('ACCOUNT_NUMBER'=>$order['SITE_NUMBER']),false,false,array('ID','DELIVERY_ID'));
    if ($ar_sale = $db_sales->GetNext(true,false)){
        $order_id=$ar_sale['ID'];
        
    	$prop_code='BILL_NUMBER';
    	$prop_value=$order['BILL_NUMBER'];
    	
	    $db_vals = CSaleOrderPropsValue::GetList(array(), array("ORDER_ID" => $order_id,"CODE"=>$prop_code));
        if ($arVals = $db_vals->Fetch()) {
            CSaleOrderPropsValue::Update($arVals['ID'], array("VALUE"=>$prop_value));
        }else{
            $prop = CSaleOrderProps::GetList(array(), array("CODE" => $prop_code));
            $prop = $prop->Fetch();
            $arFields = array(
                "ORDER_ID" => $order_id,
                "ORDER_PROPS_ID" => $prop["ID"],
                "NAME" => $prop["NAME"],
                "CODE" => $prop["CODE"],
                "VALUE" => $prop_value
            );
            CSaleOrderPropsValue::Add($arFields);
        }
        $arResult['DEL'][] = array(
            'STATUS' => 'NOT_SEND',
            'SMS_TEXT' => "via STS",
            'FILE_PATH' => $order['FILE_PATH'],
            'DEL_PATH' => $order['DEL_PATH'],
        );
    }else{
        $arResult['DEL'][] = array(
            'STATUS' => 'ORDER_NOT_FOUND',
            'SMS_TEXT' => "",
            'FILE_PATH' => $order['FILE_PATH'],
            'DEL_PATH' => $order['DEL_PATH'],
        );
    }
    
    
}

echo json_encode($arResult);
