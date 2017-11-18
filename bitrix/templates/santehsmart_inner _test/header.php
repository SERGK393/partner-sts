<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");
$wizTemplateId = COption::GetOptionString("main", "wizard_template_id", "eshop_adapt_horizontal", SITE_ID);
CUtil::InitJSCore();
CJSCore::Init(array("fx"));
CJSCore::Init(array("jquery"));
$curPage = $APPLICATION->G;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CHARSET?>"/>
    <link rel="shortcut icon" type="image/x-icon" href="<?=SITE_DIR?>favicon.ico" />
    <?
    $APPLICATION->ShowHead();
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/scripts.js");
    ?>

    <title><?$APPLICATION->ShowTitle()?></title>
</head>
<body>
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
        <div class="top-panel-city"><?$APPLICATION->IncludeComponent("twofingers:location", "orangerocket", Array(
	"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "3600",	// Время кеширования (сек.)
		"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
	),
	false
);?></div>
      <div class="top-panel-cart"><?$APPLICATION->IncludeComponent(
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
		"CACHE_TYPE" => "N",
		"POSITION_VERTICAL" => "top"
	),
	false
);?>  
</div>
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
<div class="top-color-line"></div>
<div class="wrapper">

    <a href="/">
        <div class="logo"></div>
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
    <div class="tel-num"><h2>8 800 500 13 84</h2>
    <span>Телефон в Воронеже</span></div>

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
		"CONTAINER_ID" => "search-container",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"SHOW_PREVIEW" => "Y",
		"PREVIEW_WIDTH" => "50",
		"PREVIEW_HEIGHT" => "50",
		"CONVERT_CURRENCY" => "Y",
		"ORDER" => "rank",
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
			0 => "7",
		),
		"TIME_BEGIN" => "10",
		"TIME_END" => "19",
		"SATURDAY_TIME_BEGIN" => "10",
		"SATURDAY_TIME_END" => "18",
		"SUNDAY_TIME_BEGIN" => "0",
		"SUNDAY_TIME_END" => "0",
		"HOLLYDAYS" => array(
		)
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
                "ALLOW_MULTI_SELECT" => "N"
            ),
            false
        );?>


        <div class="breadcrumbs"><?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb", 
	"orangerocket", 
	array(
		"START_FROM" => "0",
		"PATH" => "",
		"SITE_ID" => "-"
	),
	false,
	array(
		"HIDE_ICONS" => "N"
	)
);?></div>


    </div>
    <div class="clear-both"></div>
    <h3><?$APPLICATION->ShowTitle()?></h3>


