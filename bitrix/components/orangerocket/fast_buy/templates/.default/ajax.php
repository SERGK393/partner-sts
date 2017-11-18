<?
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(!CModule::IncludeModule("sale")||!CModule::IncludeModule("catalog"))
{
	return;
}


function mail_utf8($to, $from, $subj, $message) 
{ 
$subject = '=?UTF-8?B?' . base64_encode($subj) . '?='; 
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=utf-8\r\n"; 
$headers .= "Content-Transfer-Encoding: 8bit \r\n"; 
$headers .= "From: $from\r\n"; 

return mail($to, $subject, $message, $headers); 
} 

if ($_SERVER["REQUEST_METHOD"]=="POST" && strlen($_POST["action"])>0 && check_bitrix_sessid())
{
	$APPLICATION->RestartBuffer();

	switch ($_POST["action"])
	{
		case "buy":
            $dat=array();
			foreach($data as $el){
				$dat[$el['name']]=$el['value'];
			}
			CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
            if(Add2BasketByProductID($dat['product_id'])){//ADDING TO BASKET
			
			    if(!empty($dat['prod_add_id'])){
			        foreach(json_decode($dat['prod_add_id'],true) as $product_id){
			            if($product_id!=$dat['product_id']){
			                Add2BasketByProductID($product_id);
			            }
			        }
			    }
			    
			    $product=empty($dat['prod_add_id'])?$dat['product_id']:$dat['prod_add_id'];
			    $STS_ADC = $APPLICATION->get_cookie("STS_ADC");

			    $comments="***Быстрый заказ***\nТелефон: {$dat['valn']}\nИмя покупателя: {$dat['valstr']}\nГород: {$dat['city']}\nТовар: $product {$dat['product_name']}";
			    $arFields = array(
			       "LID" => SITE_ID,
			       "PERSON_TYPE_ID" => 1,
			       "PAYED" => "N",
			       "CANCELED" => "N",
			       "STATUS_ID" => "N",
                    "DELIVERY_ID" => 2,
			       "PRICE" => $dat['product_price'],
			       "CURRENCY" => "RUB",
			       "USER_ID" => $dat['user'],
			       "COMMENTS" => $comments
			    );

			    // add Guest ID
			    if (CModule::IncludeModule("statistic"))
			       $arFields["STAT_GID"] = CStatistic::GetEventParam();

			    $ORDER_ID = CSaleOrder::Add($arFields);

			    if($ORDER_ID){
                    CSaleBasket::OrderBasket($ORDER_ID, CSaleBasket::GetBasketUserID());

                    $arFields = array(
                        "ORDER_ID" => $ORDER_ID,
                        "ORDER_PROPS_ID" => 1,
                        "NAME" => "Имя",
                        "CODE" => "FIO",
                        "VALUE" => $dat['valstr']
                    );
                    CSaleOrderPropsValue::Add($arFields);
                    $arFields = array(
                        "ORDER_ID" => $ORDER_ID,
                        "ORDER_PROPS_ID" => 2,
                        "NAME" => "Город",
                        "CODE" => "CITY",
                        "VALUE" => $dat['city']
                    );
                    CSaleOrderPropsValue::Add($arFields);
                    $arFields = array(
                        "ORDER_ID" => $ORDER_ID,
                        "ORDER_PROPS_ID" => 3,
                        "NAME" => "Телефон",
                        "CODE" => "PHONE",
                        "VALUE" => $dat['valn']
                    );
                    CSaleOrderPropsValue::Add($arFields);
                    if($STS_ADC){
                        $arFields = array(
                            "ORDER_ID" => $ORDER_ID,
                            "ORDER_PROPS_ID" => 69,
                            "NAME" => "Реклама Яндекс",
                            "CODE" => "STS_ADC",
                            "VALUE" => $STS_ADC
                        );
                        CSaleOrderPropsValue::Add($arFields);
                    }

				    echo "<div style='padding:60px 25px 70px;background-color:rgba(0,255,0,0.3);overflow:hidden'>Благодарим за заказ! Мы свяжемся с Вами и уточним детали.</div>";
				    
                    $to='fast_buy@santehsmart.ru';
                    $from='sale@santehsmart.ru';
                    $subj="Новый заказ";
                    
                    $message=$comments;
                    
                    mail_utf8($to, $from, $subj, $message);
			    }else{
				    echo "<div style='padding:60px 25px 70px;background-color:rgba(255,0,0,0.3);overflow:hidden'>Увы, произошла ошибка, попробуйте позднее.</div>";
			    }
			    break;
			}else{
			    echo "<div style='padding:60px 25px 70px;background-color:silver;overflow:hidden'>Товара нет в наличии. Оставьте запрос менеджеру.</div>";
			}
	}

	die();
}
?>
