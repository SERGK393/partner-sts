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
<script src="<?=$this->GetFolder()?>/masked.js" type="text/javascript"></script>
<div id="fast-buy-container"><h6>Быстрый заказ</h6>
	<form id="fast-buy" action="javascript:void(null);" method="POST" onsubmit="fast_buy('<?=$this->GetFolder().'/ajax.php'?>')">
		<label for="valn"><?=GetMessage('INPUT_NUMBER')?>:</label><br>
		<input type="text" name="valn" id="valn"><br>
		<label for="valstr"><?=GetMessage('INPUT_NAME')?>:</label><br>
		<input type="text" name="valstr" id="valstr" value="<?=$arResult['name']?>"><br>
		<input type="hidden" name="product_id" value="<?=$arResult['product_id']?>">
		<input type="hidden" name="prod_add_id" value="">
		<input type="hidden" name="product_name" value="<?=$arResult['product_name']?>">
		<input type="hidden" name="product_price" value="<?=$arResult['product_price']?>">
		<input type="hidden" name="user" value="<?=$arResult['user']?>">
		<input type="hidden" name="city" value="<?=$arResult['city']?>">
		<input type="submit" value="<?=GetMessage('OK')?>">
	</form>
</div>
<script src="<?=$this->GetFolder()?>/script_js.js" type="text/javascript"></script>
