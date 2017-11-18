<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (CModule::IncludeModule("sale")) {

    echo 'Выполните действие:<br>';

    $order_id = $arGadgetParams["ORDER_NUMBER"];
    echo "<b>Заказ $order_id:</b><br>";

    if (isset($order_id)) {

        $db_sales = CSaleOrder::GetList(false, array('ID' => $order_id));
        if ($ar_sale = $db_sales->GetNext(true, false)) {

            $ar_props=array();

            $db_vals = CSaleOrderPropsValue::GetList(array(), array("ORDER_ID" => $order_id));
            while ($arVals = $db_vals->Fetch())
                $ar_props[$arVals["CODE"]] = $arVals["VALUE"];

//print_r($ar_sale);
//print_r($ar_props);
            $add_info=explode('-',$ar_sale['ADDITIONAL_INFO']);

            $arQuery['AMOUNT'] = $ar_props['Amount'];
            $arQuery['CURRENCY'] = $ar_props['Currency'];
            $arQuery['ORDER'] = $ar_sale['ACCOUNT_NUMBER'];
            $arQuery['RRN'] = $ar_props['RRN'];
            $arQuery['INT_REF'] = $ar_props['IntRef'];
            $arQuery['TERMINAL'] = $arGadgetParams["TERMINAL"];
            $arQuery['TIMESTAMP'] = gmdate('YmdHis');
            $arQuery['MERC_GMT'] = $arGadgetParams["MERC_GMT"];
            $arQuery['MERCH_URL'] = "http://www.santehsmart.ru/bitrix/admin/sale_order_view.php?ID=$order_id";
//$arQuery['TRTYPE']    = CSalePaySystemAction::GetParamValue("TRTYPE");

            $allow=true;
            foreach($arQuery as $prop){
                if(empty($prop))$allow=false;
            }

            ?>
            <form method="POST" action="https://3ds.mdmbank.ru:443/cgi-bin/cgi_link">
                <?foreach ($arQuery as $key => $value) {?>
                    <input type="hidden" name="<?= $key ?>" value="<?= $value ?>">
                <?}?>
                <select name="TRTYPE">
                    <option value="21">Выполнить (21)</option>
                    <option value="22">Отменить (22)</option>
                    <option value="24">Отменить (24)</option>
                </select>
                <input type="<?=($allow?'submit':'hidden')?>" value="Перейти">
                <?=($allow?'':'Недостаточно данных')?>
            </form>
            <?

        }
    }
}
