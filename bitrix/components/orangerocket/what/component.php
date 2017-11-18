<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */


if (CModule::IncludeModule("sale")){

if(isset($_REQUEST['order_id'])){

    $db_sales = CSaleOrder::GetList(false,array('ACCOUNT_NUMBER'=>$_REQUEST['order_id']),false,false,array('ID','ACCOUNT_NUMBER'));
    if ($ar_sale = $db_sales->GetNext(true,false))
    {
        $info['ORDER_NUMBER']=$ar_sale['ACCOUNT_NUMBER'];
        $info['ORDER_CODE']=$ar_sale['ID'];
    }

    $order=CSaleOrder::GetByID($info['ORDER_CODE']);

    if($order){
        $arResult['ORDER_FOUND']='Y';
        $arResult['ORDER_STATUS_ID']=$order['STATUS_ID'];
        $arResult['ORDER_STATUS_NAME']=CSaleStatus::GetByID($order['STATUS_ID'])['NAME'];
        $arResult['ORDER_STATUS_DESC']=CSaleStatus::GetByID($order['STATUS_ID'])['DESCRIPTION'];
        if($order['CANCELED']=='Y'){
            $arResult['ORDER_STATUS_ID']='STATUS_CANCELED';
        }elseif($order['PAYED']=='Y'){
            $arResult['ORDER_STATUS_ID']='STATUS_PAYED';
        }elseif($order['ALLOW_DELIVERY']=='Y'){
            $arResult['ORDER_STATUS_ID']='STATUS_DELIVERY';
        }

        $info['DATE_PAYED']=$order['DATE_PAYED'];
        $info['DATE_CANCELED']=$order['DATE_CANCELED'];
        $info['REASON_CANCELED']=$order['REASON_CANCELED'];
        $info['DATE_STATUS']=$order['DATE_STATUS'];
        $info['PRICE_DELIVERY']=$order['PRICE_DELIVERY'];
        $info['DATE_ALLOW_DELIVERY']=$order['DATE_ALLOW_DELIVERY'];
        $info['PRICE']=$order['PRICE'];
        $info['DATE_INSERT']=$order['DATE_INSERT'];
        $arResult['ORDER_INFO']=$info;
    }else{
        $arResult['ORDER_FOUND']='N';
    }

    $this->includeComponentTemplate('order');
}else{
    $this->includeComponentTemplate('input');
}

}



?>