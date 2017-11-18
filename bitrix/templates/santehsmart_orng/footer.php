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
    	<div id="bx-composite-banner" style="display: none"></div>
    </div>
</div>
<div class="footer-menu">
<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"footer_smart_catalog", 
	array(
		"ROOT_MENU_TYPE" => "left",
		"MENU_CACHE_TYPE" => "Y",
		"MENU_CACHE_TIME" => "36000000",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_THEME" => "site",
		"CACHE_SELECTED_ITEMS" => "N",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "1",
		"USE_EXT" => "Y",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"COMPONENT_TEMPLATE" => "footer_smart_catalog",
		"CHILD_MENU_TYPE" => "left"
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
</div>
    <div class="clear-both"></div>

    </div>
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
