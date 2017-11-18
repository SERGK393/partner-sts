<?
	//AddEventHandler("sale",'OnBeforeOrderAdd','gogo');
	AddEventHandler("sale",'OnOrderNewSendEmail','customOnOrderSend1C');
    AddEventHandler("catalog",'OnGetOptimalPrice','customOnGetOptimalPrice');
    AddEventHandler("sale", "OnSaleComponentOrderOneStepPersonType", "selectSavedPersonType");
    AddEventHandler('sale', 'OnBuildAccountNumberTemplateList', 'OnBuildAccountNumberTemplateListHandler');
    AddEventHandler("sale", "OnBeforeOrderAccountNumberSet", "OnBeforeOrderAccountNumberSetHandler");
	
	/*function gogo(){
		$message=serialize($arFields);
		mail('ove-shop@mail.ru', 'the subject', 'tyu', null);
	}*/
	//КЛАСС ДЛЯ ПЕРЕДАЧИ ДАННЫХ МЕЖДУ КОМПОНЕНТАМИ
	Class GarbageStorage{
		private static $storage = array();
		public static function set($name, $value){ self::$storage[$name] = $value;}
		public static function get($name){ return self::$storage[$name];}
	}

    function isPartnerClient(){
        static $partner_ID = false;
        static $second = false;

        if(!$second){
            $second = true;
            global $USER;
            $arGroups = $USER->GetUserGroupArray();
            $userGroupsString = implode(" | ", $arGroups);
            $filter = Array("ID" => $userGroupsString, "ACTIVE" => "Y");
            $rsGroups = CGroup::GetList(($by = "c_sort"), ($order = "desc"), $filter);
            //if($rsGroups->is_filtered){
                while ($get = $rsGroups->GetNext()) {
                    if (substr($get['STRING_ID'], 0, 8) == 'partner_') {
                        $partner_ID = str_replace('partner_', '', $get['STRING_ID']);
                        break;
                    }
                }
            //}
        }

        return $partner_ID;
    }


function OnBuildAccountNumberTemplateListHandler(){
    return array('CODE'=>'an_partner','NAME'=>'Номер заказа для партнера');
}
function OnBeforeOrderAccountNumberSetHandler($ID,$type){
    if($type=='an_partner'){
        if(CModule::IncludeModule('sale') && $ID>0) {
            $order_num = "11$ID";
            if(isPartnerClient()){
                if(isset($_SESSION['EXTRA_BILL_NUMBER_1C']))
                    $order_num = $_SESSION['EXTRA_BILL_NUMBER_1C'];
            }
            return $order_num;
        }
    }
    return false;
}

function selectSavedPersonType(&$arResult, &$arUserResult, $arParams)
{
    global $USER;
    if($USER->IsAuthorized())
    {
        $rsUser = $USER->GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();
        $entity = $arUser['UF_PARTNER_XML_ID']; //поле принадлежности к юр. лицу

        $personType = 0;
        if ($entity) {
            $personType = 2;
        } else {
            $personType = 1;
        }
        //очищаем текущее значение типа плательщика
        foreach($arResult['PERSON_TYPE'] as $key => $type){
            if($type['CHECKED'] == 'Y'){
                unset($arResult['PERSON_TYPE'][$key]['CHECKED']);
            }
        }
        //устанавливаем новое значение типа плательщика
        $arResult['PERSON_TYPE'][$personType]['CHECKED'] = 'Y';
        $arUserResult['PERSON_TYPE_ID'] = $personType;
    }
}
	
function customOnGetOptimalPrice($intProductID,$quantity,$arUserGroups,$renewal,$arPrices){
    $iblock_id=10;
    if(!empty($intProductID)&&$_SESSION['TF_LOCATION_SELECTED_CITY_NAME']=='Краснодар') {
        //определение и установка нужной цены
        $arSelect = array(
            'IBLOCK_ID',
            'ID',
            'NAME',
            'CATALOG_GROUP_1'
        );
        $arFilter = array(
            'IBLOCK_ID' => $iblock_id,
            'ID' => $intProductID
        );
        $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        $element = $res->GetNext();
        $price = $element['CATALOG_PRICE_1'];
        
        $db_props = CIBlockElement::GetProperty($iblock_id, $intProductID, array("sort" => "asc"), Array("CODE"=>"vendor"));
        if($ar_props = $db_props->Fetch())
            $vendor = $ar_props["VALUE"];
        else $vendor = '';
        if($vendor=='Акватон')$price = $element['CATALOG_PRICE_1']*1.07;
        //если задан купон скидки
        if(!empty($arDiscountCoupons)) {
            $arSelectCoup = array('DISCOUNT_ID');
            $arFilterCoup = array(
                'COUPON' => $arDiscountCoupons[0]
            );
            $recCoup = CCatalogDiscountCoupon::GetList(
                array(),
                $arFilterCoup,
                false,
                false,
                $arSelectCoup
            );
            $coupon = $recCoup->GetNext();
            $discount = CCatalogDiscount::GetByID($coupon['DISCOUNT_ID']);
        }
        return array(
            'PRICE' => array(
                'CATALOG_GROUP_ID' => 1,
                'PRICE' => $price,
                'CURRENCY' => "RUB",
            ),
            'DISCOUNT_LIST' => array(
                array(
                    'VALUE_TYPE' => $discount['VALUE_TYPE'],
                    'VALUE' => $discount['VALUE'],
                    'CURRENCY' => $discount['CURRENCY']
                )
            )

        );

    }
    return true;
}

function customOnOrderSend1C($ordId){
	$ordId=$ordId;
	$file_time=time();
	$filesave="/var/www/west/data/INOUT/OUT/STS/order$file_time.ord";
	$filesave_reserv="/var/www/west/data/INOUT/OUT/order$file_time.ord";

    global $USER;
    $arGroups = $USER->GetUserGroupArray();
    $isTestPay = ($arGroups[3] == 19);
    $isPartner = isPartnerClient();

    if($isPartner){
        CSaleOrder::StatusOrder($ordId, "OE");
    }

    if(!$isTestPay && !$isPartner)
	if ($handle = fopen($filesave, 'w')) {
	    $pr_out="order_id=$ordId\n";

	    $db_sales = CSaleOrder::GetList(false,array('ID'=>$ordId),false,false,array('ACCOUNT_NUMBER','DELIVERY_ID'));
        if ($ar_sale = $db_sales->GetNext(true,false)){
            $order_number=$ar_sale['ACCOUNT_NUMBER'];
		    $pr_out.="order_number=sts_$order_number\n";
		    
		    $ar_deliv=CSaleDeliveryHandler::GetBySID(preg_replace("/\:.+/",'',$ar_sale['DELIVERY_ID']))->GetNext(true,false);
		    $deliv_name="СДЭК";//trim(str_replace("Доставка Службой",'',$ar_deliv['NAME']));
		    $pr_out.="delivery=$deliv_name\n";
        }
        
        $prop_address_deliv = '';
        $prop_city = '';
        $prop_fio = '';
        $prop_email = '';//for check
        $prop_partner_id = '';

	    $db_vals = CSaleOrderPropsValue::GetList(array("SORT" => "ASC"),array("ORDER_ID" => $ordId));
        while ($arVals = $db_vals->Fetch()){
        	$code=strtolower($arVals['CODE']);
        	$value=trim(str_replace(array("\r","\n"),' ',$arVals['VALUE']));
            
        	if($code=='city'&&!empty($_SESSION['TF_LOCATION_SELECTED_CITY_NAME'])) {
        	    $value=$_SESSION['TF_LOCATION_SELECTED_CITY_NAME'];
        	}
        	if($code == 'location'){
        	    $db_vars = CSaleLocation::GetList(array("CITY_NAME"=>"ASC"), array("LID" => LANGUAGE_ID,"ID"=>$value), false, false, array());
        	    while($vars = $db_vars->Fetch()){
        	        if(!empty($vars['CITY_NAME'])) {
        	            $code = 'city';
        	            $value = $vars['CITY_NAME'];
        	        }
        	    }
        	}
        	if($code == 'company') $prop_fio = $value;
            if($code == 'partner_id') $prop_partner_id = $value;
        	
        	if($code == 'address_deliv' || $code == 'city' || $code == 'fio' || $code == 'email'){
        	    if($code == 'address_deliv') $prop_address_deliv = $value;
        	    if($code == 'city')  $prop_city = $value;
        	    if($code == 'fio')  $prop_fio = $value;
        	    if($code == 'email')  $prop_email = $value;
        	}
            else $pr_out.="$code=$value\n";
        }
        
        $pr_out.="address_deliv=$prop_address_deliv\n";
        $pr_out.="city=$prop_city\n";
        $pr_out.="fio=$prop_fio\n";
        $pr_out.="email=$prop_email\n";
        
        if(empty($prop_email)){
	        fclose( $handle );
		    unlink($filesave);
		    return;
        }

	    $products=array();
	    $res=CSaleBasket::GetList(array(),array("ORDER_ID"=>$ordId),false,false,array("PRODUCT_ID","PRICE","QUANTITY"));
	    while($resA=$res->fetch()){
            $db_props = CIBlockElement::GetProperty(10, $resA["PRODUCT_ID"], array("sort" => "asc"), Array("CODE"=>"sku_second"));

            $resItm=CIBlockElement::GetList(Array(),Array("ID"=>$resA["PRODUCT_ID"]),false,false,Array("XML_ID"));
            $resItm=$resItm->fetch();

            if($ar_props = $db_props->Fetch()){
                $sku_second = IntVal($ar_props["VALUE"]);
                if(!empty($sku_second)){
                    $db_props = CIBlockElement::GetProperty(10, $resA["PRODUCT_ID"], array("sort" => "asc"), Array("CODE"=>"price_plus"));
                    if($ar_props = $db_props->Fetch()) {
                        $price_plus = IntVal($ar_props["VALUE"]);
                        if (!empty($price_plus)) {
                            $product=array();
                            $product[0]=$resItm["XML_ID"];
                            $product[1]=intval($resA["PRICE"])-intval($price_plus);
                            $product[2]=$resA["QUANTITY"];
                            $products[]=$product;

                            $product[0]=$sku_second;
                            $product[1]=$price_plus;
                            $product[2]=$resA["QUANTITY"];
                            $products[]=$product;
                        }
                    }
                }else{
                    $product=array();
                    $product[0]=$resItm["XML_ID"];
                    $product[1]=$resA["PRICE"];
                    $product[2]=$resA["QUANTITY"];
                    $products[]=$product;
                }
            }else{
                $product=array();
                $product[0]=$resItm["XML_ID"];
                $product[1]=$resA["PRICE"];
                $product[2]=$resA["QUANTITY"];
                $products[]=$product;
            }
	    }
	    foreach($products as $product){
	        $product=implode("^", $product);
	        $pr_out.="product=$product\n";
	    }

	    fwrite($handle, $pr_out);
	    fclose($handle);
	    copy($filesave,$filesave_reserv);

        if(!empty($prop_partner_id)) unlink($filesave);
	}
}
//////YANDEX_ADV//////////
if(isset($_GET['yd_cid'])&&isset($_GET['yd_gid'])&&isset($_GET['yd_aid'])){
    $cookie_param=implode(';',array($_GET['yd_cid'],$_GET['yd_gid'],$_GET['yd_aid']));
    global $APPLICATION;
    $APPLICATION->set_cookie("STS_ADC", $cookie_param, time()+60*60*24*365);
}
//////ARNIKA_ADV//////////
if(isset($_GET['utm_source'])&&isset($_GET['utm_medium'])&&isset($_GET['utm_campaign'])&&isset($_GET['utm_content'])&&isset($_GET['utm_term'])){
    $cookie_param=implode(';',array($_GET['utm_source'],$_GET['utm_medium'],$_GET['utm_campaign'],$_GET['utm_content'],$_GET['utm_term']));
    global $APPLICATION;
    $APPLICATION->set_cookie("STS_ARN", $cookie_param, time()+60*60*24*365);
}
//////GEO_PRICE/////////////
require_once 'init_geo.php';
?>
