<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div id="sldr-cmp"<?if(isset($arParams['CHANGE_TIME'])){?> data-time="<?=$arParams['CHANGE_TIME']?>"<?}?>>
    <?foreach ($arResult['ITEMS'] as $key=>$arItem):?>
    <div<?if($key){?> style="display: none"<?}?>>
        <img src="<?=$arItem['DETAIL_PICTURE']['src']?>">
    </div>
    <?endforeach?>
</div>
