<?php 
$this->setFrameMode(true);?>
<div class="pv">
<ul id="pv_menu">
<? foreach($arResult as $key => $value):?> 
        <?foreach($value as $City => $punkt):?> 
        <?$pv_ct_cls=($City==$_SESSION['TF_LOCATION_SELECTED_CITY_NAME'])?"pv_this_city":"";?>
            <li class="<?=$pv_ct_cls?>"><h5><?=$City?></h5>
               <ul class='<?=$pv_ct_cls?>'>
                      <? foreach ($punkt as $value): ?>	
                        <?if($City==$_SESSION['TF_LOCATION_SELECTED_CITY_NAME']&& !isset($flag)){
                        	$thisCoor=$value['coor'];
                        	$adress=$value['adress'];
                        	$phone=$value['phone'];
                        	$worktime=$value['worktime'];
                        	$photos=join(',',$value['photo']);
                        	$li_style="font-weight:700;color:rgba(255, 144, 0, 1)";
                            $flag=TRUE;
                        	}?>
           	                 <?$photos_ajax=join(',',$value['photo'])?>
        	                 <li style="<?=$li_style?>" onclick="searchik('<?=$templateFolder?>/ajax.php?a=<?=$value['adress']?>&b=<?=$value['phone']?>&c=<?=$value['coor']?>&d=<?=$value['worktime']?>&p=<?=$photos_ajax?>')"><?=$value['adress'];?></li>
        	                <?=$li_style=''?>

        <? endforeach;?></ul></li>

         <? endforeach;?>
     <? endforeach;?></ul></div>
<div id="re">
<div class="pv_head"><h4><?
$head=(isset($thisCoor))?"$adress":"Выберите доступный пункт выдачи из списка";
echo $head;
?></h4>
<?if(isset($thisCoor)):

?>
<h5>Телефоны:</h5><span><?$tn=explode(',',$phone);
foreach ($tn as $num) {
    echo "$num;<br>";
}
?></span>
<h5>Режим работы:</h5><span><?=$worktime;?></span>
<div style="margin-top:10px"><h5 style="float:left;padding:0">Логистический партнер:</h5><span class="lg_logo"><img alt="СДЭК Логистика" width="80" src="/bitrix/components/orangerocket/delivery_points/templates/.default/images/cdek.jpg"> </span></div>
<div class="clear-both"></div>
<h5>Местоположение на карте:</h5>
<?endif;?>
</div>
<?

if(isset($thisCoor)){
$thisCoor=explode(',',$thisCoor);


$MAP_DATA=Array
(
   "yandex_lat" => $thisCoor[0],
   "yandex_lon" => $thisCoor[1],
   "yandex_scale" => "16",
   "PLACEMARKS" => Array
      (
      "0" => Array
      (
         "TEXT" => "Пункт Выдачи",
         "LON" => $thisCoor[1],
         "LAT" =>$thisCoor[0],
      )

   ),
);}
else $MAP_DATA=Array
(
   "yandex_lat" => "",
   "yandex_lon" => "",
   "yandex_scale" => "5",
   "PLACEMARKS" => Array
      (
      "0" => Array
      (
         "TEXT" => "Пункт Выдачи",
         "LON" =>"",
         "LAT" =>"",
      )

   ),
);
$APPLICATION->IncludeComponent("bitrix:map.yandex.view",".default",Array(
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
        "MAP_ID" => "yam_1"
    )
);?>
<?
$photoss=explode(',',$photos);
foreach($photoss as $photo):
    $photo_src=CFile::ResizeImageGet($photo)['src'];?>
<span class="lg_logo"><img src="<?=$photo_src?>"></span>
<?endforeach?>
</div>
<div class="clear-both"></div>	




