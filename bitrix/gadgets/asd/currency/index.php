<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule('currency')) {
	return;
}

$arCurrency = $arGadgetParams['CURRENCY'];
if (empty($arCurrency) || !is_array($arCurrency)) {
	$arCurrency = array('USD');
}

if (intval($arGadgetParams['WIDTH'])<=0) {
	$width = 500;
} else {
	$width = intval($arGadgetParams['WIDTH']);
}
if (intval($arGadgetParams['HEIGHT'])<=0) {
	$height = 300;
} else {
	$height = intval($arGadgetParams['HEIGHT']);
}

$minForGraph = 3;
$n = intval($arGadgetParams['DAYS']);
if (intval($n) < 2) {
	$n = 1;
}
$subDate = mktime() - 3600*24*($n-1);
$startDate = ConvertTimeStamp($subDate,'SHORT');

$dyn = array();
$GridX = array();
foreach($arCurrency as $cur){

	$arThisGrid = array();
	$arThisCurrency = array();
	$arFilter = array(
		'CURRENCY' => $cur,
		'DATE_RATE'=> $startDate,
		);

	$db_rate = CCurrencyRates::GetList(($by = 'date'), ($order = 'asc'), $arFilter);
	$dyn[$cur] = 0;
	$arrY = array();
	$rateNew = 0;

	while($arRate = $db_rate->Fetch()){
		$strDate = strtotime($arRate['DATE_RATE']);
		$current = round($arRate['RATE'],4);
		$arrY[$cur][$strDate] = $current;
		$grid[$cur][] = $strDate;
		if ($rateNew != $current){
			$dyn[$cur] = $current-$rateNew;
			$rateNew = $current;
		}
	}

	if($arrY[$cur]) {
		$arResult['DATA'][$cur]['Y'] = $arrY[$cur];
	}
	if($grid[$cur]) {
		$GridX = array_merge($GridX,$grid[$cur]);
	}

	$arResult['CURRENT'][$cur] = $current;
	$arResult['DYN'][$cur] = round($dyn[$cur], 4);
}

$GridX = array_unique($GridX);
sort($GridX);
foreach($GridX as $date){
	$GridXx[$date] = ConvertTimeStamp($date,'SHORT');
}

$arAxis = array(GetMessage("ASD_CURRENCYINFO_DATA"));
$arResult['GRAPH'] = array();
foreach($arResult['DATA'] as $cur=>$arrCur){
	$arAxis[] = $cur;
}

$arResult['GRAPH'][] = $arAxis;
foreach($GridXx as $key=>$date){
	$arData = array($date);
	foreach($arResult['DATA'] as $cur=>$arrCur){
		if($arResult['DATA'][$cur]['Y'][$key]){
			$arData[] = $arResult['DATA'][$cur]['Y'][$key];
			$mem[$cur] = $arResult['DATA'][$cur]['Y'][$key];
		} else {
			$arData[] = $mem[$cur];
		}
	}
	$arResult['GRAPH'][] = $arData;
}

include('colors.php');
$cnt = count($arColors);

if ($arResult['DATA']) {
?>
	<?//if (count($arResult['DATA']) > $minForGraph):
		$dataY = json_encode($arResult['GRAPH']);
		?>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<div id="currency_diagram"></div>
	<script type="text/javascript">
		google.load('visualization', '1.0', {'packages':['corechart']});
		function drawCurrencyDiagram() {
			var data = google.visualization.arrayToDataTable(<?=$dataY?>);
			new google.visualization.LineChart(document.getElementById('currency_diagram')).
			draw(data, {
				colors: <?=json_encode($arColors)?>,
				curveType: 'function',
				width: <?=$width?>,
				height: <?=$height?>,
				vAxis: {
					title: '<?=  CUtil::JSEscape(GetMessage('ASD_CURRENCYINFO_KURS'))?>'
				},
				hAxis:{
					title: '<?=CUtil::JSEscape(GetMessage('ASD_CURRENCYINFO_DATA'))?>',
					baseline: 'date'
				}
			});
		}
		google.setOnLoadCallback(drawCurrencyDiagram);
	</script>
	<?//endif;?>
	<div>
		<table>
		<?
		$i = 0;
		foreach($arResult['DATA'] as $cur=>$arr) {
			$clr = $i % $cnt;?>
			<tr>
				<td style="text-align: center; color:<?='#'.$arColors[$clr]?>"><?=$cur?></td>
				<td style="text-align: center;color:<?='#'.$arColors[$clr]?>"><?=$arResult['CURRENT'][$cur]?></td>
				<?if($arResult['DYN'][$cur]>0){?>
					<td style="text-align: center;color:green">(+<?=$arResult['DYN'][$cur]?>)</td>
				<?}elseif($arResult['DYN'][$cur]<0){?>
					<td style="text-align: center;color:red">(<?=$arResult['DYN'][$cur]?>)</td>
				<?}elseif($arResult['DYN'][$cur]==0){?>
					<td style="text-align: center;color:black">(<?=$arResult['DYN'][$cur]?>)</td>
				<?}?>
			</tr>
			<?
			$i++;
		}?>
		</table>
	</div>
<?}?>