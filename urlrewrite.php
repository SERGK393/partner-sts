<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/bitrix/services/ymarket/order/accept#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/bitrix/services/ymarket1/index.php",
	),
	array(
		"CONDITION" => "#^/bitrix/services/ymarket/order/status#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/bitrix/services/ymarket1/index.php",
	),
	array(
		"CONDITION" => "#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#",
		"RULE" => "alias=\$1",
		"ID" => "bitrix:im.router",
		"PATH" => "/desktop_app/router.php",
	),
	array(
		"CONDITION" => "#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#",
		"RULE" => "alias=\$1",
		"ID" => "",
		"PATH" => "/desktop_app/router.php",
	),
	array(
		"CONDITION" => "#^/bitrix/services/ymarket/cart#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/bitrix/services/ymarket1/index.php",
	),
	array(
		"CONDITION" => "#^/site_lc/personal/order/#",
		"RULE" => "",
		"ID" => "bitrix:sale.personal.order",
		"PATH" => "/site_lc/personal/order/index.php",
	),
	array(
		"CONDITION" => "#^/online/(/?)([^/]*)#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/desktop_app/router.php",
	),
	array(
		"CONDITION" => "#^/online/(/?)([^/]*)#",
		"RULE" => "",
		"ID" => "bitrix:im.router",
		"PATH" => "/desktop_app/router.php",
	),
	array(
		"CONDITION" => "#^/stssync/calendar/#",
		"RULE" => "",
		"ID" => "bitrix:stssync.server",
		"PATH" => "/bitrix/services/stssync/calendar/index.php",
	),
	array(
		"CONDITION" => "#^/site_lc/catalog/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => "/site_lc/catalog/index.php",
	),
	array(
		"CONDITION" => "#^/personal/order/#",
		"RULE" => "",
		"ID" => "bitrix:sale.personal.order",
		"PATH" => "/personal/order/index.php",
	),
	array(
		"CONDITION" => "#^/site_lc/store/#",
		"RULE" => "",
		"ID" => "bitrix:catalog.store",
		"PATH" => "/site_lc/store/index.php",
	),
	array(
		"CONDITION" => "#^/site_lc/news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/site_lc/news/index.php",
	),
	array(
		"CONDITION" => "#^/catalog/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => "/catalog/index.php",
	),
	array(
		"CONDITION" => "#^/vanny/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => "/vanny/index.php",
	),
	array(
		"CONDITION" => "#^/store/#",
		"RULE" => "",
		"ID" => "bitrix:catalog.store",
		"PATH" => "/store/index.php",
	),
	array(
		"CONDITION" => "#^/maps/#",
		"RULE" => "",
		"ID" => "speak:maps",
		"PATH" => "/maps/index.php",
	),
	array(
		"CONDITION" => "#^/news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/news/index.php",
	),
);

?>