<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/js/'.CDeliverySDEK::$MODULE_ID.'/jsloader.php');
	global $APPLICATION;
	if($arParams['NOMAPS']!='Y'){
		$prot = sdekHelper::defineProto();
		$APPLICATION->AddHeadString('<script src="'.$prot.'://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>');
	}
	$APPLICATION->AddHeadString('<link href="/bitrix/js/'.CDeliverySDEK::$MODULE_ID.'/jquery.jscrollpane.css" type="text/css"  rel="stylesheet" />');
	?>
		<script>
			IPOL_JSloader.checkScript('',"/bitrix/js/<?=CDeliverySDEK::$MODULE_ID?>/jquery.mousewheel.js");
			IPOL_JSloader.checkScript('$("body").jScrollPane',"/bitrix/js/<?=CDeliverySDEK::$MODULE_ID?>/jquery.jscrollpane.js");
			var IPOLSDEK_pvz = {
				button: '<a href="javascript:void(0);" id="SDEK_selectPVZ" onclick="IPOLSDEK_pvz.selectPVZ(); return false;"><?=GetMessage("IPOLSDEK_FRNT_CHOOSEPICKUP")?></a>',// html кнопки "выбрать ПВЗ".

				isActive: false, // открыт ли
				
				city: '<?=CDeliverySDEK::$city?>',//город
				
				cityID: '<?=CDeliverySDEK::$cityId?>', // id город

				pvzInputs: [<?=substr($arResult['propAddr'],0,-1)?>],//инпуты, куда грузится адрес пвз
				
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
				
				cityPVZ: {},//объект с ПВЗ города, там сидят они + координаты для Яндекса
				
				curPVZ: false,//выбранный ПВЗ
				
				scrollPVZ: false,//скролл пунктов ПВЗ
				
				scrollDetail: false,//скролл детальной информации
				
				multiPVZ: false, // false, если ПВЗ в городе несколько, или его id

				init: function(){
					// ==== подписываемся на перезагрузку формы
					if(typeof BX !== 'undefined' && BX.addCustomEvent)
						BX.addCustomEvent('onAjaxSuccess', IPOLSDEK_pvz.onLoad); 
					
					// Для старого JS-ядра
					if (window.jsAjaxUtil) // Переопределение Ajax-завершающей функции для навешивания js-событий новым эл-там
					{
						jsAjaxUtil._CloseLocalWaitWindow = jsAjaxUtil.CloseLocalWaitWindow;
						jsAjaxUtil.CloseLocalWaitWindow = function (TID, cont)
						{
							jsAjaxUtil._CloseLocalWaitWindow(TID, cont);
							IPOLSDEK_pvz.onLoad();
						}
					}
					// == END
					IPOLSDEK_pvz.onLoad();
					
					//html маски
					$('body').append("<div id='SDEK_mask'></div>");
				},
				
				getPrices: function(){
					$.ajax({
						url: '/bitrix/js/ipol.sdek/ajax.php',
						type: 'POST',
						dataType: 'JSON',
						data: {
							action: 'cntDelivs',
							CITY_TO: IPOLSDEK_pvz.city,
							WEIGHT: '<?=CDeliverySDEK::$orderWeight?>',
							PRICE : '<?=CDeliverySDEK::$orderPrice?>',
							CITY_TO_ID: IPOLSDEK_pvz.cityID, 
							<?if(CDeliverySDEK::$goods && is_array(CDeliverySDEK::$goods)){?>
								GABS : <?=json_encode(CDeliverySDEK::$goods)?>,
							<?}?>
						},
						success: function(data){
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
				
				onLoad: function(){
					// место, где будет кнопка "выбрать ПВЗ"
					var tag = false;

					<?if(COption::GetOptionString(CDeliverySDEK::$MODULE_ID,'pvzID',false)){?>
						tag = $('#<?=COption::GetOptionString(CDeliverySDEK::$MODULE_ID,'pvzID','')?>');
						if(tag.hasClass('sdek_custom_button'))
							tag.click(function(){ IPOLSDEK_pvz.selectPVZ(); });
					<?}else{?>
						var parentNd=$('#ID_DELIVERY_sdek_pickup');
						if(parentNd.closest('td', '#ORDER_FORM').length>0)
							tag = parentNd.closest('td', '#ORDER_FORM').siblings('td:last');
						else
							tag = parentNd.siblings('label').find('.bx_result_price');
					<?}?>

					if(tag.length>0 && tag.html().indexOf(IPOLSDEK_pvz.button)===-1){
						IPOLSDEK_pvz.pvzPrice = (tag.html()) ? tag.html() : false;
						if(!tag.hasClass('sdek_custom_button'))
							tag.html("<div class='sdek_pvzLair'>"+IPOLSDEK_pvz.button+((IPOLSDEK_pvz.pvzPrice)?("<br>"+IPOLSDEK_pvz.pvzPrice):"")+"</div>"); // Тут ставим кнопку "выбрать ПВЗ"
						IPOLSDEK_pvz.pvzLabel = tag;
					}

					if($('#sdek_city').length>0){//обновляем город
						IPOLSDEK_pvz.city   = $('#sdek_city').val();
						IPOLSDEK_pvz.cityID = $('#sdek_cityID').val();
					}else
						IPOLSDEK_pvz.loadProfile();//если нет sdek_city - грузим в первый раз => забираем из адреса ПВЗ и выставляем его

					if($('#sdek_dostav').length>0 && $('#sdek_dostav').val()=='sdek:pickup' && IPOLSDEK_pvz.pvzId)
						IPOLSDEK_pvz.choozePVZ(IPOLSDEK_pvz.pvzId,true);
					
					IPOLSDEK_pvz.curPVZ=false;

					IPOLSDEK_pvz.getPrices();
				},
				
				loadProfile:function(){//загрузка ПВЗ из профиля
					var chznPnkt=false;
					for(var i in IPOLSDEK_pvz.pvzInputs){
						chznPnkt = $('#ORDER_PROP_'+IPOLSDEK_pvz.pvzInputs[i]);
						if(chznPnkt.length>0)
							break;
					}
					if(!chznPnkt || chznPnkt.length==0) return;

					var seltdPVZ = chznPnkt.val();//wat?
					if(seltdPVZ.indexOf('#L')==-1) return;
	
					seltdPVZ=parseInt(seltdPVZ.substr(seltdPVZ.indexOf('#L')+2));

					if(seltdPVZ<=0 || typeof IPOLSDEK_pvz.PVZ[IPOLSDEK_pvz.city.toUpperCase()] == 'undefined' || typeof IPOLSDEK_pvz.PVZ[IPOLSDEK_pvz.city.toUpperCase()][seltdPVZ] == 'undefined')
						return false;
					
					// выбрали ПВЗ
					IPOLSDEK_pvz.pvzAdress=IPOLSDEK_pvz.city+", "+IPOLSDEK_pvz.PVZ[IPOLSDEK_pvz.city.toUpperCase()][seltdPVZ]['address']+" #L"+seltdPVZ;
					//'<span style="font-size:11px">'
					IPOLSDEK_pvz.pvzId = seltdPVZ;
					//Выводим подпись о выбранном ПВЗ рядом с кнопкой "Выбрать ПВЗ"
						var tmpHTML = "<div class='sdek_pvzLair'>"+IPOLSDEK_pvz.button + "<br><span class='sdek_pvzAddr'>" + IPOLSDEK_pvz.PVZ[IPOLSDEK_pvz.city.toUpperCase()][IPOLSDEK_pvz.pvzId].address+"</span>";
					if(IPOLSDEK_pvz.pvzPrice)
						tmpHTML+="<br>"+IPOLSDEK_pvz.pvzPrice;
					tmpHTML+="</div>";

					IPOLSDEK_pvz.pvzLabel.html(tmpHTML);
				},
				
				initCityPVZ: function(){ // грузим пункты самовывоза для выбранного города
					var city = IPOLSDEK_pvz.city;
					var cnt = [];
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
						cnt.push(i);
					}
					IPOLSDEK_pvz.cityPVZHTML();//грузим html PVZ. Два раза пробегаем по массиву, но не критично.
					IPOLSDEK_pvz.multiPVZ = (cnt.length == 1) ? cnt.pop() : false;
				},
				
				cityPVZHTML: function(){ // заполняем список ПВЗ города
					var html = '';
					for(var i in IPOLSDEK_pvz.cityPVZ)
						html+='<p id="PVZ_'+i+'" onclick="IPOLSDEK_pvz.markChosenPVZ(\''+i+'\')" onmouseover="IPOLSDEK_pvz.Y_blinkPVZ(\''+i+'\',true)" onmouseout="IPOLSDEK_pvz.Y_blinkPVZ(\''+i+'\')">'+IPOLSDEK_pvz.paintPVZ(i)+'</p>';
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
				
				//выбрали ПВЗ
				pvzAdress: '',
				pvzId: false,
				choozePVZ: function(pvzId,isAjax){// выбрали ПВЗ
					
					IPOLSDEK_pvz.curPVZ = pvzId;
					
					if(typeof IPOLSDEK_pvz.PVZ[IPOLSDEK_pvz.city][pvzId] == 'undefined')
						return;

					IPOLSDEK_pvz.pvzAdress=IPOLSDEK_pvz.city+", "+IPOLSDEK_pvz.PVZ[IPOLSDEK_pvz.city][pvzId]['Address']+" #S"+pvzId;

					IPOLSDEK_pvz.pvzId = pvzId;

					//Выводим подпись о выбранном ПВЗ рядом с кнопкой "Выбрать ПВЗ"
					var tmpHTML = "<div class='sdek_pvzLair'>"+IPOLSDEK_pvz.button + "<br><span class='sdek_pvzAddr'>" + IPOLSDEK_pvz.PVZ[IPOLSDEK_pvz.city][pvzId]['Address']+"</span>";
					if(IPOLSDEK_pvz.pvzPrice)
						tmpHTML+="<br>"+IPOLSDEK_pvz.pvzPrice;
					tmpHTML+="</div>"
					IPOLSDEK_pvz.pvzLabel.html(tmpHTML);

					var chznPnkt = false;
					if(typeof(KladrJsObj) != 'undefined')KladrJsObj.FuckKladr();
					for(var i in IPOLSDEK_pvz.pvzInputs){
						chznPnkt = $('#ORDER_PROP_'+IPOLSDEK_pvz.pvzInputs[i]);
						if(chznPnkt.length>0){
							chznPnkt.val(IPOLSDEK_pvz.pvzAdress);
							chznPnkt.css('background-color', '#eee').attr('readonly','readonly');
							break;
						}
					}	

					if(typeof isAjax == 'undefined'){ // Перезагружаем форму (с применением новой стоимости доставки)
						if(typeof IPOLSDEK_DeliveryChangeEvent == 'function')
							IPOLSDEK_DeliveryChangeEvent();
						else{
							if(typeof $.prop == 'undefined') // <3 jquery
								$('#ID_DELIVERY_sdek_pickup').attr('checked', 'Y');
							else
								$('#ID_DELIVERY_sdek_pickup').prop('checked', 'Y');
							$('#ID_DELIVERY_sdek_pickup').click();
						}
					}

					IPOLSDEK_pvz.close(true);
				},
				
				close: function(fromChoose){//закрываем функционал
					<?if(COption::GetOptionString(CDeliverySDEK::$MODULE_ID,'autoSelOne','') == 'Y'){?>
						if(IPOLSDEK_pvz.multiPVZ !== false && typeof(fromChoose) == 'undefined')
							IPOLSDEK_pvz.choozePVZ(IPOLSDEK_pvz.multiPVZ);
					<?}?>
					// IPOLSDEK_pvz.backFromDetail();
					if(IPOLSDEK_pvz.scrollPVZ && typeof(IPOLSDEK_pvz.scrollPVZ.data('jsp'))!='undefined')
						IPOLSDEK_pvz.scrollPVZ.data('jsp').destroy();
					$('#SDEK_pvz').css('display','none');
					$('#SDEK_mask').css('display','none');
					IPOLSDEK_pvz.isActive = false;
				},

				selectPVZ: function(){
					if(!IPOLSDEK_pvz.isActive){
						IPOLSDEK_pvz.isActive = true;
						
						var hndlr = $('#SDEK_pvz');

						var left = ($(window).width()>hndlr.width()) ? (($(window).width()-hndlr.width())/2) : 0;

						hndlr.css({
							'display'   : 'block',
							'left'      : left,
						});
						hndlr.css({
							'top'       : ($(window).height()-hndlr.height())/2+$(document).scrollTop(),
						});

						$('#SDEK_mask').css('display','block');

						IPOLSDEK_pvz.initCityPVZ();

						IPOLSDEK_pvz.Y_init();
					}
				},

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
							baloonHTML += "<div><div class='sdek_baloonDiv'><a href='javascript:void(0)' title='"+IPOLSDEK_pvz.cityPVZ[i].Note+"'><?=GetMessage('IPOLSDEK_FRNT_DETAIL')?></a></div><div style='clear:both'></div></div>";
						baloonHTML += "<div><a id='SDEK_button' href='javascript:void(0)' onclick='IPOLSDEK_pvz.choozePVZ(\""+i+"\")'></a></div>";
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
			}
			
			$(document).ready(function(){
				var tmpHTML = $('#SDEK_pvz').html();
				$('#SDEK_pvz').html('');
				$('#SDEK_pvz').attr('id','SDEK_notInUse');
				$('body').append("<div id='SDEK_pvz'>"+tmpHTML+"</div>");
				ymaps.ready(IPOLSDEK_pvz.init);
			});
		</script>
		<?// HTML виджета ?>
		<div id='SDEK_pvz'>
			<div id='SDEK_head'>
				<div id='SDEK_logo'><a href='http://ipolh.com' target='_blank'></a></div>
				<?/*<div id='SDEK_logoPlace'></div>
				<div id='SDEK_separator'></div>
				<div class='SDEK_mark'>
					<strong><?=GetMessage("IPOLSDEK_PICKUP")?></strong> <span id='SDEK_pPrice'></span><br>
					<span id='SDEK_pDate'><?=GetMessage("IPOLSDEK_DELTERM")?>: <?=GetMessage("IPOLSDEK_DAY")?></span>
				</div>
				*/?>
				<div id='SDEK_closer' onclick='IPOLSDEK_pvz.close()'></div>
			</div>
			<div id='SDEK_map'></div>
			<div id='SDEK_info'>
				<div id='SDEK_sign'><?=GetMessage("IPOLSDEK_LABELPVZ")?></div>
				<div id='SDEK_delivInfo'><?=GetMessage("IPOLSDEK_DELIVERY")?><span id='SDEK_pPrice'></span>&nbsp;&nbsp;&nbsp;<span id='SDEK_pDate'></span></div>
				<div>
					<div id='SDEK_wrapper'></div>
				</div>
				<div id='SDEK_ten'></div>
			</div>
		</div>