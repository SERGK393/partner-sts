<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();
?>
<!--<div class="when-and-how-much">
	<div class="wahm-section wahm-hr">
		<div class="wahm-sub-section wahm-img"><img src="/bitrix/components/orangerocket/when_and_how_much/images/flycar.png">
         </div>
		<div class="wahm-sub-section wahm-del">
			<?echo 'Стоимость доставки: '.$arResult['DEL_PRICE'].'руб.<br>';
         echo $arResult['DEL_TIME'].'<br>';?>
		 </div>		
	</div>
	<div class="wahm-section">
		<span>Можно будет забрать:</span>
		<div class="wahm-sub-section wahm-img"><img src="/bitrix/components/orangerocket/when_and_how_much/images/flymarc.png"></div>
		<div class="wahm-sub-section wahm-marc"> <?echo $arResult['DEL_POINT'];?>
</div>		
	</div>

		
        
	</div>-->

<?$this->setFrameMode(true);?>

<?if($arResult['DEL_PRICE_POINT']=="<b>Бесплатно</b>"):?>
	<div class="freeDelStick"><img  src='/images/freeDelivery_big.png'></div>
    <span><?=$arResult['DEL_TIME']?></span>
    <div><a href="/punkty-vydachi/"><input class="wahm-point-btn--free" type="button" value="Пункты выдачи в Вашем городе &rarr;"></a></div>
<?else:?>
<div class="wahm-title">Доставка курьером</div>
<div class="wahm-cont wahm-curr">
	Стоимость доставки: <?=$arResult['DEL_PRICE']?><br>
    <?=$arResult['DEL_TIME']?><br>
</div>
<div class="wahm-title">Доставка до пункта выдачи</div>
<div class="wahm-cont wahm-point">Стоимость доставки: <?=$arResult['DEL_PRICE_POINT']?><br>
     <?=$arResult['DEL_TIME']?><br>
     <a href="/punkty-vydachi/"><input class="wahm-point-btn" type="button" value="Пункты выдачи в Вашем городе &rarr;"></a>
     </div>
<?endif;?>
