<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Свойства");
?>
<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15.08.14
 * Time: 17:23
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(stripos($_FILES['csv']['name'],'.csv')<2){die('Не суй фигню');}
    $filename = $_FILES['csv']['tmp_name'];
    if ($handle = fopen($filename, 'r')) {//ОТКРЫЛИ ФАЙЛ
        $prop=new CIBlockProperty();
        $list = array();
        $h=fgetcsv($handle, 0, '^');//ПЕРВАЯ СТРОКА С ЗАГОЛОВКАМИ
        while ($csv = fgetcsv($handle, 0, '^')) { //ПЕРЕБИРАЕМ СТРОКИ
            foreach ($csv as $key => $value) $csv_arr[$h[$key]]=$value;
            addProperty($csv_arr,$prop);
            array_push($list, $csv_arr);
        }
        echo '<pre>';
        print_r($list);
        echo '</pre>';
    }
} else {
    ?>
    <div style='margin-top:50px; padding:30px; background-color:white;border-radius:10px;box-shadow:0px 0px 1px 1px silver;width:700px; margin-left:auto;margin-right:auto '>
        <h5>Обновление базы товаров<br>
            <form enctype='multipart/form-data' name='file' method='post'>
                <input type='file' name='csv' accept='.csv'>
                <input type='submit' value='Импортировать'>
            </form></div>
<?
}


function addProperty($a,$prop){
    $arFields = Array(
        "NAME" => $a["NAME"],
        "ACTIVE" => "Y",
        "SORT" => "600",
        "CODE" => $a["CODE"],
        "PROPERTY_TYPE" => $a["TYPE"],
        "IBLOCK_ID" => 10//$a["IBLOCK_ID"]
    );

    if($prop->Add($arFields)==FALSE)echo ''.$prop->LAST_ERROR.'<br>';
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>