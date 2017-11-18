<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");
$wizTemplateId = COption::GetOptionString("main", "wizard_template_id", "eshop_adapt_horizontal", SITE_ID);
CUtil::InitJSCore();
CJSCore::Init(array("fx"));
CJSCore::Init(array("jquery"));
$curPage = $APPLICATION->G;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="yandex-verification" content="88d96bd43b32acc4" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CHARSET?>"/>
    <link rel="shortcut icon" type="image/x-icon" href="<?=SITE_DIR?>favicon.ico" />
<link href='https://fonts.googleapis.com/css?family=PT+Sans&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
    <?
    $APPLICATION->ShowHead();
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/scripts.js");
    ?>

    <title><?$APPLICATION->ShowTitle()?></title>
<script src="//api-maps.yandex.ru/1.1/index.xml?loadByRequire=1" type="text/javascript"></script>
</head>
<body>
<!-- Rating@Mail.ru counter -->
<script type="text/javascript">
	var _tmr = _tmr || [];
	_tmr.push({id: "2688147", type: "pageView", start: (new Date()).getTime()});
	(function (d, w, id) {
		if (d.getElementById(id)) return;
		var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
		ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
		var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
		if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
	})(document, window, "topmailru-code");
</script><noscript><div style="position:absolute;left:-10000px;">
		<img src="//top-fwz1.mail.ru/counter?id=2688147;js=na" style="border:0;" height="1" width="1" alt="Рейтинг@Mail.ru" />
	</div></noscript>
<!-- //Rating@Mail.ru counter -->

<!-- Rating@Mail.ru counter -->
<script type="text/javascript">
var _tmr = _tmr || [];
_tmr.push({id: "2580829", type: "pageView", start: (new Date()).getTime()});
(function (d, w) {
   var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true;
   ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
   var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
   if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
})(document, window);
</script><noscript><div style="position:absolute;left:-10000px;">
<img src="//top-fwz1.mail.ru/counter?id=2580829;js=na" style="border:0;" height="1" width="1" alt="Рейтинг@Mail.ru" />
</div></noscript>
<!-- //Rating@Mail.ru counter -->
<?$APPLICATION->IncludeComponent("bitrix:pull.request", "composite");?>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
<div class="top-panel">
    <div class="top-panel-wrapper">
		<div class="top-panel-city"><?$APPLICATION->IncludeComponent(
	"twofingers:location",
	"orangerocket",
	array(
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"SET_TITLE" => "Y"
	),
	false
);?></div>
<div id="top-panel-del-points-cnt">
	<?$APPLICATION->IncludeComponent(
	"orangerocket:delivery_points_mark",
	".default",
	Array(
		"IBLOC_ID" => "18"
	)
);?>
</div>
        <div class="top-panel-cart" title="Корзина"><?$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket.line", 
	"smart_cart",
	array(
		"PATH_TO_BASKET" => SITE_DIR."personal/cart/",
		"PATH_TO_PERSONAL" => SITE_DIR."personal/",
		"SHOW_PERSONAL_LINK" => "N",
		"SHOW_NUM_PRODUCTS" => "Y",
		"SHOW_TOTAL_PRICE" => "Y",
		"SHOW_PRODUCTS" => "N",
		"POSITION_FIXED" => "N",
		"SHOW_EMPTY_VALUES" => "N",
		"PATH_TO_ORDER" => SITE_DIR."personal/order/make/",
		"SHOW_DELAY" => "Y",
		"SHOW_NOTAVAIL" => "N",
		"SHOW_SUBSCRIBE" => "Y",
		"SHOW_IMAGE" => "Y",
		"SHOW_PRICE" => "Y",
		"SHOW_SUMMARY" => "N",
		"BUY_URL_SIGN" => "action=ADD2BASKET",
		"POSITION_HORIZONTAL" => "right",
		"POSITION_VERTICAL" => "top"
	),
	false
	);?><a class='fix_cart' id="fix_cart" href="personal/cart/"></a></div>
        <a href="/personal/cart/"><span class="top-panel-cart-pre" title="Корзина"></span></a>
        <div class="top-panel-what"><b><a href="/what/">Узнать статус заказа</a></b></div>
        <div class="top-panel-sign-reg"><b><?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "eshop_adapt", array(
										"REGISTER_URL" => SITE_DIR."login/",
										"PROFILE_URL" => SITE_DIR."personal/",
										"SHOW_ERRORS" => "N"
									),
									false,
									array()
								);?></b></div>

    </div>
</div>
<div class="wrapper">

    <a href="/">
		<div class="logo"><h1>Купить Сантехнику в Вороннеже в Интернет-Магазине Сантехсмарт</h1></div>
    </a>
    <nav>
        <?$APPLICATION->IncludeComponent(
	"bitrix:menu",
	"top_menu",
	Array(
		"ROOT_MENU_TYPE" => "top",
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "left",
		"USE_EXT" => "N",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array()
	)
);?>
    </nav>
    <div class="tel-num">

		<? if ($_SESSION['TF_LOCATION_SELECTED_CITY_NAME']!=='Воронеж'): ?>
			<h2>8 800 500 13 84</h2>
			<span>Звонок по России бесплатный</span>
			<? else: ?>
			<span style="font-size: 31px;
    					display: block;
    					padding-bottom: 12px;">+7(473) 300-36-85</span>
			<span>8 800 500 13 84 (Бесплатно по России)</span>
		<? endif ?>
	</div>

    <div class="clear-both"></div>
    <div class="slogan">Каталог товаров</div>
    <div class="search-container">
       <?$APPLICATION->IncludeComponent(
	"bitrix:search.title",
	"santehsmart-search",
	array(
		"NUM_CATEGORIES" => "1",
		"TOP_COUNT" => "5",
		"CHECK_DATES" => "N",
		"SHOW_OTHERS" => "N",
		"PAGE" => SITE_DIR."catalog/",
		"CATEGORY_0_TITLE" => GetMessage("SEARCH_GOODS"),
		"CATEGORY_0" => array(
			0 => "iblock_catalog",
		),
		"CATEGORY_0_iblock_catalog" => array(
			0 => "10",
		),
		"CATEGORY_OTHERS_TITLE" => GetMessage("SEARCH_OTHER"),
		"SHOW_INPUT" => "Y",
		"INPUT_ID" => "title-search-input",
		"CONTAINER_ID" => "search",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"SHOW_PREVIEW" => "N",
		"PREVIEW_WIDTH" => "75",
		"PREVIEW_HEIGHT" => "75",
		"CONVERT_CURRENCY" => "Y",
		"ORDER" => "date",
		"USE_LANGUAGE_GUESS" => "Y",
		"PRICE_VAT_INCLUDE" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"CURRENCY_ID" => "RUB"
	),
	false
);?>
    </div>
    <div class="worktime">
    <?$APPLICATION->IncludeComponent(
	"orangerocket:call_us", 
	".default", 
	array(
		"NO_CALL_WEEKDAY" => array(
			0 => "5",
			1 => "6",
			2 => "7",
		),
		"TIME_BEGIN" => "10",
		"TIME_END" => "18",
		"SATURDAY_TIME_BEGIN" => "10",
		"SATURDAY_TIME_END" => "18",
		"SUNDAY_TIME_BEGIN" => "0",
		"SUNDAY_TIME_END" => "0",
		"HOLLYDAYS" => array(
			0 => "31.12.15-1.1.16-10.13",
			1 => "1.1.15-3.1.15-0.0",
			2 => "3.1.16-7.1.16-9.17",
			3 => "7.1.16-8.1.16-0.0",
			4 => "8.1.16-11.1.16-9.18",
			5 => "",
		),
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>


    </div>
    <div class="clear-both"></div>
    <div class="menu-banner-features">
        <?$APPLICATION->IncludeComponent(
	"bitrix:menu",
	"smart_catalog",
	array(
		"ROOT_MENU_TYPE" => "left",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "36000000",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_THEME" => "site",
		"CACHE_SELECTED_ITEMS" => "N",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "3",
		"CHILD_MENU_TYPE" => "left",
		"USE_EXT" => "Y",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"COMPONENT_TEMPLATE" => "smart_catalog"
	),
	false
);?>


        <div class="banner-feuters row-left">
        	<div class="main-banner">
               <?$APPLICATION->IncludeComponent(
	"orangerocket:NormalNewsSlider",
	"",
Array(),
false
);?>
        	</div>
        </div>
        <div class="main-item">
        <h4>Лучшие <br/>предложения</h4>
        <?$APPLICATION->IncludeComponent(
	"orangerocket:tov_slider",
	".default",
	array(
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "10",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"TOV_PROP" => "product_sku",
		"PICTURE_WIDTH" => "200",
		"PICTURE_HEIGHT" => "165",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "Y",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "Y"
	),
	false
);?>
        </div>


    </div>
    <div class="clear-both"></div>
    <div class="features-cheaps">
	    <div class="cheap delivery">
		    <h6>Доставка по России</h6>
		    <p>Пункты выдачи в 170 городах</p>
	    </div>
	    <div class="cheap check">
		    <h6>Проверяй и забирай</h6>
		    <p>Оплачивайте заказ после проверки</p>
	    </div>
	    <div class="cheap discount">
		     <h6>Гарантия низких цен</h6>
		     <p>Нашли дешевле? Мы сделаем скидку</p>

	    </div>
	    <div class="cheap moneyback">
		    <h6>Обмен и возврат</h6>
		    <p>Лояльная покупателю программа MoneyBack</p>
	    </div>
    </div>
