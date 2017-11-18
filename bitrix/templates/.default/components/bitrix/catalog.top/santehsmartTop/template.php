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
$a="t4fg";
$b=2;

print $a+$b;


if (!empty($arResult['ITEMS']))
{	echo "<pre>";
	echo $rows=count($arResult['ITEMS']);
	print_r($arResult);
	for($i=0;$i<=$rows-1;$i++){
		foreach($arResult['ITEMS'][$i] as $item){?>
		<div class="slider-item-wrapper" style="background-image: url(<?=$item["PREVIEW_PICTURE"]["SRC"]?>);background-repeat:no-repeat;">
			<h6><?=$item['NAME']?></h6>
			<?=$item['PRICES']['BASE']['PRINT_VALUE']?>
		</div>
		<?}	
	}?>

<?	}
?>