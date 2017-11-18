<?php
if (CModule::IncludeModule("sale")){
    $arResult=array();
    $arResult['ORDERS']=array();
    $db_sales = CSaleOrder::GetList(false,array('!STATUS_ID'=>'C','ALLOW_DELIVERY'=>'Y','>=DATE_INSERT'=>'15.08.2015 00:00:00'),false,false,array('ACCOUNT_NUMBER','DATE_INSERT'));
    while ($ar_sale = $db_sales->GetNext(true,false)){
        $arOrder=array();
        $arOrder['ACCOUNT_NUMBER']=$ar_sale['ACCOUNT_NUMBER'];
        $arOrder['DATE_INSERT']=$ar_sale['DATE_INSERT'];
        $arResult['ORDERS'][]=$arOrder;
    }
    echo json_encode($arResult);
}
