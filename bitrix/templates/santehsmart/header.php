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
    <link href='http://fonts.googleapis.com/css?family=Russo+One&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link rel="sho
    rtcut icon" type="image/x-icon" href="<?=SITE_DIR?>favicon.ico" />
    <?
    $APPLICATION->ShowHead();
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/scripts.js");
    ?>

    <title><?$APPLICATION->ShowTitle()?></title>
</head>
<body>
<?$APPLICATION->IncludeComponent("bitrix:pull.request", "composite");?>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
<div class="top-panel">
    <div class="top-panel-wrapper">
        <div class="top-panel-city"><?$APPLICATION->IncludeComponent("twofingers:location","",Array());?></div>
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
		"POSITION_VERTICAL" => "top"
	),
	false
	);?><a class='fix_cart' id="fix_cart" href="personal/cart/"></a></div>
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
        <div class="logo"><img src="/bitrix/templates/santehsmart/images/template/santehsmart_logo.png" ></div>
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
    <h1 class="tel-num">8 800 2000 500</h1>

    <div class="clear-both"></div>
    <div class="slogan"></div>
    <div class="search-container">
       <?$APPLICATION->IncludeComponent("bitrix:search.title", "santehsmart-search", Array(
	"NUM_CATEGORIES" => "1",	// Количество категорий поиска
		"TOP_COUNT" => "5",	// Количество результатов в каждой категории
		"CHECK_DATES" => "N",	// Искать только в активных по дате документах
		"SHOW_OTHERS" => "N",	// Показывать категорию "прочее"
		"PAGE" => SITE_DIR."catalog/",	// Страница выдачи результатов поиска (доступен макрос #SITE_DIR#)
		"CATEGORY_0_TITLE" => GetMessage("SEARCH_GOODS"),	// Название категории
		"CATEGORY_0" => array(	// Ограничение области поиска
			0 => "iblock_catalog",
		),
		"CATEGORY_0_iblock_catalog" => array(	// Искать в информационных блоках типа "iblock_catalog"
			0 => "10",
		),
		"CATEGORY_OTHERS_TITLE" => GetMessage("SEARCH_OTHER"),
		"SHOW_INPUT" => "Y",	// Показывать форму ввода поискового запроса
		"INPUT_ID" => "title-search-input",	// ID строки ввода поискового запроса
		"CONTAINER_ID" => "search",	// ID контейнера, по ширине которого будут выводиться результаты
		"PRICE_CODE" => array(	// Тип цены
			0 => "BASE",
		),
		"SHOW_PREVIEW" => "Y",	// Показать картинку
		"PREVIEW_WIDTH" => "75",	// Ширина картинки
		"PREVIEW_HEIGHT" => "75",	// Высота картинки
		"CONVERT_CURRENCY" => "Y",	// Показывать цены в одной валюте
		"ORDER" => "date",	// Сортировка результатов
		"USE_LANGUAGE_GUESS" => "Y",	// Включить автоопределение раскладки клавиатуры
		"PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
		"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода
		"CURRENCY_ID" => "RUB",	// Валюта, в которую будут сконвертированы цены
	),
	false
);?>
    </div>
    <div class="worktime">Звонок по России бесплатный
        <div worktime-ins>Понедельник-Пятница  9-19ч.<br>Суббота-Воскресенье 9-18ч.</div>


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


        <div class="banner-feuters row-left">
        	<div class="main-banner">
                <?$APPLICATION->IncludeComponent(
	"orangerocket:slider", 
	".default", 
	array(
		"AJAX_MODE" => "N",
		"IBLOCK_TYPE" => "services",
		"IBLOCK_ID" => "16",
		"NEWS_COUNT" => "20",
		"SORT_BY" => "ACTIVE_FROM",
		"SORT_ORDER" => "DESC",
		"PROPERTY_CODE" => array(
			0 => "url",
		),
		"PICTURE_WIDTH" => "",
		"PICTURE_HEIGHT" => "",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_NOTES" => "",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CHANGE_TIME" => "5000"
	),
	false
);?>
        	</div>
        	<div class="features">
        		<div class="f1-title f1"> <h5>Смарт Цены</h5></div>
        		<div class="f-content f1">Если вы нашли интерисующий вас товар 
        			в другом интернет-магазине.
        			Мы готовы сделать вам более выгодное предложение <em class="else">Подробнее</em>
        		</div>
        	</div>
        	<div class="features ">
        		<div class="f2-title f2"><h5>Доставка</h5></div>
        		<div class="f-content f2">Если вы нашли интерисующий вас товар 
        			в другом интернет-магазине.
        			Мы готовы сделать вам более выгодное предложение <em class="else">Подробнее</em></div>
        	</div>
        </div>
        <div class="main-item">
        <h4>Лучшие <br/>предложения</h4>
        <div></div>
        <span>L2237 В смеситель для ванны с лейкой.</span>
        <span class="main-item-price">850 Р<span>
        </div>
     

    </div>
    <div class="clear-both"></div>


