<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<div class="top3_wrapper">
<?foreach ($arResult['PRODUCTS'] as $key => $value) { ?>
<?
$this->AddEditAction($value['id'], $value['edit_href'], CIBlock::GetArrayByID($value['iblock'], 'ELEMENT_EDIT'));
$this->AddDeleteAction($value['id'], $value['delete_href'], CIBlock::GetArrayByID($value['iblock'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
?>
<div class="top3_item" id="<?= $this->GetEditAreaId($value['id']);?>">
	<a href="<?= $value['href']?>" style="height: <?= $arParams['PICTURE_HEIGHT']?>px">
		<img src="<?= $value['img']['src']?>" title="<?= $value['name']?>">
	</a>
	<span> <?= $value['name']?></span>
	<span class="top3_price"> <?= CCurrencyLang::CurrencyFormat($value['price'],"RUB",true)?></span>
</div>
<?  }?>
<div class="top3_btn_l"></div>
<div class="top3_btn_r"></div>
</div>
