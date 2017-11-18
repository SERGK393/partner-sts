<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (CModule::IncludeModule("iblock")) {
    if ($arParams["ELEMENT_ID"]!=='') {
        $arOrder = array("SORT" => "ASC");
        $arFilter = array('IBLOCK_ID' => $arParams["IBLOCK_ID"], 'ID' => $arParams["ELEMENT_ID"]);
        $arSelect = array('DETAIL_TEXT');
        $result_package = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        $result_package = $result_package->GetNext(true, false);
        $arResult['content']=$result_package['DETAIL_TEXT'];
        $this->includeComponentTemplate();
    }
}




