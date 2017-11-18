<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
/*
echo "<pre>";
print_r($arResult);
*/
if(!empty($arResult)):
?>
<div class="nss-wrapper">
	<div class="nss-items-wrapper">
		<div class="nss-items-line" style="width:<?=(count($arResult)*630).'px';?>">
			<ul>
				<?foreach($arResult as $item):?>
					<?if($item['PROPERTY_PUBLISH_VALUE']=="Y"):?>
						<li><?=CFile::ShowImage($item['DETAIL_PICTURE'],630,270)?>
							<a href="<?=$item['PROPERTY_URLL_VALUE']?>"><?=$item['PROPERTY_ACTION_VALUE']?></a>
						</li>
					<?endif?>
				<?endforeach?>
			</ul>
		</div>
	</div>
	<div class="nss-controlPanel">
		<ul>
			<?$positionCtn=0?>
			<?foreach($arResult as $item):?>
				<?if($item['PROPERTY_PUBLISH_VALUE']=="Y"):?>
					<li class="nss-tab" data-position="<?=$positionCtn?>"
						<?if($positionCtn==0):?>
						style="background-color:rgb(223, 223, 223);border-bottom-color: orangered;"
						<?endif?>
						><?=$item['PROPERTY_NAME_VALUE'];?>
						<?$positionCtn=$positionCtn+630?>
					</li>
				<?endif?>
			<?endforeach?>
		</ul>
	</div>
</div>
<?endif?>
