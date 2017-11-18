<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
</div>
<div class="footer">
<div class="footer-inner">
<div class="footer-wrapper">
    <div class="footer-logo">Интернет-магазин сантехники<br>
		Сантехсмарт <?=date('Y')?> г.
    <div class="footer-social">
    	<a href="//vk.com/santehsmart_shop" target="_blank" class="footer-social-element vk"></a>
    	<a href="//www.facebook.com/profile.php?id=100004523179100" target="_blank" class="footer-social-element facebook"></a>
    	<a href="//twitter.com/SantehSmart" target="_blank" class="footer-social-element twitter"></a>
    	<a href="//ok.ru/group/52333318242439" class="footer-social-element ok"></a>

    </div>
</div>
<div class="footer-menu">
<?$APPLICATION->IncludeComponent("bitrix:menu", "footer_smart_catalog", Array(
	"ROOT_MENU_TYPE" => "left",	// Тип меню для первого уровня
		"MENU_CACHE_TYPE" => "A",	// Тип кеширования
		"MENU_CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
		"MENU_THEME" => "site",
		"CACHE_SELECTED_ITEMS" => "N",
		"MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
		"MAX_LEVEL" => "1",	// Уровень вложенности меню
		"USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
		"DELAY" => "N",	// Откладывать выполнение шаблона меню
		"ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
	),
	false
);?>
</div>
	<div class="footer-menu">
<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"footer_menu", 
	array(
		"ROOT_MENU_TYPE" => "bottom1",
		"MAX_LEVEL" => "1",
		"USE_EXT" => "N",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"COMPONENT_TEMPLATE" => "footer_menu",
		"CHILD_MENU_TYPE" => "left"
	),
	false
);?>
	</div>
	<div class="footer-menu">
<a href="//clck.yandex.ru/redir/dtype=stred/pid=47/cid=1248/*//market.yandex.ru/shop/276025/reviews/add"><img src="//clck.yandex.ru/redir/dtype=stred/pid=47/cid=1248/*//img.yandex.ru/market/informer7.png" border="0" alt="Оцените качество магазина на Яндекс.Маркете." /></a>
<!-- begin of Top100 code -->
<!-- end of Top100 code -->
</div>
<div id="bx-composite-banner"></div>
    </div>
</div>
</div>
<div class="footer-bar">
    <div class="footer-bar-wrapper">
        <?if(isPartnerClient()){?>
            <?/*$APPLICATION->IncludeComponent(
                "bitrix:sale.basket.basket.line",
                "smart_cart-footer1",
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
                    "POSITION_VERTICAL" => "top",
                    "COMPONENT_TEMPLATE" => "smart_cart-footer1",
                    "SHOW_AUTHOR" => "N",
                    "PATH_TO_REGISTER" => SITE_DIR."login/",
                    "PATH_TO_PROFILE" => SITE_DIR."personal/"
                ),
                false
            );*/?>
        <?}else{?>
        <div style="display: none"><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "//www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg xmlns="https://www.w3.org/2000/svg"><defs><style>.cls-1{fill:#fff}</style><style>.cls-1{fill:none;stroke:#fff;stroke-width:2px}</style></defs><symbol id="scalePart" viewBox="350 503 23 19"><g id="Symbol_2_1" data-name="Symbol 2 &#x2013; 1" transform="translate(-225.5 -191.5)"><path id="Rectangle_28" data-name="Rectangle 28" class="cls-1" transform="translate(575.5 694.5)" d="M0 0h1v19H0z"/><path id="Rectangle_29" data-name="Rectangle 29" class="cls-1" transform="translate(597.5 694.5)" d="M0 0h1v19H0z"/><path id="Rectangle_30" data-name="Rectangle 30" class="cls-1" transform="translate(586.5 704.5)" d="M0 0h1v8H0z"/></g></symbol><symbol id="slash" viewBox="105.275 430.311 20.45 21.378"><g id="Symbol_4_2" data-name="Symbol 4 &#x2013; 2" transform="translate(-995.5 -42.5)"><path id="Line_3" data-name="Line 3" class="cls-1" transform="translate(1101.5 473.5)" d="M19 0L0 20"/></g></symbol></svg></div>
                <!-- split modules/discountMetr -->
                <div class="discountMetr">
                    <div class="discountMetr__warpper">
                        <div class="discountMetr__title">
                            <h6>Скидометр</h6>
                            <div class="discountMetr__startPointWrapper">
                                <div class="discountMetr__startPoint"></div>
                            </div>
                        </div>
                        <div class="discountMetr__scaleWrapper">
                            <div class="discountMetr__scale__events"><span class="discountMetr__scale__event discountMetr__scale__event--250">Скидка  250 Р
          <svg class="icon ">
            <use xlink:href="#slash"></use>
          </svg></span><span class="discountMetr__scale__event discountMetr__scale__event--delivery">+ Бесплатная доставка
          <svg class="icon ">
            <use xlink:href="#slash"></use>
          </svg></span><span class="discountMetr__scale__event discountMetr__scale__event--500">+ Скидка  700 Р
          <svg class="icon ">
            <use xlink:href="#slash"></use>
          </svg></span><span class="discountMetr__scale__event discountMetr__scale__event--1000">+ Скидка 1400 Р
          <svg class="icon ">
            <use xlink:href="#slash"></use>
          </svg></span></div>
                            <div class="discountMetr__scale__wrapper">
                                <div class="discountMetr__scale_outer">
                                    <div class="discountMetr__scale_inner"></div>
                                </div>
                            </div>
                            <div class="discountMetr__scale__characters">
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                                <svg class="icon ">
                                    <use xlink:href="#scalePart"></use>
                                </svg>
                            </div>
                            <div class="discountMetr__scale__marks"><span class="discountMetr__scale__mark discountMetr__scale__mark--3000">3000 Р</span><span class="discountMetr__scale__mark discountMetr__scale__mark--6000">6000 Р</span><span class="discountMetr__scale__mark discountMetr__scale__mark--14000">11 000 Р</span><span class="discountMetr__scale__mark discountMetr__scale__mark--18000">18 000 Р</span></div>
                        </div>
                    </div>
                </div>
        <?}?>
        </div>
</div>


<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter26881656 = new Ya.Metrika({id:26881656,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/26881656" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<!-- Код тега ремаркетинга Google -->
<!--
С помощью тега ремаркетинга запрещается собирать информацию, по которой можно идентифицировать личность пользователя. Также запрещается размещать тег на страницах с контентом деликатного характера. Подробнее об этих требованиях и о настройке тега читайте на странице http://google.com/ads/remarketingsetup.
-->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 960219827;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<div style="display: none">
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
</div>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/960219827/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-65412588-1', 'auto');
  ga('send', 'pageview');
	</script>
<!-- {literal} -->
<!-- {/literal} -->

</body>
</html>
