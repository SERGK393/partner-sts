<?php
$ove_template_path=SITE_TEMPLATE_PATH.'/';
$ove_css_path=SITE_TEMPLATE_PATH.'/css/';
$ove_img_path=SITE_TEMPLATE_PATH.'/images/';
$ove_mainmenu_path=$_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/mainmenu/';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
	<meta name='yandex-verification' content='6e11652c7755d964' />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
	<link rel="shortcut icon" type="image/x-icon" href="<?=SITE_TEMPLATE_PATH?>/favicon.ico" /><?//$APPLICATION->ShowHead();
	echo '<meta http-equiv="Content-Type" content="text/html; charset='.LANG_CHARSET.'"'.(true ? ' /':'').'>'."\n";
	$APPLICATION->ShowMeta("robots", false, true);
	$APPLICATION->ShowMeta("keywords", false, true);
	$APPLICATION->ShowMeta("description", false, true);
	$APPLICATION->ShowCSS(true, true);
	?>
	<link rel="stylesheet" href="<?php echo $ove_template_path ?>mainmenu/res/style.css" type="text/css" />
	<?php
	$APPLICATION->ShowHeadStrings();
	$APPLICATION->ShowHeadScripts();
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/script.js");
	?>
	<title><?$APPLICATION->ShowTitle()?></title>
</head>
<body id="ove">
	<div id="panel"><?$APPLICATION->ShowPanel();?></div>
	<div id="top_block">
		<div id="tb_center">
			<div id="tb_in">
				<div class="ove_city">***CITY***</div>
				<div class="ove_search">
					<?$APPLICATION->IncludeComponent("bitrix:search.title", "visual", array(
							"NUM_CATEGORIES" => "1",
							"TOP_COUNT" => "5",
							"CHECK_DATES" => "N",
							"SHOW_OTHERS" => "N",
							"PAGE" => SITE_DIR."catalog/",
							"CATEGORY_0_TITLE" => GetMessage("SEARCH_GOODS") ,
							"CATEGORY_0" => array(
								0 => "iblock_catalog",
							),
							"CATEGORY_0_iblock_catalog" => array(
								0 => "all",
							),
							"CATEGORY_OTHERS_TITLE" => GetMessage("SEARCH_OTHER"),
							"SHOW_INPUT" => "Y",
							"INPUT_ID" => "title-search-input",
							"CONTAINER_ID" => "search",
							"PRICE_CODE" => array(
								0 => "BASE",
							),
							"SHOW_PREVIEW" => "Y",
							"PREVIEW_WIDTH" => "75",
							"PREVIEW_HEIGHT" => "75",
							"CONVERT_CURRENCY" => "Y"
						),
						false
					);?></div>
				<div class="ove_cart">
								<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "", array(
										"PATH_TO_BASKET" => SITE_DIR."personal/cart/",
										"PATH_TO_PERSONAL" => SITE_DIR."personal/",
										"SHOW_PERSONAL_LINK" => "N",
										"SHOW_NUM_PRODUCTS" => "Y",
										"SHOW_TOTAL_PRICE" => "Y",
										"SHOW_PRODUCTS" => "N",
										"POSITION_FIXED" =>"N"
									),
									false,
									array()
								);?></div>
			</div>
		</div>
	</div>
	<div id="fix_block">
		<div class="ove_fixleft"></div>
		<div class="ove_fixright">
			<a href="/lowprice" target="_blank"><img src="images/122G.png" alt="Нашли дешевле?" /></a>
				<a href="http://ove-cfo.ru/2011-11-24-09-14-11.html"><img src="bibika.png" alt="d2013" width="130px";/></a>
		</div>
		
		</div>
	</div>
	<div id="body_block">
		<table>
			<tbody>
				<tr id="body_header">
					<td class="ove_logo">
						<div class="mod_container">
							<a href="/"><img src="<?php echo $ove_img_path ?>logo.png" alt="logo" /></a>
						</div>
					</td>
					<td class="ove_numbers">
						<noscript><div style="position:absolute;background-color:white; color:red;font-size:25px;font-weight:700;text-align:center; padding-top:40px;width:880px;height:100px">Включите JavaScript для полноценной работы сайта.</div></noscript>
						*NUMBERS*
						<script>f_go(get_city_cook())</script>
					</td>
					<td class="ove_littleslide"></td>
				</tr>
				<tr id="body_menu">
					<td class="ove_shop">
						<a href="javascript:;"><div id="inetmagazin_button"><div class="ove_mainmenu_arrow">Интернет-магазин</div></div></a>
						<?php require( $ove_mainmenu_path."ove_popups_left.php");?>
					</td>
					<td class="ove_mainmenu" colspan="2"><?php require( $ove_mainmenu_path."ove_mainmenu.php");?></td>
				</tr>
					<tr id="body_crumb">
						<td class="ove_crumb1">*CRUMB1*</td>
						<td class="ove_crumb2" colspan="2">
							<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "", array(
									"START_FROM" => "0",
									"PATH" => "",
									"SITE_ID" => "-"
								),
								false,
								Array('HIDE_ICONS' => 'Y')
							);?>
						</td>
					</tr>
				<tr id="body_main1">
					<td class="ove_left" rowspan="2">
						<div class="ove_categories">*CATEGORIES*</div>
						*LEFT*
						<div class="ove_news">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_DIR."include/news.php",
									"AREA_FILE_RECURSIVE" => "N",
									"EDIT_MODE" => "html",
								),
								false,
								Array('HIDE_ICONS' => 'Y')
							);?>
							<script>f_go(get_city_cook())</script>
						</div>
					</td>
					<td class="ove_pop_body" colspan="2">
						<?php require( $ove_mainmenu_path."ove_popups_body.php");?>
					</td>
				</tr>
				<tr id="body_main2">
					<td class="ove_mainbody" colspan="2">
						<h1><?=$APPLICATION->ShowTitle(false);?></h1>
						