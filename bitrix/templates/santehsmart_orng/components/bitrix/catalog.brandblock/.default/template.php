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

if(empty($arResult["BRAND_BLOCKS"]))
	return;

$strObName = "bxIblockBrand_".$this->randString();
$mouseEvents = 'onmouseover="'.$strObName.'.itemOver(this);" onmouseout="'.$strObName.'.itemOut(this)"';
?>
<div class="bx_item_detail_inc_two">
		<?
		foreach ($arResult["BRAND_BLOCKS"] as $blockId => $arBB)
		{
			$brandID = 'brand_'.$arResult['ID'].'_'.$this->randString();
			$html = '';

			if($arBB['TYPE'] == 'ONLY_PIC')
			{
				$html .= '<img src="'.htmlspecialcharsbx($arBB['PICT']['SRC']).'"';

				if($arBB['NAME'] !== false)
					$html .= ' alt="'.htmlspecialcharsbx($arBB['NAME']).'"  title="'.htmlspecialcharsbx($arBB['NAME']).'"';

				$html .= '>';

				if($arBB['LINK'] !== false)
					$html = '<a target="blank" href="'.htmlspecialcharsbx($arBB['LINK']).'">'.PHP_EOL.
					$html.PHP_EOL.
					'</a>';

				if($arBB['FULL_DESCRIPTION'] !== false)
					$html .= '<span class="bx_popup"><span class="arrow"></span><span class="text">'.$arBB['FULL_DESCRIPTION'].'</span></span>';

				$html = '<div id="'.$brandID.'" class="bx_item_detail_inc_one_container">'.PHP_EOL.
					$html.PHP_EOL.
					'</div>';
			}
			else
			{
				if($arBB['FULL_DESCRIPTION'] !== false)
					$html .= '<span class="bx_popup"><span class="arrow"></span><span class="text">'.$arBB['FULL_DESCRIPTION'].'</span></span>';

				if($arBB['DESCRIPTION'] !== false)
					$html .= htmlspecialcharsbx($arBB['DESCRIPTION']);

				if($arBB['PICT'] != false && strlen($arBB['PICT']['SRC']) > 0)
				{
					$html = ' id="'.$brandID.'" class="bx_item_vidget icon" style="background-image:url('.$arBB['PICT']['SRC'].');" '.$mouseEvents.'>'.
						$html;
				}
				else
				{
					$html = ' id="'.$brandID.'" class="bx_item_vidget" '.$mouseEvents.'>'.
						$html;
				}

				if(strlen($arBB['LINK']) > 0)
					$html = '<a target="blank" href="'.htmlspecialcharsbx($arBB['LINK']).'"'.$html.'</a>';
				else
					$html = '<span'.$html.'</span>';
			}

			echo $html;
		}
		?>
</div>
<script type="text/javascript">
	var <?=$strObName;?>;
	BX.ready( function(){
		if(typeof window["JCIblockBrands"] != 'function') //if cached by upper components, etc.
		{
			BX.loadScript("<?=$templateFolder.'/script.js'?>", function(){ <?=$strObName;?> = new JCIblockBrands; });
			BX.loadCSS("<?=$templateFolder.'/style.css'?>");
		}
		else
		{
			<?=$strObName;?> = new JCIblockBrands;
		}
	});
</script>