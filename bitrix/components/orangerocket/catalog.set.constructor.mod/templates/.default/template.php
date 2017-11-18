<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?><?
$intElementID = intval($arParams["ELEMENT_ID"]);
CJSCore::Init(array("popup"));
$countDefSetItems = count($arResult["SET_ITEMS"]["DEFAULT"]);
$blockWidth = 87/(1+$countDefSetItems);
?>

<div class="or_ext_products">
    <form id="ext_prod_list" name="ext_prod_list" method="post">
        <div class="ext_prod_element first">
            <input type="hidden" value="<?=$intElementID?>" id="elem_<?=$intElementID?>" checked>
            <label><?=GetMessage('EXT_PRODUCT_HEADER')?></label>
        </div>
        <?foreach($arResult["SET_ITEMS"]["ALL"] as $key => $arItem):
            if($arItem["PURCHASE_PRICE_VALUE"]>0):?>
            <div class="ext_prod_element <?=$arItem["GROUP"]?>">
                <div class="image">
                    <img src="<?=$arItem["PREVIEW_PICTURE"]['src']?>" height="35px" width="35px">
                    <div class="full-image" id="<?=$arItem["DETAIL_PICTURE"]?>" data-img-id="<?=$arItem["DETAIL_PICTURE"]?>"></div>
                </div>
                <div class="label">
                    <input type="checkbox<?/*=($arItem["GROUP"]&&$arItem["GROUP"]!='o')?'radio':'checkbox'*/?>" value="<?=$arItem["ID"]?>"
                           onchange="updateCatalogConstructor(this)"
                           id="elem_<?=$arItem["ID"]?>"
                           name="<?=$arItem["GROUP"]?$arItem["GROUP"]:$arItem["NAME"]?>"
                           data-pur-price="<?=$arItem["PURCHASE_PRICE_VALUE"]?>"
                           data-retail-price="<?=$arItem["RETAIL_PRICE_VALUE"]?>"
                           <?if($arItem["GROUP"]):
                           ?>data-group-name="<?
                           $spoilmess=GetMessage("EXT_PRODUCT_".strtoupper($arItem["GROUP"]));
                           echo $spoilmess?$spoilmess:$arItem["NAME"];
                           ?>"<?endif;?>>
                    <label for="elem_<?=$arItem["ID"]?>"><?=$arItem["NAME"]?> (+<?=$arItem["PURCHASE_PRICE_PRINT_VALUE"]?>)</label>
                </div>
            </div>
        <?endif;endforeach?>
    </form>
</div>

<?
$popupParams["AJAX_PATH"] = $this->GetFolder()."/ajax.php";
$popupParams["SITE_ID"] = SITE_ID;
$popupParams["CURRENT_TEMPLATE_PATH"] = $this->GetFolder();
$popupParams["MESS"] = array(
	"CATALOG_SET_POPUP_TITLE" => GetMessage("CATALOG_SET_POPUP_TITLE"),
	"CATALOG_SET_POPUP_DESC" => GetMessage("CATALOG_SET_POPUP_DESC"),
	"CATALOG_SET_BUY" => GetMessage("CATALOG_SET_BUY"),
	"CATALOG_SET_SUM" => GetMessage("CATALOG_SET_SUM"),
	"CATALOG_SET_DISCOUNT" => GetMessage("CATALOG_SET_DISCOUNT"),
	"CATALOG_SET_WITHOUT_DISCOUNT" => GetMessage("CATALOG_SET_WITHOUT_DISCOUNT"),
);
$popupParams["ELEMENT"] = $arResult["ELEMENT"];
$popupParams["SET_ITEMS"] = $arResult["SET_ITEMS"];
$popupParams["DEFAULT_SET_IDS"] = $arResult["DEFAULT_SET_IDS"];
$popupParams["ITEMS_RATIO"] = $arResult["ITEMS_RATIO"];
?>
<script>
    $('#ext_prod_list input[name=nog]').eq(0).attr('checked',true);
    $('#ext_prod_list input[name=gm]').bind('change',function(){
        if(!$(this).attr('checked')){
            $('#ext_prod_list input[name=spina]').attr('checked',false);
        }
    });
    $('#ext_prod_list input[name=spina]').bind('change',function(){
        if($(this).attr('checked')){
            var chkd=false;
            var egm=$('#ext_prod_list input[name=gm]');
            egm.each(function(){
                if($(this).attr('checked'))chkd=true;
            });
            if(!chkd){
                egm.eq(0).attr('checked',true);
                window.alert("Установка массажа спины невозможна без гидромассажной системы.\nГидромассаж выбран автоматически.");
            }
        }
    });
    
    var spoilsArray={}
    $('#ext_prod_list').find('input').each(function(){
        if($(this).attr('data-group-name')&&!spoilsArray['.'+$(this).attr('name')]){
            spoilsArray['.'+$(this).attr('name')]=$(this).attr('data-group-name')
            $(this).attr('data-group-name','')
        }
    })
    for (var key in spoilsArray){
        var value=spoilsArray[key]
        var spoil=$('<div class="spoiler"><p><span>'+value+'</span><span class="spo-open">(Раскрыть список)</span></p></div>')
        var func="$(this).parent().toggleClass('hide').find('"+key+"').each(function(){;$(this).delay($(this).index()*30).fadeToggle(150)})"
        spoil.find('p').eq(0).bind('click',new Function(func)).css({
            '-webkit-touch-callout': 'none',
            '-webkit-user-select': 'none',
            '-khtml-user-select': 'none',
            '-moz-user-select': 'none',
            '-ms-user-select': 'none',
            'user-select': 'none'
        })

        if(key.indexOf('panel')+1) $('#ext_prod_list').find('.first').eq(0).after(spoil)
        else if(key.indexOf('kar')+1) $('#ext_prod_list').find('.first').eq(0).next().after(spoil)
        else $('#ext_prod_list').find(key).eq(0).before(spoil)
        var isBigGroup=0//$('#ext_prod_list').find(key).size()>2
        $('#ext_prod_list').find(key).each(function (i){
            if(isBigGroup){
                $(this).css('display','none')
                spoil.addClass('hide')
            }
            $(this).appendTo(spoil).css({
                'border-bottom': 'none'
            })
        })
        if(spoil.find('input').size()==1){
            spoil.find('p').remove()
            spoil.find('div').css('border-top','')
        }
    }

    $('#ext_prod_list').find('.full-image').hide()
    $('#ext_prod_list').find('img').bind({
        'mouseover':function(){
            if($(this).css('cursor')!='wait' && $(this).next().css('background-image')=='none'){
                $('#ext_prod_list').data('popup',$(this).next().attr('id'))
                getFullImage($(this).next().attr('data-img-id'),$(this).css('cursor','wait').next().attr('id'))
            }else {
                $('#ext_prod_list').data('popup',$(this).next().show().attr('id'))
            }
        },
        'mouseout':function(){
            $('#'+$('#ext_prod_list').data('popup')).hide()
            $('#ext_prod_list').data('popup',null)
        }
    })

    function getFullImage(imgid,fullid){
        BX.ajax.post(
            '<?=$popupParams["AJAX_PATH"]?>',
            {
                sessid : BX.bitrix_sessid(),
                action : "ajax_get_full_image",
                imgID : imgid
            },
            function(result){
                var json = JSON.parse(result);
                if (json.src)$('#'+fullid).css({
                    'background-image':'url('+json.src+')',
                    'width':json.width,
                    'height':json.height
                }).prev().delay(500).css('cursor','default')
                $('#'+$('#ext_prod_list').data('popup')).show()
            }
        )
    }
</script>
<script>
    var or_cur_price_doc= document.getElementsByClassName('item_current_price')[0];
    var or_cur_price2_doc=0;
    var Box=document.getElementById('ext_prod_list').getElementsByTagName('input');
    var or_cur_price2=0;
    if(!or_cur_price_doc){
        or_cur_price_doc=document.getElementsByClassName('mop_price')[0];
        or_cur_price2_doc=document.getElementsByClassName('rrc_price')[0];
        or_cur_price2=or_cur_price2_doc.innerHTML;
    }
    var or_cur_price= or_cur_price_doc.innerHTML;

    function strNum(x) {
        var r = "";
        for(var n=0; n<x.length; n++) {
            var base= x.charAt(n);
            if (base!=' '&&isNaN(base)==false||base==',')
                r = r + base;
        }
        return parseFloat(r.replace(/,/g,"."));
    }
    function strPrice(x) {
        x = x.toString();
        var y = x.charAt(0);
        for(var n=1; n<x.length; n++) {
            if (Math.ceil((x.length-n)/3) == (x.length-n)/3) y = y + " ";
            y = y + x.charAt(n);
        }
        return y;
    }
    function adPrice(x,type) {
        /*var add=x.parentNode.getElementsByTagName('label')[0].innerHTML;
        var n=add.lastIndexOf("(");
        if(n<0||(add.lastIndexOf("+")<=n)) return "";
        var operand=add.slice(n+1,n+2);
        return operand+strNum(add.slice(n));*/
        var add = x.getAttribute('data-'+type+'-price');
        var operand='+';
        return operand+add;
    }
    function sumBox() {
        var price=strNum(or_cur_price);
        for(var n=0; n<Box.length; n++) {
            if(Box[n].checked)price=eval(price+adPrice(Box[n],'pur'));
        }
        or_cur_price_doc.innerHTML=or_cur_price.replace(strPrice(strNum(or_cur_price)), strPrice(price));

        if(or_cur_price2_doc) {
            price = strNum(or_cur_price2);
            for (n = 0; n < Box.length; n++) {
                if (Box[n].checked) price = eval(price + adPrice(Box[n],'retail'));
            }
            or_cur_price2_doc.innerHTML = or_cur_price2.replace(strPrice(strNum(or_cur_price2)), strPrice(price));
        }

    }

    /*BX.ready(function() {
        setTimeout(function(){
            or_cur_price_doc= document.getElementsByClassName('item_current_price')[0];
            Box=document.getElementById('ext_prod_list').getElementsByTagName('input');
            or_cur_price= vmStr.innerHTML;
            if(!or_cur_price_doc){
                or_cur_price_doc=document.getElementsByClassName('mop_price')[0];
                or_cur_price2_doc=document.getElementsByClassName('rrc_price')[0];
            }
            sumBox();
        },2000);
    });*/
</script>
<script>
	BX.message({
		setItemAdded2Basket: '<?=GetMessageJS("CATALOG_SET_ADDED2BASKET")?>',
		setButtonBuyName: '<?=GetMessageJS("CATALOG_SET_BUTTON_BUY")?>',
		setButtonBuyUrl: '<?=$arParams["BASKET_URL"]?>',
		setIblockId: '<?=$arParams["IBLOCK_ID"]?>',
		setOffersCartProps: <?=CUtil::PhpToJSObject($arParams["OFFERS_CART_PROPERTIES"])?>
	});

	BX.ready(function(){
        updateCatalogConstructor();
        var avail='<?=$arParams["AVAILABLE"]?>';
        if(avail){
            var buyButton=document.getElementById('<?=$arParams['BX_ID'].'_buy_link'?>');
            buyButton.setAttribute('id','<?=$arParams['BX_ID'].'_buy_button'?>');
            buyButton.setAttribute('onclick','catalogSetDefaultObj_<? echo $intElementID; ?>.Add2Basket();');
        }
	});

    function newCatalogConstructor(productIds){
        return new catalogSetConstructDefault(
            productIds,
            '<? echo $this->GetFolder(); ?>/ajax.php',
            '<?=$arResult["ELEMENT"]["PRICE_CURRENCY"]?>',
            '<?=SITE_ID?>',
            '<?=$intElementID?>',
            '<?=(isset($arResult["ELEMENT"]["DETAIL_PICTURE"]["src"]) ? $arResult["ELEMENT"]["DETAIL_PICTURE"]["src"] : $this->GetFolder().'/images/no_foto.png')?>',
            <?=CUtil::PhpToJSObject($arResult["ITEMS_RATIO"])?>,
            '<?=$arParams['BX_ID'].'_quantity'?>',
            '<?=$arResult['PARTNER_ID']?>'
        );
    }

    function updateCatalogConstructor(elm){
        if(elm){
            var spoil=$(elm).parent().parent().parent();
            if(spoil.hasClass('spoiler')){
                var elem=$(elm);
                spoil.find('input').each(function(){
                    if($(this).attr('id')!=elem.attr('id')){
                        $(this).prop("checked", false);
                    }
                });
            }
        }
        
        var productIds=[];
        var form=document.getElementById('ext_prod_list');
        var elems=form.getElementsByTagName('input');
        for (var i=0;i<elems.length;i++){
            var el=elems[i];
            if(el){
                if(el.checked){
                    productIds.push(el.value);
                }
            }
        }
        
        //jquery for fast_buy
        $('#fast-buy-container input[name="prod_add_id"]').attr('value',JSON.stringify(productIds));
        //end jquery
        
        catalogSetDefaultObj_<?=$intElementID; ?> = newCatalogConstructor(productIds);
        sumBox();
    }
/*
    function uncheckProduct(el){
        var button=el.parentNode.getElementsByTagName('input')[0].checked=false;
        el.style.display='';
        sumBox();
    }
*/
	if (!window.arSetParams)
	{
		window.arSetParams = [{'<?=$intElementID?>' : <?echo CUtil::PhpToJSObject($popupParams)?>}];
	}
	else
	{
		window.arSetParams.push({'<?=$intElementID?>' : <?echo CUtil::PhpToJSObject($popupParams)?>});
	}

	function OpenCatalogSetPopup(element_id)
	{
		if (window.arSetParams)
		{
			for(var obj in window.arSetParams)
			{
				if (window.arSetParams.hasOwnProperty(obj))
				{
					for(var obj2 in window.arSetParams[obj])
					{
						if (window.arSetParams[obj].hasOwnProperty(obj2))
						{
							if (obj2 == element_id)
								var curSetParams = window.arSetParams[obj][obj2]
						}
					}
				}
			}
		}

		BX.CatalogSetConstructor =
		{
			bInit: false,
			popup: null,
			arParams: {}
		};
		BX.CatalogSetConstructor.popup = BX.PopupWindowManager.create("CatalogSetConstructor_"+element_id, null, {
			autoHide: false,
			offsetLeft: 0,
			offsetTop: 0,
			overlay : true,
			draggable: {restrict:true},
			closeByEsc: false,
			closeIcon: { right : "12px", top : "10px"},
			titleBar: {content: BX.create("span", {html: "<div><?=GetMessage("CATALOG_SET_POPUP_TITLE_BAR")?></div>"})},
			content: '<div style="width:250px;height:250px; text-align: center;"><span style="position:absolute;left:50%; top:50%"><img src="<?=$this->GetFolder()?>/images/wait.gif"/></span></div>',
			events: {
				onAfterPopupShow: function()
				{
					BX.ajax.post(
						'<? echo $this->GetFolder(); ?>/popup.php',
						{
							lang: BX.message('LANGUAGE_ID'),
							site_id: BX.message('SITE_ID') || '',
							arParams:curSetParams
						},
						BX.delegate(function(result)
						{
							this.setContent(result);
							BX("CatalogSetConstructor_"+element_id).style.left = (window.innerWidth - BX("CatalogSetConstructor_"+element_id).offsetWidth)/2 +"px";
							var popupTop = document.body.scrollTop + (window.innerHeight - BX("CatalogSetConstructor_"+element_id).offsetHeight)/2;
							BX("CatalogSetConstructor_"+element_id).style.top = popupTop > 0 ? popupTop+"px" : 0;
						},
						this)
					);
				}
			}
		});

		BX.CatalogSetConstructor.popup.show();
	}
</script>
