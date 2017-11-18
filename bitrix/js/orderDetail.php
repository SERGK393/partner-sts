<?
global $orderId;
$orderId = $_REQUEST['ID'];
$orderinfo=CSaleOrder::getById($orderId);//параметры заказа

if(
	COption::GetOptionString(self::$MODULE_ID,'showInOrders','Y') == 'N' &&
	strpos($orderinfo['DELIVERY_ID'],'sdek:') !== 0
)
	return;

// NW
global $IPOLSDEK_isLoaded;
$IPOLSDEK_isLoaded = false;
$SDEK_ID  = false;
if($ordrVals=self::GetByOI($orderId)){ // получаем параметры заявки из БД, если они есть
	$status=$ordrVals['STATUS'];
	$message=array();
	if($ordrVals['MESSAGE'])
		$message=unserialize($ordrVals['MESSAGE']);
	foreach($message as $key => $sign)
		if(in_array($key,array('service','location','line','house','flat','PVZ','name','phone','email','comment','number')))
			$message[$key]='<br><span style="color:#FF4040">'.$sign.'</span>';
		else{
			$message['troubles'].='<span style="color:#FF4040">'.$sign.' ('.$key.')</span><br>';
		}
	$SDEK_ID = $ordrVals['SDEK_ID'];
	$MESS_ID = $ordrVals['MESS_ID'];
	$ordrVals=unserialize($ordrVals['PARAMS']); // массив значений заявки, если не задан - заполняется по умолчанию из параметров, указанных в опциях и покупателем
	$cityName = sqlSdekCity::getBySId($ordrVals['location']);
	if($cityName)
		$cityName = $cityName['NAME'];
	else
		$cityName = "ERROR";
	$IPOLSDEK_isLoaded = true;
}
// END NW
if(!$status)
	$status='NEW';

//собираем из свойств заказа параметры для заявки
$arChk=array();
$PVZaddr = false;//определяем пвз
$PVZprop = COption::GetOptionString(self::$MODULE_ID,'pvzPicker',false);

$orderProps=CSaleOrderPropsValue::GetOrderProps($orderId);
$orderPropsMas = array();
while($orderProp=$orderProps->Fetch()){
	$orderPropsMas[$orderProp['CODE']] = $orderProp['VALUE'];
	if($orderProp['CODE'] == $PVZprop)
		$PVZprop = $orderProp['VALUE'];
}
$orignCityId = false; // город-исходник из заказа, для которого рассчитывается доставка - только он есть в таблице соответствий

if(!$IPOLSDEK_isLoaded && IsModuleInstalled('ipol.kladr')){ // ибо кладр = хорошо
	$propCode = COption::GetOptionString(self::$MODULE_ID,'address','');
	if($propCode && $orderPropsMas[$propCode]){
		$containment = explode(",",$orderPropsMas[$propCode]);
		if(is_numeric($containment[0])) $start = 2;
		else $start = 1;		
		if($containment[$start]){ $orderPropsMas['address'] = ''; $ordrVals['line'] = trim($containment[$start]);}
		if($containment[($start+1)]){ $containment[($start+1)] = trim($containment[($start+1)]); $ordrVals['house'] = trim(substr($containment[($start+1)],strpos($containment[($start+1)]," ")));}
		if($containment[($start+2)]){ $containment[($start+2)] = trim($containment[($start+2)]); $ordrVals['flat']  = trim(substr($containment[($start+2)],strpos($containment[($start+2)]," ")));}
	}
}

foreach(array('location','name','email','phone','address','street','house','flat') as $prop){
	if(!$ordrVals[$prop] || $prop=='location'){
		$propCode = COption::GetOptionString(self::$MODULE_ID,$prop,'');
		if($prop!='location')
			$ordrVals[$prop] = ($propCode)?$orderPropsMas[$propCode]:false;
		elseif($propCode){
			$orderPropsMas[$propCode] = sdekHelper::getNormalCity($orderPropsMas[$propCode]);
			$src = CDeliverySDEK::getCity($orderPropsMas[$propCode]);
			$orignCityId = $src;
			if(!$ordrVals[$prop]){
				$ordrVals[$prop]=CDeliverySDEK::getCity($orderPropsMas[$propCode]);
				$cityName = sdekHelper::getOrderCity($orderId);
			}
		}
	}
}

if(!$ordrVals['comment'])
	$ordrVals['comment'] = $orderinfo['COMMENTS'];

// Проверка города
$errCities = sdekHelper::getErrCities();
$multiCity = false;
$multiCityS = false;
if(array_key_exists($orderPropsMas['LOCATION'],$errCities['many'])){
	$multiCity = '&nbsp;&nbsp;<a href="#" class="PropWarning" onclick="return ipolSDEK_popup_virt(\'pop-multiCity\',this);"></a>	
	<div id="pop-multiCity" class="b-popup" style="display: none; ">
	<div class="pop-text">'.GetMessage("IPOLSDEK_SOD_MANYCITY").'<div class="close" onclick="$(this).closest(\'.b-popup\').hide();"></div>
</div>';
$multiCityS = "<select id='IPOLSDEK_ms' onchange='IPOLSDEK_onMSChange(\$(this))'>
	<option value='".$orignCityId."' ".(($ordrVals['location'] == $orignCityId)?"selected":"").">".$errCities['many'][$orderPropsMas['LOCATION']]['takenLbl']."</option>";
	foreach($errCities['many'][$orderPropsMas['LOCATION']]['sdekCity'] as $sdekId => $arAnalog)
		$multiCityS .= "<option value='".$sdekId."' ".(($ordrVals['location'] == $sdekId)?"selected":"").">".$arAnalog['region'].", ".$arAnalog['name']."</option>";
$multiCityS .= "</select>";
}

$payment = sqlSdekCity::getCityPM($ordrVals['location']); // платежная система

// NW
/* DATE
if($ordrVals['issue']){ // дата доставки
	if(preg_match('/([\d]{4})-([\d]{2})-([\d]{2})/',$ordrVals['issue'],$matches))
		$ordrVals['issue']=$matches[3].".".$matches[2].".".$matches[1];
	else
		$ordrVals['issue']=false;
}
*/
// END NW

CDeliverySDEK::forceSetGoods($orderId);
$naturalGabs = array(
	"D_W" => CDeliverySDEK::$goods['D_W'],
	"D_L" => CDeliverySDEK::$goods['D_L'],
	"D_H" => CDeliverySDEK::$goods['D_H'],
	"W" => CDeliverySDEK::$goods['W']
);

// Габариты
if(!$IPOLSDEK_isLoaded){
	CDeliverySDEK::forceSetGoods($orderId);
	$ordrVals['GABS'] = $naturalGabs;
}

//ТАРИФЫ
$arList = CDeliverySDEK::getListFile();
$arModdedList = CDeliverySDEK::wegihtPVZ($ordrVals['GABS']["W"] * 1000);
$strOfCodes='';

$arTarif = sdekdriver::getExtraTarifs();
$arTarifMode = unserialize(COption::GetOptionString(self::$MODULE_ID,"tarifs","a:{}"));
$hasSelected = false;
if(!$ordrVals['service'] && $orderPropsMas['IPOLSDEK_CNTDTARIF'])
	$ordrVals['service'] = $orderPropsMas['IPOLSDEK_CNTDTARIF'];

foreach($arTarif as $code => $arSign){//тариф
	if($arSign['SHOW'] == 'Y' || $code == $ordrVals['service']){
		$selected = '';
		if(!$hasSelected && $code == $ordrVals['service'])
			$selected='selected';
		elseif(!$hasSelected && !$ordrVals['service']){ //пытаемся угадать тариф
			if(array_key_exists($cityName,$arList) && strpos($ordrVals['address'],"#S") && $code == 136)
				$selected = 'selected';
			elseif($code == 137)
				$selected = 'selected';
		}

		if($selected)
			$hasSelected = true;

		$highLight = '';
		if($code == 138 || $code == 139)
			$highLight = "style='background-color:#F08192'";
		
		$strOfCodes.="<option $highLight value='$code' $selected>".$arSign['NAME']."</option>";
	}
}
// безнал
if(!$IPOLSDEK_isLoaded){
	//PAY_SYSTEM_ID
	$paySys = unserialize(COption::getOptionString(self::$MODULE_ID,'paySystems',''));
	if(in_array($orderinfo['PAY_SYSTEM_ID'],$paySys)){
		$ordrVals['isBeznal'] = 'Y';
	}
}
$badPay = false;
if($orderinfo['PAYED'] != 'Y')
	$badPay = true;

// ПВЗ
$strOfPSV='';
if(!$ordrVals['PVZ'])
	$ordrVals['PVZ'] = (strpos($PVZprop,"#S"))?substr($PVZprop,strpos($PVZprop,"#S")+2):false;
$arBPVZ = "{";
foreach($arList[$cityName] as $code => $punkts){
	if(!array_key_exists($code,$arModdedList[$cityName]))
		$arBPVZ .= $code.":true,";
	$selected = ($ordrVals['PVZ'] == $code) ? "selected" : "";
	$strOfPSV.="<option $selected value='".$code."'>".$punkts['Name']." (".$code.")"."</option>";
}
$arBPVZ .= "}";
//Доп. опции
$exOpts = sdekdriver::getExtraOptions();
if($IPOLSDEK_isLoaded)
	foreach($exOpts as $code => $vals)
		if($ordrVals['AS'][$code] == 'Y')
			$exOpts[$code]['DEF'] = 'Y';
		else
			$exOpts[$code]['DEF'] = 'N';

$satBut='';

// Вызов курьера
$allowCourier = (COption::GetOptionString(self::$MODULE_ID,'allowSenders','N') == 'Y');
if(!$ordrVals['courierCity'])
	$ordrVals['courierCity'] = sqlSdekCity::getByBId(sdekHelper::getNormalCity(COption::GetOptionString('sale','location',false)));
else
	$ordrVals['courierCity'] = sqlSdekCity::getBySId($ordrVals['courierCity']);

$citiesSender = sqlSdekCity::select();
$IPOLSDEK_sC = '';
$tmpCts = array();
while($element=$citiesSender->Fetch()){
	$IPOLSDEK_sC .= "{label:'{$element['NAME']} ({$element['REGION']})',value:'{$element['SDEK_ID']}'},";
	$tmpCts[$element['SDEK_ID']] = $element['NAME']." (".$element['REGION'].')';
}
$svdCouriers = unserialize(COption::GetOptionString(self::$MODULE_ID,'senders','a:{}'));

$IPOLSDEK_svdC = "";
foreach($svdCouriers as $ind => $vals){
	$IPOLSDEK_svdC .= $ind.":{";
	foreach($vals as $name => $value)
		$IPOLSDEK_svdC .= $name.": '".$value."',";
	$IPOLSDEK_svdC .= "cityName: '".$tmpCts[$vals['courierCity']]."',";
	$IPOLSDEK_svdC .= "},";
}

CJSCore::Init(array("jquery"));
?>
<?=sdekdriver::getModuleExt('courierTimeCheck')?>
<?=sdekdriver::getModuleExt('packController')?>
<link href="/bitrix/js/<?=self::$MODULE_ID?>/jquery-ui.css?<?=mktime()?>" type="text/css"  rel="stylesheet" />
<link href="/bitrix/js/<?=self::$MODULE_ID?>/jquery-ui.structure.css?<?=mktime()?>" type="text/css"  rel="stylesheet" />

<script src='/bitrix/js/<?=self::$MODULE_ID?>/jquery-ui.js?<?=mktime()?>' type='text/javascript'></script>
<style type='text/css'>
	.PropWarning{
		background: url('/bitrix/images/<?=self::$MODULE_ID?>/trouble.png') no-repeat transparent;
		background-size: contain;
		display: inline-block;
		height: 12px;
		position: relative;
		width: 12px;
	}
	.PropWarning:hover{
		background: url('/bitrix/images/<?=self::$MODULE_ID?>/trouble.png') no-repeat transparent !important;
		background-size: contain !important;
	}
	.PropHint { 
		background: url('/bitrix/images/<?=self::$MODULE_ID?>/hint.gif') no-repeat transparent;
		display: inline-block;
		height: 12px;
		position: relative;
		width: 12px;
	}
	.PropHint:hover{background: url('/bitrix/images/<?=self::$MODULE_ID?>/hint.gif') no-repeat transparent !important;}
	.b-popup { 
		background-color: #FEFEFE;
		border: 1px solid #9A9B9B;
		box-shadow: 0px 0px 10px #B9B9B9;
		display: none;
		font-size: 12px;
		padding: 19px 13px 15px;
		position: absolute;
		top: 38px;
		width: 300px;
		z-index: 12;
	}
	.b-popup .pop-text { 
		margin-bottom: 10px;
		color:#000;
	}
	.pop-text i {color:#AC12B1;}
	.b-popup .close { 
		background: url('/bitrix/images/<?=self::$MODULE_ID?>/popup_close.gif') no-repeat transparent;
		cursor: pointer;
		height: 10px;
		position: absolute;
		right: 4px;
		top: 4px;
		width: 10px;
	}
	#IPOLSDEK_wndOrder{
		width: 100%;
	}
	#IPOLSDEK_allTarifs{
		border-collapse: collapse;
		width: 100%;
	}
	#IPOLSDEK_allTarifs td{
		border: 1px dotted black;
		padding: 3px;
	}
	#IPOLSDEK_tarifWarning{
		display:none;
	}
	#IPOLSDEK_tarifWarning span{
		font-size: 10px;
	}
	#IPOLSDEK_service{
		max-width:250px;
	}
	.IPOLSDEK_gabInput{
		width: 28px;
	}
	#IPOLSDEK_gabsPlace{
		min-height: 27px;
	}
</style>
<script>
<?=sdekdriver::getModuleExt('mask_input')?>
var IPOLSDEK_ordrId="<?=$orderId?>";
var IPOLSDEK_status="<?=$status?>";

// Города-отправители
var IPOLSDEK_senderCities = [<?=$IPOLSDEK_sC?>];

$(document).ready(function(){
	$('.adm-detail-toolbar').find('.adm-detail-toolbar-right').prepend("<a href='javascript:void(0)' onclick='IPOLSDEK_editLogisticsProp()' class='adm-btn'><?=GetMessage('IPOLSDEK_JSC_SOD_BTNAME')?></a>");
	var btn = $('[onclick="IPOLSDEK_editLogisticsProp()"]');
	switch(IPOLSDEK_status){
		case 'NEW'    : break;
		case 'ERROR'  : btn.css('color','#F13939'); break;
		default       : btn.css('color','#3A9640'); break;
	}
});

var IPOLSDEK_wndOrderProp=false;
var IPOLSDEK_badPVZ = <?=$arBPVZ?>;
function IPOLSDEK_editLogisticsProp(){//окошко показываем
	var savButStat='';
	if(IPOLSDEK_status!='ERROR' && IPOLSDEK_status!='NEW')
		savButStat='style="display:none"';
	var delButStat='';
	if(IPOLSDEK_status !='OK' && IPOLSDEK_status !='ERROR' && IPOLSDEK_status !='DELETD' )
		delButStat='style="display:none"';
	var prntButStat='style="display:none"';
	if(IPOLSDEK_status =='OK')
		prntButStat='';
		
	if(!IPOLSDEK_wndOrderProp){
		var html=$('#IPOLSDEK_wndOrder').parent().html();
		$('#IPOLSDEK_wndOrder').replaceWith('');
		IPOLSDEK_wndOrderProp = new BX.CDialog({
			title: "<?=GetMessage('IPOLSDEK_JSC_SOD_WNDTITLE')?>",
			content: html,
			icon: 'head-block',
			resizable: true,
			draggable: true,
			height: '500',
			width: '475',
			buttons: [
				//'<input type=\"button\" value=\"<?=GetMessage('IPOLSDEK_JSC_SOD_SAVE')?>\" '+savButStat+' onclick=\"IPOLSDEK_changeParams(\'updtOrder\')\"/>', // сохранить
				'<input type=\"button\" value=\"<?=GetMessage('IPOLSDEK_JSC_SOD_SAVESEND')?>\"  '+savButStat+'onclick=\"IPOLSDEK_changeParams(\'saveAndSend\')\"/>', // сохранить и отправить
				'<input type=\"button\" value=\"<?=GetMessage('IPOLSDEK_JSC_SOD_ALLTARIFS')?>\"  '+savButStat+'onclick=\"IPOLSDEK_ShowAllTarifs()\"/>', // все тарифы
				'<input type=\"button\" value=\"<?=GetMessage('IPOLSDEK_JSC_SOD_DELETE')?>\" '+delButStat+' onclick=\"IPOLSDEK_delReq()\"/>', // удалить
				'<input type=\"button\" id=\"IPOLMSHP_PRINT\" value=\"<?=GetMessage('IPOLSDEK_JSC_SOD_PRNTSH')?>\" '+prntButStat+' onclick="IPOLSDEK_print(\''+IPOLSDEK_ordrId+'\'); return false;"/>',
				'<input type=\"button\" value=\"<?=GetMessage('IPOLSDEK_JS_SOD_Packs')?>\"  onclick="IPOLSDEK_packHandler.open(); return false;"/>', // места				// печать штрихкода
				<?if($SDEK_ID){?>'<a href="http://www.edostavka.ru/track.html?order_id=<?=$SDEK_ID?>" target="_blank"><?=GetMessage('IPOLSDEK_JSC_SOD_FOLLOW')?></a>'<?}?> // отслеживание
			]
		});
		$('#IPOLSDEK_courierTimeBeg').mask("29:59");
		$('#IPOLSDEK_courierTimeEnd').mask("29:59");
		// $('#IPOLSDEK_courierPhone').mask("99999999999");

		$( "#IPOLSDEK_cSSelector" ).autocomplete({
		  source: IPOLSDEK_senderCities,
		  select: function(ev,ui){IPOLSDEK_courierHandler.changeCity(2,ui);}
		});
	}
	IPOLSDEK_onCodeChange($('#IPOLSDEK_service'),true);
	IPOLSDEK_checkNal();
	IPOLSDEK_courierHandler.handleCourier();
	onChangeConditoinsMakeReq(true);
	IPOLSDEK_wndOrderProp.Show();
}
function IPOLSDEK_onCodeChange(wat,ifDef){//изменилась услуга: проверяем, не самовывоз ли, скрываем/показываем соответствующие поля доставки
	if(wat.val() == 138 || wat.val() == 139) $('#IPOLSDEK_tarifWarning').css('display','table-row');
	else $('#IPOLSDEK_tarifWarning').css('display','');

	if(IPOLSDEK_isPVZ(wat.val())){//самовывоз
		$('#IPOLSDEK_wndOrder').find('.IPOLSDEK_notSV').css('display','none');
		$('#IPOLSDEK_timeFrom').closest('tr').css('display','none');
		$('#IPOLSDEK_wndOrder').find('.IPOLSDEK_SV').css('display','');
	}else{
		$('#IPOLSDEK_wndOrder').find('.IPOLSDEK_notSV').css('display','');
		$('#IPOLSDEK_timeFrom').closest('tr').css('display','');
		$('#IPOLSDEK_wndOrder').find('.IPOLSDEK_SV').css('display','none');
	}

	<?if($allowCourier){?>
	if(IPOLSDEK_isToDoor(wat.val())){
		$('#IPOLSDEK_courierHeader').css('display','');
		if(IPOLSDEK_courierHandler.requestCourier)
			IPOLSDEK_courierHandler.handleCourier();		
	}else{
		$('#IPOLSDEK_courierHeader').css('display','none');
		if(IPOLSDEK_courierHandler.requestCourier && typeof(ifDef) == 'undefined')
			IPOLSDEK_courierHandler.handleCourier();
	}
	<?}else{?>
	$('#IPOLSDEK_courierHeader').css('display','none');
	<?}?>
	
	if(typeof(ifDef) == 'undefined')
		onChangeConditoinsMakeReq();
	IPOLSDEK_onPVZChange();
}
function IPOLSDEK_onMSChange(wat){
	$('#IPOLSDEK_location').val(wat.val());
	onChangeConditoinsMakeReq();
}

function IPOLSDEK_onPVZChange(wat){
	if(typeof(wat) == 'undefined')
		wat = $('#IPOLSDEK_PVZ');
	if(typeof(IPOLSDEK_badPVZ[wat.val()]) != 'undefined')
		$('#IPOLSDEK_badPVZ').css('display','inline');
	else
		$('#IPOLSDEK_badPVZ').css('display','none');
}

function onChangeConditoinsMakeReq(isNoAlert){
	var reqParams = IPOLSDEK_getInputsCountDeliv();

	if(typeof(reqParams) != 'object' || typeof(reqParams.cityTo) == 'undefined'){
		alert(reqParams);
		return false;
	}

	$.ajax({
		type : 'POST',
		url  : "/bitrix/js/<?=self::$MODULE_ID?>/ajax.php",
		data : reqParams,
		dataType: 'json',
		success: function(data){
			if(typeof data.success != 'undefined'){
				var dayLbl = data.termMin + "-" + data.termMax + " <?=GetMessage("IPOLSDEK_JS_SOD_HD_DAY")?>";
				if(data.termMin == data.termMax) dayLbl = data.termMax + " <?=GetMessage("IPOLSDEK_JS_SOD_HD_DAY")?>";
				var text = "<?=GetMessage("IPOLSDEK_JSC_SOD_NEWCONDITIONS_1")?>" + dayLbl + "<?=GetMessage("IPOLSDEK_JSC_SOD_NEWCONDITIONS_2")?>" + data.price + " <?=GetMessage("IPOLSDEK_JSC_SOD_RUB")?>";
				$('#IPOLSDEK_newPrDel').html(data.price);
			}else{
				var text = '';
				for(var i in data)
					text += data[i]+" ("+i+") \n";
				$('#IPOLSDEK_newPrDel').html('<?=GetMessage("IPOLSDEK_JS_SOD_noDost")?>');
			}
			if(typeof(isNoAlert) == 'undefined')
				alert(text);
		}
	});
}

function IPOLSDEK_getInputsCountDeliv(params){
	var city = $('#IPOLSDEK_location').val();
	if(!city)
		return '<?=GetMessage("IPOLSDEK_JSC_SOD_NOCITY")?>';

	var tarif = $('#IPOLSDEK_service').val();
	if(!tarif)
		return '<?=GetMessage("IPOLSDEK_JSC_SOD_NOTARIF")?>';

	var GABS = {
		'D_L' : $('#IPOLSDEK_GABS_D_L').val(),
		'D_W' : $('#IPOLSDEK_GABS_D_W').val(),
		'D_H' : $('#IPOLSDEK_GABS_D_H').val(),
		'W'   : $('#IPOLSDEK_GABS_W').val(),
	};
	var packs = $('#IPOLSDEK_PLACES').val();
	if(packs)
		packs = JSON.parse(packs);

	if(typeof(params) == 'undefined')
		params = {};

	return {
		action   : 'extCountDeliv',
		orderId  : (params.orderId) ? params.orderId : IPOLSDEK_ordrId,
		cityTo   : (params.cityTo) ? params.cityTo : city,
		cityFrom : (params.cityFrom) ? params.cityFrom : ((IPOLSDEK_courierHandler.requestCourier) ? $('#IPOLSDEK_courierCity').val() : 0),
		tarif    : (params.tarif) ? params.tarif : tarif,
		GABS     : (params.GABS) ? params.GABS : GABS,
		packs    : (params.packs) ? params.packs : packs,		
	};
}

function IPOLSDEK_getInputs(){
	var dO={};

	var isPVZ = IPOLSDEK_isPVZ($('#IPOLSDEK_service').val());
	var isCourierCall = (IPOLSDEK_isToDoor($('#IPOLSDEK_service').val()) && IPOLSDEK_courierHandler.requestCourier);

	var reqFields = {
		'service'   	 : {need: true},
		'realSeller'	 : {need: false},
		'location'  	 : {need: true},
		'name'     		 : {need: true},
		'email'     	 : {need: false},
		'phone'     	 : {need: true},
		'comment'    	 : {need: false},
		'line'      	 : {need: true,check: (!isPVZ)},
		'house'      	 : {need: true,check: (!isPVZ)},
		'flat'       	 : {need: true,check: (!isPVZ)},
		'PVZ'            : {need: true,check: isPVZ},
		'courierDate'    : {need: true,check: isCourierCall},
		'courierTimeBeg' : {need: true,check: isCourierCall},
		'courierTimeEnd' : {need: true,check: isCourierCall},
		'courierCity' 	 : {need: true,check: isCourierCall},
		'courierStreet'  : {need: true,check: isCourierCall},
		'courierHouse' 	 : {need: true,check: isCourierCall},
		'courierFlat' 	 : {need: true,check: isCourierCall},
		'courierPhone' 	 : {need: true,check: isCourierCall},
		'courierName'	 : {need: true,check: isCourierCall},
		'courierComment' : {need: false,check: isCourierCall},
	};

	for(var i in reqFields){
		if(typeof(reqFields[i].need) == 'undefined') continue;
		if(typeof(reqFields[i].check) != 'undefined' && !reqFields[i].check) continue;
		dO[i]=$('#IPOLSDEK_'+i).val();
		if(!dO[i] && reqFields[i].need)
			return $('#IPOLSDEK_'+i).closest('tr').children('td').html();
	}

	dO['AS'] = {};
	$('[id^="IPOLSDEK_AS_"]').each(function(){
		if($(this).attr('checked'))
			dO['AS'][$(this).val()]='Y';
	});
	
	var packs = $('#IPOLSDEK_PLACES').val();
	if(packs){
		packs = JSON.parse(packs);
		dO['packs'] = packs;
	}

	$('[id^="IPOLSDEK_GABS_"]').each(function(){
		if(typeof dO['GABS'] == 'undefined') dO['GABS'] = {};
		dO['GABS'][$(this).attr('id').substr(14)]=$(this).val();
	});
	if($('#IPOLSDEK_isBeznal').attr('checked'))
		dO['isBeznal']='Y';

	return dO;
}

// КНОПКИ

function IPOLSDEK_changeParams(mode)//кнопочка "сохранить"
{
	var dataObject=IPOLSDEK_getInputs();
	if(typeof dataObject != 'object'){if(dataObject)alert('<?=GetMessage('IPOLSDEK_JSC_SOD_ZAPOLNI')?> "'+dataObject+'"');return;}
	dataObject['action']=mode;
	dataObject['orderId']=IPOLSDEK_ordrId;

	$.post(
		"/bitrix/js/<?=sdekdriver::$MODULE_ID?>/ajax.php",
		dataObject,
		function(data){
			if(mode=='saveAndSend')
				$('[onclick^="IPOLSDEK_changeParams("]').each(function(){$(this).css('display','none')});
			alert(data);
			IPOLSDEK_wndOrderProp.Close();
		}
	);
}

function IPOLSDEK_delReq(){
	if(IPOLSDEK_status == 'NEW' || IPOLSDEK_status == 'ERROR' || IPOLSDEK_status == 'DELETE'){
		if(confirm("<?=GetMessage('IPOLSDEK_JSC_SOD_IFDELETE')?>"))
			$.post(
				"/bitrix/js/<?=self::$MODULE_ID?>/ajax.php",
				{action:'delReqOD',oid:IPOLSDEK_ordrId},
				function(data){
					alert(data);
					document.location.reload();
				}
			);
	}else{
		if(IPOLSDEK_status == 'OK'){
			if(confirm("<?=GetMessage('IPOLSDEK_JSC_SOD_IFKILL')?>"))
				$.post(
					"/bitrix/js/<?=self::$MODULE_ID?>/ajax.php",
					{action:'killReqOD',oid:IPOLSDEK_ordrId},
					function(data){
						if(data.indexOf('GD:')===0){
							alert(data.substr(3));
							document.location.reload();
						}
						else
							alert(data);
					}
				);
		}
	}
}

function IPOLSDEK_print(oId){
	$('#IPOLMSHP_PRINT').attr('disabled','true');
	$('#IPOLMSHP_PRINT').val('<?=GetMessage("IPOLSDEK_JSC_SOD_LOADING")?>');
	$.ajax({
		url  : "/bitrix/js/<?=self::$MODULE_ID?>/ajax.php",
		type : 'POST',
		data : {
			action : 'printOrderInvoice',
			oId    : oId
		},
		dataType : 'json',
		success  : function(data){
			$('#IPOLMSHP_PRINT').removeAttr('disabled');
			$('#IPOLMSHP_PRINT').val('<?=GetMessage("IPOLSDEK_JSC_SOD_PRNTSH")?>');
			if(data.result == 'ok')
				window.open('/upload/<?=self::$MODULE_ID?>/'+data.file);
			else
				alert(data.error);
		}
	});
}
function IPOLSDEK_isPVZ(val){
	var arPVZ = [<?=sdekHelper::getTarifList(array('type'=>'pickup','answer'=>'string','fSkipCheckBlocks'=>true))?>];
	for(var i = 0; i < arPVZ.length; i++) 
        if(arPVZ[i] == val) return true;
	return false;
}

function IPOLSDEK_isToDoor(val){
	var dT = [<?=sdekHelper::getDoorTarifs(true)?>];
	for(var i = 0; i < dT.length; i++) 
        if(dT[i] == val) return true;
	return false;
}

function ipolSDEK_popup_virt(code, info){ // Вспл. подсказки 
	var offset = $(info).position().top;
	var obj;
	if(code == 'next') 	obj = $(info).next();
	else  				obj = $('#'+code);

	var LEFT = (parseInt($('#IPOLSDEK_wndOrder').width())-parseInt(obj.width()))/2;
	obj.css({
		top: (offset+15)+'px',
		left: LEFT,
		display: 'block'
	});	
	return false;
}

var IPOLSDEK_wndTarifs = false;
function IPOLSDEK_ShowAllTarifs(){//окошко показываем
	if(!IPOLSDEK_wndTarifs){
		
		IPOLSDEK_wndTarifs = new BX.CDialog({
			title: "<?=GetMessage('IPOLSDEK_JSC_SOD_ALLTARIFS')?>",
			content: "<table id='IPOLSDEK_allTarifs'><tr><td style='text-align:center;border:none;padding-top: 100px;'><img src='/bitrix/images/<?=self::$MODULE_ID?>/ajax.gif'></td></tr></table>",
			icon: 'head-block',
			resizable: true,
			draggable: true,
			height: '300',
			width: '510',
			buttons: []
		});
	}
	var packs = $('#IPOLSDEK_PLACES').val();
	if(packs)
		packs = JSON.parse(packs);

	var dataReq = {
			action   : 'htmlTaritfList',
			orderId  : IPOLSDEK_ordrId,
			cityTo   : $('#IPOLSDEK_location').val(),
			cityFrom : (IPOLSDEK_courierHandler.requestCourier) ? $('#IPOLSDEK_courierCity').val() : 0,
			gabs	 : {
				'D_L' : $('#IPOLSDEK_GABS_D_L').val(),
				'D_W' : $('#IPOLSDEK_GABS_D_W').val(),
				'D_H' : $('#IPOLSDEK_GABS_D_H').val(),
				'W'   : $('#IPOLSDEK_GABS_W').val(),
			},
			packs: packs,
		};
	IPOLSDEK_wndTarifs.Show();
	$.post(					
		"/bitrix/js/<?=self::$MODULE_ID?>/ajax.php",dataReq,
		function(data){
			$('#IPOLSDEK_allTarifs').replaceWith(data);
		}
	);
}
function IPOLSDEK_SATclick(wat){
	$('#IPOLSDEK_service').val(wat);
	IPOLSDEK_onCodeChange($('#IPOLSDEK_service'),true);
	IPOLSDEK_wndTarifs.Close();
}

function IPOLSDEK_checkNal(){
	<?if($badPay){?>
		if($('#IPOLSDEK_isBeznal').attr('checked'))
			$('#IPOLSDEK_notPayed').css('display','inline');
		else
			$('#IPOLSDEK_notPayed').css('display','none');
	<?}?>
}

// заказ курьера
IPOLSDEK_courierHandler = {
	requestCourier : <?if(!$allowCourier) echo 'true';
						elseif($ordrVals['courierDate']) echo 'false';
						else echo 'true';
					?>, // проверка в php! Обратное, так как при загрузке работает

	handleCourier: function(){
		if(IPOLSDEK_courierHandler.requestCourier){
			$("[onclick='IPOLSDEK_courierHandler.handleCourier()']").html('<?=GetMessage('IPOLSDEK_JS_SOD_HD_SHOWCOURIER')?>');
			$('.IPOLSDEK_courierInfo').css('display','none');
			IPOLSDEK_courierHandler.requestCourier = false;
		}else{
			<?if(COption::GetOptionString(self::$MODULE_ID,'allowSenders','N') == 'N'){?> return; <?}?>
			if($('#IPOLSDEK_courierHeader').css('display')!='none'){
				$("[onclick='IPOLSDEK_courierHandler.handleCourier()']").html('<?=GetMessage('IPOLSDEK_JS_SOD_HD_NOSHOWCOURIER')?>');
				$('.IPOLSDEK_courierInfo').css('display','');
				IPOLSDEK_courierHandler.requestCourier = true;
			}
		}
	},

	changeCity: function(mode,val){
		if(mode == 1){
			$('#IPOLSDEK_cSSelector').parent().css('display','block');
			$('#IPOLSDEK_cSLabel').parent().css('display','none');
			$('#IPOLSDEK_cSSelector').val('');
		}else{
			$('#IPOLSDEK_cSLabel').parent().css('display','block');
			$('#IPOLSDEK_cSSelector').parent().css('display','none');
			$('#IPOLSDEK_cSLabel').html(val.item.label);
			$('#IPOLSDEK_courierCity').val(val.item.value);
			onChangeConditoinsMakeReq();
		}		
	},

	svdCrrs: {<?=$IPOLSDEK_svdC?>},

	selectProfile: function(val){
		var Vals = '';
		if(val === '')
			Vals = {senderName:"",cityName:"",courierCity:'',courierStreet:'',courierHouse:'',courierFlat:'',courierPhone:'',courierName:''};
		else
			Vals = IPOLSDEK_courierHandler.svdCrrs[val];
		for(var i in Vals)
			$('#IPOLSDEK_'+i).val(Vals[i]);
		$('#IPOLSDEK_cSLabel').html(Vals.cityName);
		IPOLSDEK_courierHandler.onTimeChange();
		onChangeConditoinsMakeReq();
	},

	onDateChange: function(){
		var curDate = new Date('<?=date('Y')?>','<?=(date('m')-1)?>','<?=date('d')?>');
		var selDate = $('#IPOLSDEK_courierDate').val().split('.');
		selDate = new Date(selDate[2],selDate[1]-1,selDate[0]);

		if(selDate < curDate)	
			$('#IPOLSDEK_courierDateError').css('display','table-cell');
		else
			$('#IPOLSDEK_courierDateError').css('display','none');
		if(selDate.valueOf() == curDate.valueOf() && !$('#IPOLSDEK_courierTimeBeg').val()){
			var cT = new Date();
			var aT = new Date(cT + 900000); // +15 min
			if(aT.getUTCHours() < 15){
				$('#IPOLSDEK_courierTimeBeg').val(aT.getHours()+":"+aT.getMinutes());
				aT = new Date(cT + 11700000); // +3h 15m
				$('#IPOLSDEK_courierTimeEnd').val(aT.getHours()+":"+aT.getMinutes());
			}
		}

		IPOLSDEK_courierHandler.onTimeChange();
	},

	onTimeChange: function(){
		var start = $('#IPOLSDEK_courierTimeBeg').val();
		var end = $('#IPOLSDEK_courierTimeEnd').val();
		if(start || end){
			var check = IPOLSDEK_courierTimeCheck(start,end,$('#IPOLSDEK_courierDate').val());
			if(check === true){
				$('.IPOLSDEK_badInput').removeClass('IPOLSDEK_badInput');
				$('#IPOLSDEK_courierTimeOK').val(true);
				$('#IPOLSDEK_courierTimeError').html('');
			}else{
				if(check.error == 'start' || check.error == 'both')
					$('#IPOLSDEK_courierTimeBeg').addClass('IPOLSDEK_badInput');
				if(check.error == 'end' || check.error == 'both')
					$('#IPOLSDEK_courierTimeEnd').addClass('IPOLSDEK_badInput');
				$('#IPOLSDEK_courierTimeOK').val(false);
				$('#IPOLSDEK_courierTimeError').html(check.text);
			}
		}else{
			$('.IPOLSDEK_badInput').removeClass('IPOLSDEK_badInput');
			$('#IPOLSDEK_courierTimeOK').val(true);
			$('#IPOLSDEK_courierTimeError').html('');
		}
	}
};

// управление сервисными свойствами
function IPOLSDEK_serverShow(){
	$(".IPOLSDEK_detOrder").css("display","");
	IPOLSDEK_gabsHandler.handleLabel();
}

// Управление габаритами упаковки
var IPOLSDEK_gabsHandler = {
	//кнопка "Изменить"
	changeGabs: function(){
		var GABS = {
			D_W: $('#IPOLSDEK_GABS_D_W').val() * 10,
			D_L: $('#IPOLSDEK_GABS_D_L').val() * 10,
			D_H: $('#IPOLSDEK_GABS_D_H').val() * 10
		};
		var htmlCG  = "<input type='text' class='IPOLSDEK_gabInput' id='IPOLSDEK_GABS_D_W_new' value='"+GABS.D_W+"'> <?=GetMessage("IPOLSDEK_mm")?>&nbsp;x&nbsp;";
			htmlCG += "<input type='text' class='IPOLSDEK_gabInput' id='IPOLSDEK_GABS_D_L_new' value='"+GABS.D_L+"'> <?=GetMessage("IPOLSDEK_mm")?>&nbsp;x&nbsp;";
			htmlCG += "<input type='text' class='IPOLSDEK_gabInput' id='IPOLSDEK_GABS_D_H_new' value='"+GABS.D_H+"'> <?=GetMessage("IPOLSDEK_mm")?>,";
			htmlCG += "<input type='text' style='width:20px' id='IPOLSDEK_GABS_W_new' value='"+$('#IPOLSDEK_GABS_W').val()+"'> <?=GetMessage("IPOLSDEK_kg")?>";
			htmlCG += " <a href='javascript:void(0)' onclick='IPOLSDEK_gabsHandler.acceptChange()'>OK</a>";
		$('#IPOLSDEK_natGabs').css('display','none');
		$('#IPOLSDEK_gabsPlace').parents('tr').css('display','table-row');
		$('#IPOLSDEK_gabsPlace').html(htmlCG);
	},
	// принятие изменений в кнопке "Изменить"
	acceptChange: function(){
		var GABS = {
			'D_L'  : $('#IPOLSDEK_GABS_D_L_new').val(),
			'D_W'  : $('#IPOLSDEK_GABS_D_W_new').val(),
			'D_H'  : $('#IPOLSDEK_GABS_D_H_new').val(),
			'W'    : $('#IPOLSDEK_GABS_W_new').val(),
			'mode' : 'mm',
		};
		IPOLSDEK_gabsHandler.writeChanges(GABS);
		onChangeConditoinsMakeReq();
	},
	// установка изменений согласно GABS
	writeChanges: function(GABS){
		if(GABS.mode == 'mm'){
			var GABSmm = GABS;
			var GABScm = {
				'D_L'  : GABS.D_L / 10,
				'D_W'  : GABS.D_W / 10,
				'D_H'  : GABS.D_H / 10
			}
		}else{
			var GABSmm =  {
				'D_L'  : GABS.D_L * 10,
				'D_W'  : GABS.D_W * 10,
				'D_H'  : GABS.D_H * 10
			};
			var GABScm = GABS;
		}

		var htmlCG  = GABSmm.D_W + " <?=GetMessage("IPOLSDEK_mm")?> x " + GABSmm.D_L + " <?=GetMessage("IPOLSDEK_mm")?> x " + GABSmm.D_H + " <?=GetMessage("IPOLSDEK_mm")?>, " + GABS.W + " <?=GetMessage("IPOLSDEK_kg")?> <a href='javascript:void(0)' onclick='IPOLSDEK_gabsHandler.changeGabs()'> <?=GetMessage('IPOLSDEK_STT_CHNG')?></a>";
		$('#IPOLSDEK_gabsPlace').html(htmlCG);
		$('#IPOLSDEK_GABS_D_L').val(GABScm.D_L);
		$('#IPOLSDEK_GABS_D_W').val(GABScm.D_W);
		$('#IPOLSDEK_GABS_D_H').val(GABScm.D_H);
		$('#IPOLSDEK_GABS_W').val(GABS.W);
		$('#IPOLSDEK_gabsPlace').parents('tr').css('display','table-row');
		$('#IPOLSDEK_VWeightPlace').html((GABScm.D_L*GABScm.D_W*GABScm.D_H) / 5000);
		IPOLSDEK_gabsHandler.changeStat = true;
		IPOLSDEK_serverShow();
	},
	// окончание работы управления упаковками
	onPackHandlerEnd: function(){
		$('#IPOLSDEK_PLACES').val('');
		if(IPOLSDEK_packHandler.saveObj.cnt == 1){
			var gabs = [1,1,1,1];
			for(var i in IPOLSDEK_packHandler.saveObj)
				if(!isNaN(parseInt(i))){
					gabs = IPOLSDEK_packHandler.saveObj[i].gabs.split(' x ');
					gabs.push(IPOLSDEK_packHandler.saveObj[i].weight);
					continue;
				}

			IPOLSDEK_gabsHandler.writeChanges({
				'D_L'  : gabs[0],
				'D_W'  : gabs[1],
				'D_H'  : gabs[2],
				'W'    : gabs[3],
				'mode' : 'cm'
			});
		}else{
			if(IPOLSDEK_packHandler.saveObj){
				delete IPOLSDEK_packHandler.saveObj.cnt;
				$('#IPOLSDEK_PLACES').val(JSON.stringify(IPOLSDEK_packHandler.saveObj));
			}
			IPOLSDEK_serverShow();
			onChangeConditoinsMakeReq();
		}
	},
	// проверяет, что именно показывать при открытии и редактировании
	changeStat: <?=(sdekHelper::isEqualArrs($naturalGabs,$ordrVals['GABS']) ? "false" : "true")?>,
	handleLabel: function(){
		// заданы упаковки
		if($('#IPOLSDEK_PLACES').val()){
			$('#IPOLSDEK_gabsPlace').closest('tr').css('display','none');
			$('#IPOLSDEK_natGabs').css('display','none');
			$('#IPOLSDEK_PLACES').closest('tr').css('display','');
		}else{
			if(IPOLSDEK_gabsHandler.changeStat){
				$('#IPOLSDEK_gabsPlace').closest('tr').css('display','table-row');
				$('#IPOLSDEK_natGabs').css('display','none');
				$('#IPOLSDEK_PLACES').closest('tr').css('display','none');
			}else{
				$('#IPOLSDEK_gabsPlace').closest('tr').css('display','none');
				$('#IPOLSDEK_natGabs').css('display','inline');
				$('#IPOLSDEK_PLACES').closest('tr').css('display','none');				
			}
		}
	}
}
</script>
<div style='display:none'>
	<table id='IPOLSDEK_wndOrder'>
		<tr><td><?=GetMessage('IPOLSDEK_JS_SOD_STATUS')?></td><td><?=$status.$satBut?></td></tr>
		<tr><td colspan='2'><small><?=GetMessage('IPOLSDEK_JS_SOD_STAT_'.$status)?></small><?=$message['number']?></td></tr>
		<?if($SDEK_ID){?><tr><td><?=GetMessage('IPOLSDEK_JS_SOD_SDEK_ID')?></td><td><?=$SDEK_ID?></td></tr><?}?>
		<?if($MESS_ID){?><tr><td><?=GetMessage('IPOLSDEK_JS_SOD_MESS_ID')?></td><td><?=$MESS_ID?></td></tr><?}?>
		<?//Заявка?>
		<tr class='heading'><td colspan='2'><?=GetMessage('IPOLSDEK_JS_SOD_HD_PARAMS')?></td></tr>
		<tr><td><?=GetMessage('IPOLSDEK_JS_SOD_number')?></td><td><?=($orderinfo['ACCOUNT_NUMBER'])?$orderinfo['ACCOUNT_NUMBER']:$orderId?></td></tr>
		<tr><td><?=GetMessage('IPOLSDEK_JS_SOD_service')?></td><td>
			<select id='IPOLSDEK_service' onchange='IPOLSDEK_onCodeChange($(this))'><?=$strOfCodes?></select>
			<?=$message['service']?>
		</td></tr>
		<tr id='IPOLSDEK_tarifWarning'><td colspan='2'><span><?=GetMessage('IPOLSDEK_JS_SOD_WRONGTARIF')?></span></td></tr>
		<tr><td><?=GetMessage('IPOLSDEK_JS_SOD_realSeller')?> <a href='#' class='PropHint' onclick='return ipolSDEK_popup_virt("pop-realSeller",this);'></a></td><td><input type='text' id='IPOLSDEK_realSeller' value='<?=($ordrVals['realSeller'])?$ordrVals['realSeller']:COption::GetOptionString(self::$MODULE_ID,'realSeller','')?>'></td></tr>
		<tr><td><?=GetMessage('IPOLSDEK_JS_SOD_isBeznal')?></td><td>
			<?if($payment === true || floatval($payment) >= floatval($orderinfo['PRICE'])){?>
				<input type='checkbox' id='IPOLSDEK_isBeznal' value='Y' <?=($ordrVals['isBeznal']=='Y')?'checked':''?> onchange='IPOLSDEK_checkNal()'>
			<?}else{?>
				<input type='checkbox' id='IPOLSDEK_isBeznal' value='Y' checked disabled onchange='IPOLSDEK_checkNal()'><br>
				<?
					if(!$payment)
						echo GetMessage("IPOLSDEK_JS_SOD_NONALPAY");
					else
						echo str_replace("#VALUE#",$payment,GetMessage("IPOLSDEK_JS_SOD_TOOMANY"));
			}?>
			&nbsp;&nbsp;<span id='IPOLSDEK_notPayed' style='color:red;display:none'><?=GetMessage("IPOLSDEK_JS_SOD_NOTPAYED")?></span>
		</td></tr>
		<?//Ошибки?>
		<?if(count($message['troubles'])){?>
			<tr class='heading'><td colspan='2'><?=GetMessage('IPOLSDEK_JS_SOD_HD_ERRORS')?></td></tr>
			<tr><td colspan='2'><?=$message['troubles']?></td></tr>
		<?}?>
		<?//Отправитель?>
		<tr id='IPOLSDEK_courierHeader'><td colspan='2' style='text-align:center;border-top: 1px dashed black'><a href='javascript:void(0)' onclick='IPOLSDEK_courierHandler.handleCourier()'></a>&nbsp;<a class='PropHint' onclick="return ipolSDEK_popup_virt('pop-sender',this);" href='javascript:void(0)'></a></td></tr>
		<?
		if(count($svdCouriers)){?>
			<tr class='IPOLSDEK_courierInfo'><td><?=GetMessage('IPOLSDEK_JS_SOD_courierSender')?></td><td><select onchange='IPOLSDEK_courierHandler.selectProfile($(this).val())'><option></option>
			<?foreach($svdCouriers as $ind => $vals){?>
				<option value='<?=$ind?>'><?=$vals['senderName']?></option>
			<?}?>
			</select>&nbsp;<a class='PropHint' onclick="return ipolSDEK_popup_virt('pop-courierSender',this);" href='javascript:void(0)'></a></td></tr>
		<?}?>
			<?// Дата?>
		<tr class='IPOLSDEK_courierInfo'><td><?=GetMessage('IPOLSDEK_JS_SOD_courierDate')?></td><td>
			<div class="adm-input-wrap adm-input-wrap-calendar">
				<input class="adm-input adm-input-calendar" disabled id='IPOLSDEK_courierDate' disabled type="text" name="IPOLSDEK_courierDate" style='width:148px;' value="<?=$ordrVals['courierDate']?>">
				<span class="adm-calendar-icon" style='right:0px'onclick="BX.calendar({node:this, field:'IPOLSDEK_courierDate', form: '', bTime: false, bHideTime: true,callback_after: IPOLSDEK_courierHandler.onDateChange});"></span>
			</div>
		</td></tr>
		<tr class='IPOLSDEK_courierInfo'><td colspan='2' id='IPOLSDEK_courierDateError' style='font-size:small;color:red;display:none'><?=GetMessage('IPOLSDEK_JS_SOD_badDate')?></td></tr>
			<?// Время?>
		<tr class='IPOLSDEK_courierInfo'><td><?=GetMessage('IPOLSDEK_JS_SOD_courierTime')?></td><td><input id='IPOLSDEK_courierTimeBeg' type='text' value='<?=$ordrVals['courierTimeBeg']?>' style='width:56px' onchange='IPOLSDEK_courierHandler.onTimeChange()'> - <input id='IPOLSDEK_courierTimeEnd' type='text' value='<?=$ordrVals['courierTimeEnd']?>' style='width:56px' onchange='IPOLSDEK_courierHandler.onTimeChange()'><input type='hidden' id='IPOLSDEK_courierTimeOK'></td></tr>
		<tr class='IPOLSDEK_courierInfo'><td colspan='2' id='IPOLSDEK_courierTimeError' style='font-size:small;color:red'></td></tr>
			<?// Прочее курьер?>
		<tr class='IPOLSDEK_courierInfo'><td><?=GetMessage('IPOLSDEK_JS_SOD_courierCity')?></td><td>
			<div><span id='IPOLSDEK_cSLabel'><?=$ordrVals['courierCity']['NAME']." ({$ordrVals['courierCity']['REGION']})"?></span><br><a href='javascript:void(0)' onclick='IPOLSDEK_courierHandler.changeCity(1)'><?=GetMessage("IPOLSDEK_STT_CHNG")?></a></div>
			<div style='display:none'><input id='IPOLSDEK_cSSelector' type='text' value=''></div>
			<input type='hidden' id='IPOLSDEK_courierCity' value='<?=$ordrVals['courierCity']['SDEK_ID']?>'>
		</td></tr>
		<tr class='IPOLSDEK_courierInfo'><td><?=GetMessage('IPOLSDEK_JS_SOD_courierStreet')?></td><td><input id='IPOLSDEK_courierStreet' type='text' value='<?=str_replace("'",'"',$ordrVals['courierStreet'])?>'></td></tr>
		<tr class='IPOLSDEK_courierInfo'><td><?=GetMessage('IPOLSDEK_JS_SOD_courierHouse')?></td><td><input id='IPOLSDEK_courierHouse' type='text' value='<?=str_replace("'",'"',$ordrVals['courierHouse'])?>'></td></tr>
		<tr class='IPOLSDEK_courierInfo'><td><?=GetMessage('IPOLSDEK_JS_SOD_courierFlat')?></td><td><input id='IPOLSDEK_courierFlat' type='text' value='<?=str_replace("'",'"',$ordrVals['courierFlat'])?>'></td></tr>
		<tr class='IPOLSDEK_courierInfo'><td><?=GetMessage('IPOLSDEK_JS_SOD_courierPhone')?></td><td><input id='IPOLSDEK_courierPhone' type='text' value='<?=$ordrVals['courierPhone']?>'></td></tr>
		<tr class='IPOLSDEK_courierInfo'><td><?=GetMessage('IPOLSDEK_JS_SOD_courierName')?></td><td><input id='IPOLSDEK_courierName' type='text' value='<?=str_replace("'",'"',$ordrVals['courierName'])?>'></td></tr>
		<tr class='IPOLSDEK_courierInfo'><td><?=GetMessage('IPOLSDEK_JS_SOD_courierComment')?></td><td><input id='IPOLSDEK_courierComment' type='text' value='<?=str_replace("'",'"',$ordrVals['courierComment'])?>'></td></tr>
		<?//Адрес?>
		<tr class='heading'><td colspan='2'><?=GetMessage('IPOLSDEK_JS_SOD_HD_ADDRESS')?></td></tr>
		<tr><td><?=GetMessage('IPOLSDEK_JS_SOD_location')?><?=$multiCity?></td><td><?=($multiCityS)?$multiCityS:$cityName?><input id='IPOLSDEK_location' type='hidden' value='<?=$ordrVals['location']?>'><?=$message['location']?></td></tr>
		<tr class='IPOLSDEK_notSV'><td><?=GetMessage('IPOLSDEK_JS_SOD_street')?></td><td>
			<?if($ordrVals['line']){?>
				<input id='IPOLSDEK_line' type='text' value='<?=$ordrVals['line']?>'>
			<?}else{?>
				<textarea id='IPOLSDEK_line'><?=$ordrVals['address']?></textarea>
			<?}?>
			<?=$message['line']?>
		</td></tr>
		<tr class='IPOLSDEK_notSV'><td><?=GetMessage('IPOLSDEK_JS_SOD_house')?></td><td><input id='IPOLSDEK_house' type='text' value='<?=$ordrVals['house']?>'><?=$message['house']?></td></tr>
		<tr class='IPOLSDEK_notSV'><td><?=GetMessage('IPOLSDEK_JS_SOD_flat')?></td><td><input id='IPOLSDEK_flat' type='text' value='<?=$ordrVals['flat']?>'><?=$message['flat']?></td></tr>
		<tr class='IPOLSDEK_SV'><td><?=GetMessage('IPOLSDEK_JS_SOD_PVZ')?></td>
			<td>
			<?if($strOfPSV){?><select id='IPOLSDEK_PVZ' onchange='IPOLSDEK_onPVZChange($(this))'><?=$strOfPSV?></select><?}
			else{?><span id='IPOLSDEK_deliveryPoint_noSV'><?=GetMessage('IPOLSDEK_JS_SOD_NOSVREG')?></span><?}?>
			<?=$message['deliveryPoint']?>
			</td>
		</tr>
		<tr class='IPOLSDEK_SV'><td colspan='2'><span id='IPOLSDEK_badPVZ' style='display:none'><?=GetMessage('IPOLSDEK_JS_SOD_BADPVZ')?></span></td></tr>
		<?//Получатель?>
		<tr class='heading'><td colspan='2'><?=GetMessage('IPOLSDEK_JS_SOD_HD_RESIEVER')?></td></tr>
		<tr><td><?=GetMessage('IPOLSDEK_JS_SOD_name')?></td><td><input id='IPOLSDEK_name' type='text' value='<?=$ordrVals['name']?>'><?=$message['name']?></td></tr>
		<tr><td valign="top"><?=GetMessage('IPOLSDEK_JS_SOD_phone')?></td><td><input id='IPOLSDEK_phone' type='text' value='<?=$ordrVals['phone']?>'></td></tr>
		<tr><td valign="top"><?=GetMessage('IPOLSDEK_JS_SOD_email')?></td><td><input id='IPOLSDEK_email' type='text' value='<?=$ordrVals['email']?>'></td></tr>
		<tr><td><?=GetMessage('IPOLSDEK_JS_SOD_comment')?></td><td><textarea id='IPOLSDEK_comment'><?=$ordrVals['comment']?></textarea><?=$message['comment']?></td></tr>
		<tr><td colspan='2'>
			<?foreach(array('realSeller','sender','courierSender','GABARITES') as $hintCode){?>
				<div id="pop-<?=$hintCode?>" class="b-popup" >
					<div class="pop-text"><?=GetMessage("IPOLSDEK_JSC_SOD_HELPER_$hintCode")?></div>
					<div class="close" onclick="$(this).closest('.b-popup').hide();"></div>
				</div>
			<?}?>
		</td></tr>	
		
		<?//Доп. параметры?>
		<tr class='heading'><td colspan='2'><?=GetMessage('IPOLSDEK_AS')?></td></tr>
		<?foreach($exOpts as $id => $option)
			if($option['SHOW']=="Y" || $option['DEF']=="Y"){
			?>
			<tr><td><?=GetMessage("IPOLSDEK_AS_".$id."_NAME")?></td><td><input id='IPOLSDEK_AS_<?=$id?>' <?=($option['DEF']=="Y")?"checked":""?> type='checkbox' value='<?=$id?>'></td></tr>
		<?}?>
		
		<?// О заказе?>
		<tr class='heading'><td colspan='2'><a onclick='IPOLSDEK_serverShow()' href='javascript:void(0)'><?=GetMessage('IPOLSDEK_JS_SOD_ABOUT')?></td></tr>
			<?// Габариты родные?>
		<tr class='IPOLSDEK_detOrder' style='display:none'>	
			<td><?=GetMessage('IPOLSDEK_JS_SOD_GABARITES')?> <a href='#' class='PropHint' onclick='return ipolSDEK_popup_virt("pop-GABARITES",this);'></a></td>
			<td>
				<?=($naturalGabs['D_W'])*10?><?=GetMessage("IPOLSDEK_mm")?> x <?=($naturalGabs['D_L'])*10?><?=GetMessage("IPOLSDEK_mm")?> x <?=($naturalGabs['D_H'])*10?><?=GetMessage("IPOLSDEK_mm")?>, <?=$naturalGabs['W']?><?=GetMessage("IPOLSDEK_kg")?> 
				<?if(!$IPOLSDEK_isLoaded || $status == 'NEW' || $status == 'ERROR'){?>
					<a <?=(sdekHelper::isEqualArrs($naturalGabs,$ordrVals['GABS'])?"":"style='display:none'")?> href='javascript:void(0)' id='IPOLSDEK_natGabs' onclick='IPOLSDEK_gabsHandler.changeGabs()'><?=GetMessage('IPOLSDEK_STT_CHNG')?></a>
				<?}?>
				<input id='IPOLSDEK_GABS_D_W' type='hidden' value='<?=$ordrVals['GABS']['D_W']?>'>
				<input id='IPOLSDEK_GABS_D_L' type='hidden' value='<?=$ordrVals['GABS']['D_L']?>'>
				<input id='IPOLSDEK_GABS_D_H' type='hidden' value='<?=$ordrVals['GABS']['D_H']?>'>
				<input id='IPOLSDEK_GABS_W'   type='hidden' value='<?=$ordrVals['GABS']['W']?>'>
			</td>
		</tr>
			<?// Габариты заданные?>
		<tr class='IPOLSDEK_detOrder' style='display:none'>	
			<td><?=GetMessage('IPOLSDEK_JS_SOD_CGABARITES')?></td>
			<td>
				<div id='IPOLSDEK_gabsPlace'>
					<?=($ordrVals['GABS']['D_W'])*10?><?=GetMessage("IPOLSDEK_mm")?> x <?=($ordrVals['GABS']['D_L'])*10?><?=GetMessage("IPOLSDEK_mm")?> x <?=($ordrVals['GABS']['D_H'])*10?><?=GetMessage("IPOLSDEK_mm")?>, <?=$ordrVals['GABS']['W']?><?=GetMessage("IPOLSDEK_kg")?> 
					<?if(!$IPOLSDEK_isLoaded || $status == 'NEW' || $status == 'ERROR'){?>
					<a href='javascript:void(0)' onclick='IPOLSDEK_gabsHandler.changeGabs()'><?=GetMessage('IPOLSDEK_STT_CHNG')?></a>
					<?}?>
				</div>
			</td>
		</tr>
			<?// Габариты упаковки?>
		<tr class='IPOLSDEK_detOrder' style='display:none'>	
			<td colspan="2" style='text-align:center'><?=GetMessage('IPOLSDEK_JS_SOD_PACKS_GIVEN')?><input type='hidden' id='IPOLSDEK_PLACES' value='<?=json_encode($ordrVals['packs'])?>'></td>
		</tr>
		<tr class='IPOLSDEK_detOrder' style='display:none'>	
			<td><?=GetMessage('IPOLSDEK_JS_SOD_VWEIGHT')?></td>
			<td>
				<span id='IPOLSDEK_VWeightPlace'><?=($ordrVals['GABS']['D_W']*$ordrVals['GABS']['D_L']*$ordrVals['GABS']['D_H']/5000)?></span><?=GetMessage("IPOLSDEK_kg")?>
			</td>
		</tr>		
		<tr class='IPOLSDEK_detOrder' style='display:none'>	
			<td><?=GetMessage('IPOLSDEK_JS_SOD_SDELPRICE')?></td>
			<td><?=$orderinfo['PRICE_DELIVERY']?></td>
		</tr>		
		<tr class='IPOLSDEK_detOrder' style='display:none'>	
			<td><?=GetMessage('IPOLSDEK_JS_SOD_NDELPRICE')?></td>
			<td id='IPOLSDEK_newPrDel'></td>
		</tr>
	</table>
</div>