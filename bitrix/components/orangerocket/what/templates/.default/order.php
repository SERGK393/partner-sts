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
<form action="/what" method="get">
<label for="order_id"><?=GetMessage('PLEASE_INPUT')?>: </label>
<input type="text" name="order_id" id="order_id">
<input type="submit" value="<?=GetMessage('PLEASE_SUBMIT')?>">
</form>
<div class="order-info">
	<?if($arResult['ORDER_FOUND']=='Y'):?>
		<b><?=GetMessage('ORDER_NUMBER')?>: </b><span><?=$arResult['ORDER_INFO']['ORDER_NUMBER']?></span><br>
		<?if(strlen($arResult['ORDER_STATUS_ID'])>1){?>
		<b><?=GetMessage('ORDER_STATUS_NAME')?>: </b><span><?=GetMessage($arResult['ORDER_STATUS_ID'])?></span>
		<?}else{?>
		<b><?=GetMessage('ORDER_STATUS_NAME')?>: </b><span><?=$arResult['ORDER_STATUS_NAME']?></span><br>
		<b><?=GetMessage('ORDER_STATUS_DESC')?>: </b><span><?=$arResult['ORDER_STATUS_DESC']?></span>
		<?}?>
	<?else:?>
		<b><?=GetMessage('NOT_FOUND')?></b>
	<?endif?>
</div>