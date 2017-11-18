<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();
/*
echo "<pre>";
print_r($arResult);
*/
if(!empty($arResult)):
?>

<script src='/libraries/owl.carousel/owl-carousel/owl.carousel.js'></script>
<link rel='stylesheet' href='/libraries/owl.carousel/owl-carousel/owl.carousel.css'>
<link rel='stylesheet' href='/libraries/owl.carousel/owl-carousel/owl.theme.css'>
<link rel='stylesheet' href='<?=$this->GetFolder()?>/style.css'> 
<div class="ti_wrp">
<h4>Вместе дешевле:</h4>
<div id='ti_carousel' class='owl-carousel3 owl-theme3'>
	<?foreach($arResult['ITEMS'] as $tiItem):?>
	<div class='ti_carousel_item'>
		<div class="ti_img_ctn">
			<a href="<?=$tiItem['DETAIL_PAGE_URL']?>">
			<?=$tiItem['IMG']?>
			</a>
		</div>	
		<div class='ti_child_title'>
			<a href="<?=$tiItem['DETAIL_PAGE_URL']?>">
			    <?=$tiItem['NAME']?>
			</a>
		</div>
		<div class='ti_child_price_and_discount'>
			<p class="ti_child_old_price"><?=$tiItem['PRICE']?></p>
			<p class="ti_child_new_price"><?=$tiItem['DISCOUNT_PRICE']?></p>
			<p class="ti_child_discount"><?="Вы экономите ".$tiItem['DISCOUNT_R']?></p>
			<div>
				<?if($tiItem['IN_CART']===true):?>
				<a href="/personal/cart">
				<input class="ti_2cart_href" type="button" value="В корзине">
				</a>	
					<?else:?>
				<input class="ti_2cart_button" type="button" value="Купить со скидкой" 
					data-path="<?=$arResult['COMPONENT_DIRECTORY'];?>"
					data-itemId="<?=$tiItem['ID'];?>"
					data-discountValue="<?=$tiItem['DISCOUNT'];?>"
					data-parentId="<?=$arResult['PARENT_ID'];?>"
					data-cart="Y">

					<?endif?>
			</div>		
		</div>
			</div>
	<?endforeach?>
	
</div>
</div>
<script src='<?=$this->GetFolder()?>/script_js.js'></script>
<?endif?>
