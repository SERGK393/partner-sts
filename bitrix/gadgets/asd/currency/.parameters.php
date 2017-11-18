<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule('currency')) {
	return;
}


$lcur = CCurrency::GetList(($by='name'), ($order1='asc'), LANGUAGE_ID);
while($lcur_res = $lcur->Fetch()) {
	$arValues[$lcur_res['CURRENCY']] = $lcur_res['FULL_NAME'].'('.$lcur_res['CURRENCY'].')';
}

$arUserParams = Array(
			'CURRENCY'=>Array(
				'NAME' => GetMessage("ASD_CURRENCYINFO_VALUTA"),
				'TYPE' => 'LIST',
				'MULTIPLE' => 'Y',
				'VALUES' => $arValues,
			),
			'DAYS'=>Array(
				'NAME' => GetMessage("ASD_CURRENCYINFO_POKAZYVATQ_STATISTIK"),
				'TYPE' => 'STRING',
				'DEFAULT' => 7,
			),
			'WIDTH'=>Array(
				'NAME' => GetMessage("ASD_CURRENCYINFO_SIRINA_GRAFIKA_V"),
				'TYPE' => 'STRING',
				'DEFAULT' => 400,
			),
			'HEIGHT'=>Array(
				'NAME' => GetMessage("ASD_CURRENCYINFO_VYSOTA_GRAFIKA_V"),
				'TYPE' => 'STRING',
				'DEFAULT' => 250,
			),



);

$arParameters = array('USER_PARAMETERS'=>$arUserParams);
