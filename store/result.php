<?php
$stringOut='';
$status=($_REQUEST['Result']==0)?'Y':'N';
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (CModule::IncludeModule("sale"))
{
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
	    if($_REQUEST['Order']){
		    $db_sales = CSaleOrder::GetList(false,array('ACCOUNT_NUMBER'=>$_REQUEST['Order']),false,false,array('ID','ACCOUNT_NUMBER',"USER_ID"));
            if ($ar_sale = $db_sales->GetNext(true,false)){
                $order_id=$ar_sale['ID'];
            }

            if(isset($order_id)){
                $props=array('Result','TrType','TextMessage','Function','IntRef','RRN','Amount','Currency','RESPONSE_DATE');

                foreach($props as $prop_code) {
                    $prop_value=($prop_code!='RESPONSE_DATE')?($_REQUEST[$prop_code]):Date(CDatabase::DateFormatToPHP(CLang::GetDateFormat("FULL", LANG)));

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
                }
		    }else{
		        $stringOut.="Заказ не найден\n";
		    }

		}else{
		    $stringOut.="Заказ не принят\n";
		}
	}
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");


$stringOut.=print_r($_REQUEST,true);

if ($handle = fopen($_SERVER["DOCUMENT_ROOT"].'/out_debug.txt', 'w')or die()) {
    fwrite($handle,$stringOut);
    fclose($handle);
}
