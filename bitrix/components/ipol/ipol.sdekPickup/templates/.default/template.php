<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	if(coption::GetOptionString(CDeliverySDEK::$MODULE_ID,'addJQ','Y')=='Y')
		CJSCore::init('jquery');
	global $APPLICATION;
	if($arParams['NOMAPS']!='Y'){
		$prot = sdekHelper::defineProto();
		$APPLICATION->AddHeadString('<script src="'.$prot.'://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>');
	}
	$APPLICATION->AddHeadString('<script src="/bitrix/js/'.CDeliverySDEK::$MODULE_ID.'/jquery.mousewheel.js" type="text/javascript"></script>');
	$APPLICATION->AddHeadString('<script src="/bitrix/js/'.CDeliverySDEK::$MODULE_ID.'/jquery.jscrollpane.js" type="text/javascript"></script>');
	$APPLICATION->AddHeadString('<link href="/bitrix/js/'.CDeliverySDEK::$MODULE_ID.'/jquery.jscrollpane.css" type="text/css"  rel="stylesheet" />');
	if($arParams['CNT_DELIV'] == 'Y')
		$order = "{
			WEIGHT : '{$arResult['ORDER']['WEIGHT']}',
			PRICE  : '{$arResult['ORDER']['PRICE']}',
			GOODS  : {$arResult['ORDER']['GOODS_js']}
		}";
	else{
		$order = 'false';
		if($arParams['DIMENSIONS']){
			$dimJS = "{
				LENGTH : '".(($arParams['DIMENSIONS']['LENGTH']) ? $arParams['DIMENSIONS']['LENGTH'] : 0)."',
				WIDTH  : '".(($arParams['DIMENSIONS']['WIDTH'])  ? $arParams['DIMENSIONS']['WIDTH']  : 0)."',
				HEIGHT : '".(($arParams['DIMENSIONS']['HEIGHT']) ? $arParams['DIMENSIONS']['HEIGHT'] : 0)."',
			}";
		}else
			$dimJS = false;
	}
	$forbidens = "[";
	if($arParams['FORBIDDEN'])
		foreach($arParams['FORBIDDEN'] as $forb)
			$forbidens .= "'$forb',";
	$forbidens .= "]";
	?>
		<script>
			var IPOLSDEK_pvz = {
				city: '<?=$arResult['city']?>',//город

				pvzInputs: [<?=substr($IPOLSDEK_propAddr,0,-1)?>],//инпуты, куда грузится адрес пвз

				pvzLabel: '',//jq-объект элемента, куда лепим кнопку выбрать ПВЗ

				pvzPrice: false,

				PVZ: {
					<?foreach($arResult['PVZ'] as $city => $deliveryPoints){?>
						'<?=$city?>':{
							<?foreach($deliveryPoints as $dpId => $descr){?>
								'<?=$dpId?>':{
									'Name'     : '<?=$descr['Name']?>',
									'Address'  : '<?=$descr['Address']?>',
									'WorkTime' : '<?=$descr['WorkTime']?>',
									'Phone'    : '<?=$descr['Phone']?>',
									'Note'     : '<?=$descr['Note']?>',
									'cX'       : '<?=$descr['cX']?>',
									'cY'       : '<?=$descr['cY']?>',
								},
							<?}?>
						},
					<?}?>
				},
				
				order: <?=$order?>,
				
				forbidden: <?=$forbidens?>,
				
				cityPVZ: {},//объект с ПВЗ города, там сидят они + координаты для Яндекса

				scrollPVZ: false,//скролл пунктов ПВЗ

				scrollDetail: false,//скролл детальной информации

				init: function(){
					IPOLSDEK_pvz.initCityPVZ();
					<?if($arParams['CNT_DELIV'] != 'Y'){?>IPOLSDEK_pvz.loadCityCost();<?}?>
					<?if(!in_array("pickup",$arParams['FORBIDDEN'])){?>IPOLSDEK_pvz.Y_init();<?}?>
				},

				chooseCity: function(city){
					$('#SDEK_citySel a').each(function(){
						$(this).css('display','');
						if($(this).attr('onclick').indexOf(city)!==-1){
							$(this).css('display','none');
							$('#SDEK_cityName').html($(this).text());
						}
					});
					$('#SDEK_citySel').css('display','none');
					IPOLSDEK_pvz.city = city;
					if(IPOLSDEK_pvz.scrollPVZ && typeof(IPOLSDEK_pvz.scrollPVZ.data('jsp'))!='undefined')
						IPOLSDEK_pvz.scrollPVZ.data('jsp').destroy();
					IPOLSDEK_pvz.initCityPVZ();
					<?if(!in_array("pickup",$arParams['FORBIDDEN'])){?>IPOLSDEK_pvz.Y_init();<?}?>
					IPOLSDEK_pvz.loadCityCost();
				},

				initCityPVZ: function(){ // грузим пункты самовывоза для выбранного города
					var city = IPOLSDEK_pvz.city;
					IPOLSDEK_pvz.cityPVZ = {};
					for(var i in IPOLSDEK_pvz.PVZ[city]){
						IPOLSDEK_pvz.cityPVZ[i] = {
							'Name'     : IPOLSDEK_pvz.PVZ[city][i]['Name'],
							'Address'  : IPOLSDEK_pvz.PVZ[city][i]['Address'],
							'WorkTime' : IPOLSDEK_pvz.PVZ[city][i]['WorkTime'],
							'Phone'    : IPOLSDEK_pvz.PVZ[city][i]['Phone'],
							'Note'     : IPOLSDEK_pvz.PVZ[city][i]['Note'],
							'cX'       : IPOLSDEK_pvz.PVZ[city][i]['cX'],
							'cY'       : IPOLSDEK_pvz.PVZ[city][i]['cY'],
						}; 
					}
					IPOLSDEK_pvz.cityPVZHTML();//грузим html PVZ. Два раза пробегаем по массиву, но не критично.
				},
				
				cityPVZHTML: function(){ // заполняем список ПВЗ города
					var html = '';
					for(var i in IPOLSDEK_pvz.cityPVZ)
						html+='<p id="PVZ_'+i+'" onclick="IPOLSDEK_pvz.markChosenPVZ(\''+i+'\')" onmouseover="IPOLSDEK_pvz.Y_blinkPVZ(\''+i+'\',true)" onmouseout="IPOLSDEK_pvz.Y_blinkPVZ(\''+i+'\')" >'+IPOLSDEK_pvz.paintPVZ(i)+'</p>';
					$('#SDEK_wrapper').html(html);
					IPOLSDEK_pvz.scrollPVZ=$('#SDEK_wrapper').jScrollPane();
				},
				
				paintPVZ: function(ind){ //красим адресс пвз, если задан цвет
					var addr = '';
					if(IPOLSDEK_pvz.cityPVZ[ind].color && IPOLSDEK_pvz.cityPVZ[ind].Address.indexOf(',')!==false)
						addr="<span style='color:"+IPOLSDEK_pvz.cityPVZ[ind].color+"'>"+IPOLSDEK_pvz.cityPVZ[ind].Address.substr(0,IPOLSDEK_pvz.cityPVZ[ind].Address.indexOf(','))+"</span><br>"+IPOLSDEK_pvz.cityPVZ[ind].Name;
					else
						addr=IPOLSDEK_pvz.cityPVZ[ind].Name;
					return addr;
				},
				
				/* detailPVZ: function(id){
					if(IPOLSDEK_pvz.scrollDetail && typeof(IPOLSDEK_pvz.scrollDetail.data('jsp'))!='undefined')
						IPOLSDEK_pvz.scrollDetail.data('jsp').destroy();
					var addrStr = IPOLSDEK_pvz.cityPVZ[id].Address; 
					var detailHtml = '<p><strong><?=GetMessage('IPOLSDEK_FRNT_ADDRESS')?></strong><br>'+addrStr+'</p>';
					if(IPOLSDEK_pvz.cityPVZ[id].WorkTime)
						detailHtml += '<p><strong><?=GetMessage('IPOLSDEK_FRNT_WORKTIME')?></strong><br>'+IPOLSDEK_pvz.cityPVZ[id].WorkTime+'</p>'					
					if(IPOLSDEK_pvz.cityPVZ[id].Phone)
						detailHtml += '<p><strong><?=GetMessage('IPOLSDEK_FRNT_PHONE')?></strong><br>'+IPOLSDEK_pvz.cityPVZ[id].Phone+'</p>';
					
					if(IPOLSDEK_pvz.cityPVZ[id].way)
						detailHtml += '<p><strong><?=GetMessage('IPOLSDEK_FRNT_HOWTOGET')?></strong><br>'+IPOLSDEK_pvz.cityPVZ[id].way.replace(/\|/g,'<br>')+'</p>';
					if(IPOLSDEK_pvz.cityPVZ[id].path)
						detailHtml += '<p><img src="'+IPOLSDEK_pvz.cityPVZ[id].path+'"></p>';
					$('#SDEK_fullInfo').html(detailHtml);
					IPOLSDEK_pvz.scrollDetail=$('#SDEK_detail').jScrollPane({autoReinitialise: true});
					$('#SDEK_info').children('div').animate({'marginLeft':'-300px'},500);
				},
				
				backFromDetail: function(){
					if(IPOLSDEK_pvz.scrollDetail && typeof(IPOLSDEK_pvz.scrollDetail.data('jsp'))!='undefined')
						IPOLSDEK_pvz.scrollDetail.data('jsp').destroy();
					$('#SDEK_info').children('div').animate({'marginLeft':'0px'},500);
				}, */

				markChosenPVZ: function(id){
					if($('.sdek_chosen').attr('id')!='PVZ_'+id){
						$('.sdek_chosen').removeClass('sdek_chosen');
						$("#PVZ_"+id).addClass('sdek_chosen');
						IPOLSDEK_pvz.Y_selectPVZ(id);
					}
				},

				//Yкарты
				Y_map: false,//указатель на y-карту

				Y_init: function(){
					if(typeof IPOLSDEK_pvz.city == 'undefined')
						IPOLSDEK_pvz.city = '<?=GetMessage('IPOLSDEK_FRNT_MOSCOW')?>';
					ymaps.geocode("<?=GetMessage("IPOLSDEK_RUSSIA")?>, "+IPOLSDEK_pvz.city , {
						results: 1
					}).then(function (res) {
							var firstGeoObject = res.geoObjects.get(0);
							var coords = firstGeoObject.geometry.getCoordinates();
							coords[1]-=0.2;
							if(!IPOLSDEK_pvz.Y_map){
								IPOLSDEK_pvz.Y_map = new ymaps.Map("SDEK_map",{
									zoom:10,
									controls: [],
									center: coords
								});
								var ZK = new ymaps.control.ZoomControl({
									options : {
										position:{
											left : 265,
											top  : 146
										}
									}
								});
								
								IPOLSDEK_pvz.Y_map.controls.add(ZK);
							}
							else{
								IPOLSDEK_pvz.Y_map.setCenter(coords);
								IPOLSDEK_pvz.Y_map.setZoom(10);
							}
							if(!IPOLSDEK_pvz.Y_markedCities[IPOLSDEK_pvz.city]) //чтобы не грузились повторно
								IPOLSDEK_pvz.Y_markPVZ();
							else
								IPOLSDEK_pvz.cityPVZ = IPOLSDEK_pvz.Y_markedCities[IPOLSDEK_pvz.city];
					});
				},

				Y_markPVZ: function(){
					for(var i in IPOLSDEK_pvz.cityPVZ){
						var baloonHTML  = "<div id='SDEK_baloon'>";
						baloonHTML += "<div class='SDEK_iAdress'>";
						if(IPOLSDEK_pvz.cityPVZ[i].Address.indexOf(',')!==-1){
							if(IPOLSDEK_pvz.cityPVZ[i].color)
								baloonHTML +=  "<span style='color:"+IPOLSDEK_pvz.cityPVZ[i].color+"'>"+IPOLSDEK_pvz.cityPVZ[i].Address.substr(0,IPOLSDEK_pvz.cityPVZ[i].Address.indexOf(','))+"</span>";
							else
								baloonHTML +=  IPOLSDEK_pvz.cityPVZ[i].Address.substr(0,IPOLSDEK_pvz.cityPVZ[i].Address.indexOf(','));
							baloonHTML += "<br>"+IPOLSDEK_pvz.cityPVZ[i].Address.substr(IPOLSDEK_pvz.cityPVZ[i].Address.indexOf(',')+1).trim();
						}
						else
							baloonHTML += IPOLSDEK_pvz.cityPVZ[i].Address;
						baloonHTML += "</div>";

						if(IPOLSDEK_pvz.cityPVZ[i].Phone)
							baloonHTML += "<div><div class='SDEK_iTelephone sdek_icon'></div><div class='sdek_baloonDiv'>"+IPOLSDEK_pvz.cityPVZ[i].Phone+"</div><div style='clear:both'></div></div>";
						if(IPOLSDEK_pvz.cityPVZ[i].WorkTime)
							baloonHTML += "<div><div class='SDEK_iTime sdek_icon'></div><div class='sdek_baloonDiv'>"+IPOLSDEK_pvz.cityPVZ[i].WorkTime+"</div><div style='clear:both'></div></div>";
						
						if(IPOLSDEK_pvz.cityPVZ[i].Note)
							baloonHTML += "<div class='sdek_baloonDiv'><a href='javascript:void(0)' title='"+IPOLSDEK_pvz.cityPVZ[i].Note+"'><?=GetMessage('IPOLSDEK_FRNT_DETAIL')?></a></div>";
						
						baloonHTML += "</div>";
						IPOLSDEK_pvz.cityPVZ[i].placeMark = new ymaps.Placemark([IPOLSDEK_pvz.cityPVZ[i].cY,IPOLSDEK_pvz.cityPVZ[i].cX],{
							balloonContent: baloonHTML
						}, {
							iconLayout: 'default#image',
							iconImageHref: '/bitrix/images/ipol.sdek/widjet/sdekNActive.png',
							iconImageSize: [40, 43],
							iconImageOffset: [-10, -31]
						});
						IPOLSDEK_pvz.Y_map.geoObjects.add(IPOLSDEK_pvz.cityPVZ[i].placeMark);
						IPOLSDEK_pvz.cityPVZ[i].placeMark.link = i;
						IPOLSDEK_pvz.cityPVZ[i].placeMark.events.add('balloonopen',function(metka){
							IPOLSDEK_pvz.markChosenPVZ(metka.get('target').link);
						});
					}
					IPOLSDEK_pvz.Y_markedCities[IPOLSDEK_pvz.city]=IPOLSDEK_pvz.cityPVZ;
				},

				Y_selectPVZ: function(wat){
					IPOLSDEK_pvz.cityPVZ[wat].placeMark.balloon.open();
					IPOLSDEK_pvz.Y_map.setCenter([IPOLSDEK_pvz.cityPVZ[wat].cY,IPOLSDEK_pvz.cityPVZ[wat].cX]);
				},
				
				Y_blinkPVZ: function(wat,ifOn){
					if(typeof(ifOn)!='undefined' && ifOn)
						IPOLSDEK_pvz.cityPVZ[wat].placeMark.options.set({iconImageHref:"/bitrix/images/ipol.sdek/widjet/sdekActive.png"});
					else
						IPOLSDEK_pvz.cityPVZ[wat].placeMark.options.set({iconImageHref:"/bitrix/images/ipol.sdek/widjet/sdekNActive.png"});
				},

				Y_markedCities: {},
				
				loadCityCost: function(){
					var data = (IPOLSDEK_pvz.order)?IPOLSDEK_pvz.order:{};
					data['CITY_TO'] = IPOLSDEK_pvz.city;
					data['action']  = 'cntDelivs';
					data['FORBIDDEN'] = IPOLSDEK_pvz.forbidden;
					<?if($dimJS){?>data['DIMS'] = <?=$dimJS?>;<?}?>
					$.ajax({
						url: '/bitrix/js/ipol.sdek/ajax.php',
						type: 'POST',
						dataType: 'JSON',
						data: data,
						success: function(data){
							var transDate=false;
							if(data.courier != 'no'){
								if(typeof data.c_date == 'undefined') transDate = data.date;
								else transDate = data.c_date;
								$('#SDEK_cPrice').html(data.courier);
								$('#SDEK_cDate').html("<td><?=GetMessage("IPOLSDEK_DELTERM")?></td><td>"+transDate+"<?=GetMessage("IPOLSDEK_DAY")?></td>");
							}else{
								$('#SDEK_cPrice').html("");
								$('#SDEK_cDate').html("<td colspan='2'><?=GetMessage("IPOLSDEK_NO_DELIV")?></td>");		
							}
							
							if(data.pickup != 'no'){
								if(typeof data.p_date == 'undefined') transDate = data.date;
								else transDate = data.p_date;
								$('#SDEK_pPrice').html(data.pickup);
								$('#SDEK_pDate').html(transDate+"<?=GetMessage("IPOLSDEK_DAY")?>");
							}else{
								$('#SDEK_pPrice').html("");
								$('#SDEK_pDate').html("<?=GetMessage("IPOLSDEK_NO_DELIV")?>");		
							}
						}
					});
				},
			}
			$(document).ready(function(){
				ymaps.ready(
					function(){
						IPOLSDEK_pvz.init();
					}
				);
				<?if(in_array("pickup",$arParams['FORBIDDEN'])){?>
					$('#SDEK_map').css('display','none');
					$('#SDEK_info').css('display','none');
				<?}?>
			});

			function showCitySel(){
				$('#SDEK_citySel').css('display','');
			}
		</script>
		<div id='SDEK_pvz'>
			<?if(count($arResult['Regions'])>1){?>
			<div id='SDEK_title'>
				<div id='SDEK_cityPicker'>
					<div><?=GetMessage("IPOLSDEK_YOURCITY")?></div>
					<div>
						<div id='SDEK_cityLabel'>
							<a id='SDEK_cityName' href='javascript:void(0)' onmouseover='showCitySel(); return false;'><?=$arResult['city']?></a>
							<div id='SDEK_citySel'>
								<?foreach($arResult['Regions'] as $city){?>
									<a href='javascript:void(0)' <?=($city==CDeliverySDEK::toUpper($arResult['city']))?"style='display:none'":''?> onclick='IPOLSDEK_pvz.chooseCity("<?=$city?>");return false;'><?=$city?><br></a>
								<?}?>
							</div>
						</div>
					</div>
				</div>
				<?/*
				<div id='SDEK_logoPlace'></div>
				<div id='SDEK_separator'></div>
				*/?>
				<div class='SDEK_mark'>
					<table>
					<tr><td><strong><?=GetMessage("IPOLSDEK_COURIER")?></strong></td><td><span id='SDEK_cPrice'><?=($arResult['DELIVERY']['courier']!='no')?$arResult['DELIVERY']['courier']:""?></span></td></tr>
					<tr id='SDEK_cDate' title='<?=GetMessage("IPOLSDEK_HINT")?>'><td><?=($arResult['DELIVERY']['courier']!='no')?GetMessage("IPOLSDEK_DELTERM")."</td><td>".$arResult['DELIVERY']['date'].GetMessage("IPOLSDEK_DAY"):"<td colspan='2'>".GetMessage("IPOLSDEK_NO_DELIV")?></td></tr>
					</table>
				</div>
				<?/*
				<div class='SDEK_mark'>
					<strong><?=GetMessage("IPOLSDEK_PICKUP")?></strong> <span id='SDEK_pPrice'><?=($arResult['DELIVERY']['pickup']!='no')?$arResult['DELIVERY']['pickup']:""?></span><br>
					<span id='SDEK_pDate' title='<?=GetMessage("IPOLSDEK_HINT")?>'><?=($arResult['DELIVERY']['pickup']!='no')?GetMessage("IPOLSDEK_DELTERM").$arResult['DELIVERY']['date'].GetMessage("IPOLSDEK_DAY"):GetMessage("IPOLSDEK_NO_DELIV")?></span>
				</div>
				*/?>
				<div style='float:none;clear:both'></div>
			</div>
			<?}?>
			<div id='SDEK_map'></div>
			<div id='SDEK_info'>
				<div id='SDEK_sign'><?=GetMessage("IPOLSDEK_LABELPVZ")?></div>
				<div id='SDEK_delivInfo'><?=GetMessage("IPOLSDEK_DELIVERY")?>
					<span id='SDEK_pPrice'><?=($arResult['DELIVERY']['pickup']!='no')?$arResult['DELIVERY']['pickup']:""?></span>
					&nbsp;&nbsp;&nbsp;
					<span id='SDEK_pDate'><?=($arResult['DELIVERY']['pickup']!='no')?$arResult['DELIVERY']['date'].GetMessage("IPOLSDEK_DAY"):""?></span></div>
				<div>
					<div id='SDEK_wrapper'></div>
				</div>
				<div id='SDEK_ten'></div>
			</div>
			<div id='SDEK_head'>
				<div id='SDEK_logo'><a href='http://ipolh.com' target='_blank'></a></div>
			</div>
		</div>