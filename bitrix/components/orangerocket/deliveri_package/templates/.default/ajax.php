<?require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");?>
<div class="pv_head">
<h4> <?=$_REQUEST['a'];?></h4>
<h5>Телефоны:</h5><span><?$tn=explode(',',$_REQUEST['b']);
foreach ($tn as $num) {
    echo "$num;<br>";
}
?></span>
<h5>Режим работы:</h5><span><?=$_REQUEST['d'];?></span>
<div style="margin-top:10px"><h5 style="float:left;padding:0">Логистический партнер:</h5><span class="lg_logo"><img alt="СДЭК Логистика" width="80" src="/bitrix/components/orangerocket/delivery_points/templates/.default/images/cdek.jpg"> </span></div>
<div class="clear-both"></div>
<h5>Местоположение на карте:</h5>
</div>
<?$crd=explode(',',$_REQUEST['c']);
if($crd){
$MAP_DATA=Array
(
   "yandex_lat" => $crd[0],
   "yandex_lon" => $crd[1],
   "yandex_scale" => "16",
   "PLACEMARKS" => Array
      (
      "0" => Array
      (
         "TEXT" => "Пункт Выдачи",
         "LON" => $crd[1],
         "LAT" =>$crd[0],
      )

   ),
);
?>
<?$APPLICATION->IncludeComponent("bitrix:map.yandex.view",".default",Array(
        "INIT_MAP_TYPE" => "MAP",
        "MAP_DATA" => serialize($MAP_DATA),
        "MAP_WIDTH" => "600",
        "MAP_HEIGHT" => "500",
        "CONTROLS" => array(
            "TOOLBAR",
            "ZOOM",
            "SMALLZOOM",
            "MINIMAP",
            "TYPECONTROL",
            "SCALELINE"
        ),
        "OPTIONS" => array(
            "ENABLE_SCROLL_ZOOM",
            "ENABLE_DBLCLICK_ZOOM",
            "ENABLE_DRAGGING"
        ),
        "MAP_ID" => "yam_1".rand(20,400).''
    )
);
}
?>
<?$photos=explode(',',$_REQUEST['p']);
foreach($photos as $photo):
    $photo_src=CFile::ResizeImageGet($photo)['src'];?>
<span class="lg_logo"><img src="<?=$photo_src?>"></span>
<?endforeach?>
</div>
</div>
