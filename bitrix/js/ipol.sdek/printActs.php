<?
/*
	��� ������ ������� ������ ���� ������-�������� � �������.
	��� ����� ������������ ��� �������� ������ �������, ������ ����� ���������, ��� �� �� �������� �� ������� ������ ������� (����� ������ �� ���������� �� ����� �����).
	������ ������� ����������� � ������ ���������� ��� ������� ������.
	
	����� �������������� ���������, ����� ���� ��� � ��� �� ���������, ��� � ����, ����� ������� ������� ����� ��������. � ������ ������� ��� ��������� � ������ � UTF-����������.
	
	� ������ ������������ �� ��������������� ������� �� �������, ���������������� � ������������� ����� ������� ��� �����.
*/
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$SALE_RIGHT = $APPLICATION->GetGroupRight("sale");
if($SALE_RIGHT=="D") $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$module_id = "ipol.sdek";
CModule::IncludeModule($module_id);

$shpName=COption::GetOptionString($module_id,'strName','');

if(CModule::IncludeModule("sale")){

	$arOrders = explode(":", $ORDER_ID);
	unset($ORDER_ID);
	$ttlPay = 0;
	foreach($arOrders as $key => $id){
		$req=sdekdriver::select(array(),array('ORDER_ID'=>$id))->Fetch();
		if(!$req){
			unset($arOrders[$key]);
			continue;
		}
		$params = unserialize($req['PARAMS']);
		$order  = CSaleOrder::GetById($id);
		$arOrders[$key] = array(
			'ID'     => $id,
			'SDEKID' => $req['SDEK_ID'],
			'WEIGHT' => ($params['GABS']['W'])?$params['GABS']['W']:(COption::GetOptionString($module_id,'weightD',1000))/1000,
			'PRICE'  => (float)($order['PRICE'] - $order['PRICE_DELIVERY']),
			'TOPAY'  => ($params['isBeznal']=='Y')?0:(float)$order['PRICE']
		);
		$ttlPay+=$arOrders[$key]['PRICE'];
	}

	if(is_array($arOrders) && count($arOrders) > 0){
		$arDate = array(
			1  => ' ������ ',
			2  => ' ������� ',
			3  => ' ����� ',
			4  => ' ������ ',
			5  => ' ��� ',
			6  => ' ���� ',
			7  => ' ���� ',
			8  => ' ������� ',
			9  => ' �������� ',
			10 => ' ������� ',
			11 => ' ������ ',
			12 => ' ������� ',
		);
		$actDate = date("d").$arDate[date("m")].date("Y");
	?>
		<!-- ������� ������ ���� --> 					
			<style type="text/css">
			/* ��� */
			<!--
				@page { size: 21cm 29.7cm; margin-left: 1cm; margin-right: 1cm; margin-top: 1cm; margin-bottom: 1cm }
				P { margin-bottom: 0.21cm; direction: ltr; color: #000000; widows: 2; orphans: 2 }
			-->
			div.block {
				width: 100%;
				clear: right;
				min-height: 100px;
				page-break-after:always;
			}
			div.block:last-child{
				page-break-after:auto;
			}
			.header{
				text-align:center;
				font-weight:bold;
			}
			.right{
				text-align:right;
			}
			.text8{
				font-size: 8pt;
			}
			.breaker{
				height: 1cm;
			}
			</style>
			<div class="block">			
				<p class='header'>��� ������-�������� �_______ �� <?=$actDate?> �.</p>
				<p><br></p>
				<p>__________, ��������� � ���������� �����������, � ���� __________, ������������ �� ��������� ____ �� ____  �., ������� � ������ ���������� �������� �___ �� ____ �. ������� �� __________, ���������� � ���������� ������, � ���� __, ������������ �� ��������� ___, ��������� ����������� ��� ����������� �������� �����������:</p>
				<p><br></p>
				<table BORDER='1' BORDERCOLOR="#00000a" CELLPADDING='2' CELLSPACING='0'>
					<tr VALIGN='TOP'>
						<td><p>�</p></td>
						<td><p>����� ������</p></td>
						<td><p>����� �����������</p></td>
						<td><p>��� ����������� (��)</p></td>
						<td><p>��������� ����������� (���.)</p></td>
						<td><p>����� � ������ (���.)</p></td>
					</tr>
					<?foreach($arOrders as $key => $arOrder){?>
						<TR VALIGN='TOP'>
							<td><p><?=$key+1?></p></td>
							<td><p><?=$arOrder['ID']?></p></td>
							<td><p><?=$arOrder['SDEKID']?></p></td>
							<td><p><?=$arOrder['WEIGHT']?></p></td>
							<td><p><?=$arOrder['PRICE']?></p></td>
							<td><p><?=$arOrder['TOPAY']?></p></td>
						</TR>
					<?}?>
				</TABLE>
				<p><strong>����� ���������� ����������� <?=count($arOrders)?> ������.</strong></p>
				<p><strong>����� ����� ����������� ��������� <?=$ttlPay?> ���.</strong></p>
				<p><br></p>
				<table BORDER=0 CELLPADDING=9 CELLSPACING=0>
					<tr VALIGN=TOP>
						<td WIDTH=381><p class='text8'>����:</p></td>
						<td WIDTH=381><p class='text8'>������:</p></td>
					</tr>
					<tr VALIGN=TOP>
						<td>
							<p class='text8'>��������� ____________________________</p>
							<p class='text8'>��� _________________________________</p>
							<p class='text8'>������� ______________________________</p>
							<p><BR></p>
						</td>
						<td>
							<p class='text8'>��������� ____________________________</p>
							<p class='text8'>��� _________________________________</p>
							<p class='text8'>������� ______________________________</p>
							<p><BR></p>
						</td>
					</tr>
				</table>
				<p class='breaker'></p>
			</div>
		<?}
	}
?>