<?php
include_once $_SERVER["DOCUMENT_ROOT"].'/testzone/util/platform.php';
include_once getPlatformPath();
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Mail\Event;

function mail_utf8($to, $from, $subj, $message)
{
    $subject = '=?UTF-8?B?' . base64_encode($subj) . '?=';
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit \r\n";
    $headers .= "From: $from\r\n";

    return mail($to, $subject, $message, $headers);
}

$arResult = array();
if ((CModule::IncludeModule("iblock")&&CModule::IncludeModule("catalog")&&CModule::IncludeModule("sale")&&CModule::IncludeModule("highloadblock"))){
	if(isset($_POST['cart_content'])){
		$pl = new Platform();
		$partner_id = $_POST['partner_id'];
        $alt_partner_id = $_POST['alt_partner_id'];
		$comment = $_POST['comment'];
		$arProducts = $_POST['cart_content'];
		$arNames = $_POST['cart_names'];
		$arPrices = $_POST['cart_prices'];
		$arResult = array();
		if($pl->makeNewBill($partner_id,$alt_partner_id,$comment,$arProducts,$arPrices,$arNames)){
			$arResult['status'] = 'ok';
            $arResult['response'] = $pl->getLastResponse();
            $arResult['data'] = $pl->getExtraData();
            $arMail = $arResult['data']['mail'];
            $arResult['data'] = $arResult['data']['json'];

            Event::send(array(
                "EVENT_NAME" => "PARTNER_ORDER_FOR_MANAGER",
                "LID" => "s1",
                "C_FIELDS" => $arMail,
            ));

            /*$to=$arManager['UF_MAIL'];
            $from='sale@santehsmart.ru';
            $subj="Заявка Атаманенко тест";

            $message="Была сформирована заявка N <ТЕСТ> на сумму <ТЕСТ>, кол-во позиций: <ТЕСТ>.";*/

            mail_utf8($to, $from, $subj, $message);
		}else{
			/*$arResult['status'] = 'error';
            $arResult['response'] = $pl->getLastError();
            $arResult['data'] = $pl->getExtraData();*/
			$arResult = $pl->getCartContentPlus($arProducts,$arNames);
		}
	}else{
		$arResult['status'] = 'error';
	}
}else{
	$arResult['status'] = 'error';
}

echo json_encode($arResult);