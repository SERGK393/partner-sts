function catalogSetConstructDefault(arSetIds, ajax_path, price_currency, lid, element_id, detail_img, items_ratio, items_quantity_id, partner_id)
{
	this.arSetIDs = arSetIds;
	this.ajax_path = ajax_path;
	this.price_currency = price_currency;
	this.lid = lid;
	this.element_id = element_id;
	this.detail_img = detail_img;
	this.items_ratio = items_ratio;
	this.items_quantity_id = items_quantity_id;
	this.partner_id = partner_id;
}

var popupMail=null;

catalogSetConstructDefault.prototype.Add2Basket = function()
{
	var detail_img = this.detail_img;
	var element_id = this.element_id;
	var items_quantity = BX(this.items_quantity_id);
	var main_quantity = $('.stock > span:nth-child(1)').text();
	if(items_quantity)
        this.items_ratio = items_quantity.value;
    var partnerButton = $('.toOrder');
    console.log(this.partner_id);
    if(partnerButton.hasClass('active') || !this.partner_id) {
        if (!partnerButton.hasClass('succes')) {
            if (main_quantity > 0 || !this.partner_id) {
                partnerButton.removeClass('active');
                partnerButton.html('<svg width="50px"  height="50px"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-rolling" style="background: none;">'+
                    '<circle cx="50" cy="50" fill="none" ng-attr-stroke="{{config.color}}" ng-attr-stroke-width="{{config.width}}" ng-attr-r="{{config.radius}}" ng-attr-stroke-dasharray="{{config.dasharray}}" stroke="#ffb175" stroke-width="12" r="18" stroke-dasharray="84.82300164692441 30.274333882308138" transform="rotate(186 50 50)">'+
                    '<animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform>'+
                    '</circle> </svg>');

                obj = this;
                BX.ajax.post(
                    this.ajax_path,
                    {
                        sessid: BX.bitrix_sessid(),
                        action: 'catalogSetAdd2Basket',
                        set_ids: this.arSetIDs,
                        lid: this.lid,
                        iblockId: BX.message('setIblockId'),
                        setOffersCartProps: BX.message('setOffersCartProps'),
                        itemsRatio: this.items_ratio
                    },
                    function (result) {
                        if (obj.partner_id){
                            partnerButton.addClass('succes').text('В заявке →').wrap('<a href="/personal/cart/"></a>');
                        } else showCatalogSetAdd2BasketPopup(detail_img, element_id);
                        console.log([result, obj.items_ratio, obj.partner_id]);
                    }
                );
            } else {
                if (popupMail == null) {
                    //uses jquery
                    var sku = $('dl.sku dd').html();
                    if (!sku) sku = $('.sku').html();
                    var name = $('.wrapper h1').eq(0).html();
                    var partnerID = this.partner_id;
                    var formContent = BX.create("span", {html: '...'});
                    BX.ajax.post(
                        '/bitrix/templates/santehsmart_inner/components/bitrix/catalog/smart_catalog/bitrix/catalog.element/.default/mailform.php',
                        {
                            sessid: BX.bitrix_sessid(),
                            sku: sku,
                            name: name,
                            partnerID: partnerID
                        },
                        function (message) {
                            formContent.innerHTML = message;
                        }
                    );
                    popupMail = new BX.PopupWindow("my_answer", window.body, {
                        content: formContent,
                        titleBar: {
                            content: BX.create("span", {
                                html: '<b>Узнать о поступлении</b>',
                                'props': {'className': 'access-title-bar'}
                            })
                        },
                        zIndex: 0,
                        offsetLeft: 0,
                        offsetTop: 0,
                        draggable: {restrict: false},
                        overlay: true,
                        buttons: [
                            new BX.PopupWindowButton({
                                text: "Отправить запрос",
                                className: "popup-window-button-accept",
                                events: {
                                    click: function () {
                                        var buy = $('form[name=or_mailform]');
                                        if (!partnerID) {
                                            var n = $('input[name=var_num]', buy).val();
                                            if (n.length != 18) {
                                                window.alert("Введите номер телефона,\nнапример: +7 (123) 123-45-67\n");
                                                return;
                                            }
                                            var str = $('input[name=var_name]', buy).val();
                                            if (str.length < 2 || /[0-9]/.test(str)) {
                                                window.alert("Введите свое имя");
                                                return;
                                            }
                                        }
                                        BX.ajax.submit(buy[0], function (data) { // отправка данных из формы с id="myForm" в файл из action="..."
                                            formContent.innerHTML = data;
                                            $('.popup-window-button-accept').remove();
                                        });
                                    }
                                }
                            }),
                            new BX.PopupWindowButton({
                                text: "Закрыть",
                                className: "webform-button-link-cancel",
                                events: {
                                    click: function () {
                                        popupMail = this.popupWindow;
                                        this.popupWindow.close(); // закрытие окна
                                    }
                                }
                            })
                        ]
                    }).show();
                } else {
                    popupMail.show();
                }
            }
        }
    }
}

catalogSetConstructDefault.prototype.DeleteItem = function(element, item_id)
{
	var wrapObj = element.parentNode;
	BX.remove(element);

	for(var i = 0, l = this.arSetIDs.length; i < l; i++)
	{
		if (this.arSetIDs[i] == item_id)
		{
			this.arSetIDs.splice(i,1);
		}
	}

	var sumPrice = +BX.firstChild(wrapObj).getAttribute("data-price");
	var sumOldPrice = +BX.firstChild(wrapObj).getAttribute("data-old-price");
	var sumDiffDiscountPrice = +BX.firstChild(wrapObj).getAttribute("data-discount-diff-price");

	var setItems = BX.findChildren(wrapObj, {className: "bx_default_set_items"}, true);

	if (!setItems.length)
	{
		BX.removeClass(BX.firstChild(wrapObj), "plus");
		BX.addClass(BX.firstChild(wrapObj), "equally");
	}
	else
	{
		for (var i=0; i<setItems.length; i++)
		{
			if (i == setItems.length-1)
			{
				BX.removeClass(setItems[i], "plus");
				BX.addClass(setItems[i], "equally");
			}
			sumPrice += +setItems[i].getAttribute("data-price");
			sumOldPrice += +setItems[i].getAttribute("data-old-price");
			sumDiffDiscountPrice += +setItems[i].getAttribute("data-discount-diff-price");
		}
	}
	BX.ajax.post(
		this.ajax_path,
		{
			sessid : BX.bitrix_sessid(),
			action : "ajax_recount_prices",
			sumPrice : sumPrice,
			sumOldPrice : sumOldPrice,
			sumDiffDiscountPrice : sumDiffDiscountPrice,
			currency : this.price_currency
		},
		function(result)
		{
			var json = JSON.parse(result);
			if (json.formatSum)
			{
				BX.findChild(wrapObj, {className:"bx_item_set_current_price"}, true, false).innerHTML = json.formatSum;
			}
			if (json.formatOldSum)
			{
				BX.findChild(wrapObj, {className:"bx_item_set_old_price"}, true, false).innerHTML = json.formatOldSum;
			}
			else
			{
				BX.findChild(wrapObj, {className:"bx_item_set_old_price"}, true, false).style.display = "none";
			}
			if (json.formatDiscDiffSum)
			{
				BX.findChild(wrapObj, {className:"bx_set_discount_diff_price"}, true, false).innerHTML = json.formatDiscDiffSum;
			}
			else
			{
				BX.findChild(wrapObj, {className:"bx_item_set_economy_price"}, true, false).style.display = "none";
			}
		}
	);
}

function catalogSetConstructPopup(ItemsCount, ItemsWidth, Currency, DefaultItemPrice, DefaultItemDiscountPrice, DefaultItemDiscountDiffPrice, ajaxPath, setIds, lid, element_id, items_ratio, detail_img)
{
	this.catalogSetItemsCount = ItemsCount;
	this.catalogSetItemsWidth = ItemsWidth;
	this.catalogCurrency = Currency;
	this.catalogDefaultItemPrice = DefaultItemPrice;
	this.catalogDefaultItemDiscountPrice = DefaultItemDiscountPrice;
	this.catalogDefaultItemDiscountDiffPrice = DefaultItemDiscountDiffPrice;
	this.ajaxPath = ajaxPath;
	this.catalogSetIds = setIds;
	this.lid = lid;
	this.element_id = element_id;
	this.items_ratio = items_ratio;
	this.detail_img = detail_img;
}

catalogSetConstructPopup.prototype.scrollItems = function(direction)
{
	if (direction == 'left')
	{
		var curLeftPercent = BX("bx_catalog_set_construct_slider_"+this.element_id).getAttribute('data-style-left');
		if (curLeftPercent >= 0)
			return;
		var leftPercent = +(curLeftPercent)+20;
	}
	else
	{
		var curLeftPercent = BX("bx_catalog_set_construct_slider_"+this.element_id).getAttribute('data-style-left');
		if (-curLeftPercent >= (this.catalogSetItemsCount - 5)*this.catalogSetItemsWidth)
			return;
		var leftPercent = +(curLeftPercent)-20;
	}
	BX("bx_catalog_set_construct_slider_"+this.element_id).setAttribute('data-style-left', leftPercent);
	BX("bx_catalog_set_construct_slider_"+this.element_id).style.left = leftPercent+'%';
}

catalogSetConstructPopup.prototype.recountSlider = function(action)
{
	if (action == 'add')
	{
		this.catalogSetItemsCount -= 1;
	}
	else if (action == 'delete')
	{
		this.catalogSetItemsCount += 1;
	}
	this.catalogSetItemsWidth = (this.catalogSetItemsCount <=5) ? 20 : 100/this.catalogSetItemsCount;
	var dragObj = BX.findChildren(BX("bx_catalog_set_construct_popup_"+this.element_id), {className:"bx_drag_obj"}, true);
	for (var i=0; i<dragObj.length; i++)
	{
		dragObj[i].style.width = this.catalogSetItemsWidth+"%";
	}
	BX("bx_catalog_set_construct_slider_"+this.element_id).style.width = this.catalogSetItemsCount <=5 ? "100%" : (100+(this.catalogSetItemsCount-5)*20)+"%";
	if (this.catalogSetItemsCount > 5)
	{
		BX("bx_catalog_set_construct_slider_left_"+this.element_id).style.display = "block";
		BX("bx_catalog_set_construct_slider_right_"+this.element_id).style.display = "block";
	}
	else
	{
		BX("bx_catalog_set_construct_slider_left_"+this.element_id).style.display = "none";
		BX("bx_catalog_set_construct_slider_right_"+this.element_id).style.display = "none";
		BX("bx_catalog_set_construct_slider_"+this.element_id).style.left = "0%";
		BX("bx_catalog_set_construct_slider_"+this.element_id).setAttribute("data-style-left", 0);
	}

}

catalogSetConstructPopup.prototype.recountPrices = function()
{
	var sumPrice = +this.catalogDefaultItemDiscountPrice;
	var sumOldPrice = +this.catalogDefaultItemPrice;
	var sumDiffDiscountPrice = +this.catalogDefaultItemDiscountDiffPrice;

	var setObj = BX.findChildren(BX("bx_catalog_set_construct_popup_"+this.element_id), {className:"bx_drag_dest"}, true);
	for (var i=0; i<setObj.length; i++)
	{
		if (!BX.hasClass(setObj[i], "bx_kit_item_empty"))
		{
			var priceObj = BX.findChild(setObj[i], {className:"bx_kit_item_price"}, true, false);
			var price = priceObj.getAttribute("data-discount-price");
			if (price)
				sumPrice += +price;
			var oldPrice = priceObj.getAttribute("data-price");
			if (oldPrice)
				sumOldPrice += +oldPrice;
			var discDiffprice = priceObj.getAttribute("data-discount-diff-price");
			if (discDiffprice)
				sumDiffDiscountPrice += +discDiffprice;
		}
	}

	var element_id = this.element_id;

	BX.ajax.post(
		this.ajaxPath,
		{
			sessid : BX.bitrix_sessid(),
			action : "ajax_recount_prices",
			sumPrice : sumPrice,
			sumOldPrice : sumOldPrice,
			sumDiffDiscountPrice : sumDiffDiscountPrice,
			currency : this.catalogCurrency
		},
		function(result)
		{
			var json = JSON.parse(result);

			if (json.formatSum)
			{
				BX("bx_catalog_set_construct_sum_price_"+element_id).innerHTML = json.formatSum;
			}
			if (json.formatOldSum)
			{
				BX("bx_catalog_set_construct_sum_old_price_"+element_id).innerHTML = json.formatOldSum;
				BX("bx_catalog_set_construct_sum_old_price_"+element_id).parentNode.style.display = "block";
			}
			else
			{
				BX("bx_catalog_set_construct_sum_old_price_"+element_id).parentNode.style.display = "none";
			}
			if (json.formatDiscDiffSum)
			{
				BX("bx_catalog_set_construct_sum_diff_price_"+element_id).innerHTML = json.formatDiscDiffSum;
				BX("bx_catalog_set_construct_sum_diff_price_"+element_id).parentNode.style.display = "block";
			}
			else
			{
				BX("bx_catalog_set_construct_sum_diff_price_"+element_id).parentNode.style.display = "none";
			}
			if (!json.formatOldSum && !json.formatDiscDiffSum)
			{
				BX.addClass(BX("bx_catalog_set_construct_price_block_"+element_id), "not_sale");
			}
			else
			{
				BX.removeClass(BX("bx_catalog_set_construct_price_block_"+element_id), "not_sale");
			}
		}
	);
}

catalogSetConstructPopup.prototype.catalogSetAdd = function(element, emptyObj)
{
	if (!emptyObj)
		emptyObj = BX.findChild(BX("bx_catalog_set_construct_popup_"+this.element_id), {className:"bx_kit_item_empty"}, true, false);
	if (emptyObj)
	{
		var add_obj = element.parentNode;

		var objImg = BX.findChild(element, {className:"bx_kit_img_container"}, true, false);
		var objName = BX.findChild(element, {className:"bx_kit_item_title"}, true, false);
		var itemID = objName.getAttribute("data-item-id");
		var objPrice = BX.findChild(element, {className:"bx_kit_item_price"}, true, false);
		var _this = this;
		var objDeleteIcon =  BX.create('DIV', {
			props: {className: "bx_kit_item_del"},
			events: {click: function() {_this.catalogSetDelete(this.parentNode);}}
		});

		var newSetItem = BX.create('DIV', {
			props: {className: "bx_kit_item_children bx_kit_item_border"},
			children: [objImg, objName, objPrice, objDeleteIcon]
		});

		emptyObj.appendChild(newSetItem);
		BX.removeClass(emptyObj, "bx_kit_item_empty bx_kit_item_border");
		if (BX.hasClass(element, "discount"))
		{
			BX.addClass(emptyObj, "discount");
			var objDiscount = BX.findChild(add_obj, {className:"bx_kit_item_discount"}, true, false);
			emptyObj.appendChild(objDiscount);
		}

		BX.remove(add_obj);

		this.recountSlider("add");
		this.recountPrices();
		this.catalogSetIds.push(itemID);
	}
}

catalogSetConstructPopup.prototype.catalogSetDelete = function(element)
{
	var empty_obj = element.parentNode;

	var objImg = BX.findChild(element, {className:"bx_kit_img_container"}, true, false);
	var objName = BX.findChild(element, {className:"bx_kit_item_title"}, true, false);
	var itemID = objName.getAttribute("data-item-id");
	var objPrice = BX.findChild(element, {className:"bx_kit_item_price"}, true, false);

	var _this = this;
	var objAddIcon =  BX.create('DIV', {
		props: {className: "bx_kit_item_add"},
		events: {click: function() {_this.catalogSetAdd(this.parentNode);}}
	});

	var discountClass = BX.hasClass(empty_obj, "discount") ? " discount" : "";

	var newSetItem = BX.create('DIV', {
		props: {className: "bx_kit_item bx_kit_item_border"+discountClass},
		children: [objImg, objName, objPrice, objAddIcon]
	});

	var divChilds = [];
	divChilds.push(newSetItem);
	if (discountClass)
	{
		var objDiscount = BX.findChild(empty_obj, {className:"bx_kit_item_discount"}, true, false);
		divChilds.push(objDiscount);
	}

	var objDiv = BX.create('DIV', {
		props: {className: "bx_kit_item_slider bx_drag_obj"},
		children: divChilds
	});
	objDiv.setAttribute("data-main-element-id", this.element_id);
	objDiv.onbxdragstart = catalogSetConstructDragStart;
	objDiv.onbxdrag = catalogSetConstructDragMove;
	objDiv.onbxdraghover = catalogSetConstructDragHover;
	objDiv.onbxdraghout = catalogSetConstructDragOut;
	objDiv.onbxdragrelease = catalogSetConstructDragRelease;   //node was thrown outside of dest
	jsDD.registerObject(objDiv);

	BX("bx_catalog_set_construct_slider_"+this.element_id).appendChild(objDiv);

	empty_obj.innerHTML = "";
	BX.addClass(empty_obj, "bx_kit_item_empty bx_kit_item_border");
	BX.removeClass(empty_obj, "discount");

	this.recountSlider("delete");
	this.recountPrices();
	for(var i = 0, l = this.catalogSetIds.length; i < l; i++)
	{
		if (this.catalogSetIds[i] == itemID)
		{
			this.catalogSetIds.splice(i,1);
		}
	}
}

catalogSetConstructPopup.prototype.Add2Basket = function()
{
	var detail_img = this.detail_img;
	var element_id = this.element_id;
	BX.ajax.post(
		this.ajaxPath,
		{
			sessid: BX.bitrix_sessid(),
			action: 'catalogSetAdd2Basket',
			set_ids: this.catalogSetIds,
			itemsRatio: this.items_ratio,
			lid: this.lid,
			iblockId: BX.message('setIblockId'),
			setOffersCartProps: BX.message('setOffersCartProps')
		},
		function(result)
		{
			BX.CatalogSetConstructor.popup.close();
			showCatalogSetAdd2BasketPopup(detail_img, element_id);
		}
	);
}

function showCatalogSetAdd2BasketPopup(setMainPictureUrl, element_id)
{
	element_id = element_id || "";
	var popup = BX.PopupWindowManager.create("CatalogSetAdd2Basket"+element_id, null, {
		autoHide: false,
		//	zIndex: 0,
		offsetLeft: 0,
		offsetTop: 0,
		overlay : true,
		draggable: {restrict:true},
		closeByEsc: true,
		closeIcon: { right : "12px", top : "10px"},
		content: '' +
			'<div class="bx_modal_container" style="width:300px;height:280px;text-align: center;padding-top:20px">' +
			'<img src="'+setMainPictureUrl+'"/>'+
			'<p>'+BX.message("setItemAdded2Basket")+'</p>'+
			'<a class="bx_bt_blue bx_medium" href="'+BX.message("setButtonBuyUrl")+'"><span class="bx_icon_cart"></span><span>'+BX.message("setButtonBuyName")+'</span></a>'+
			'</div>'
	});

	//popup.show();
    document.location.href = "/personal/cart";
}

//drag&drop
function catalogSetConstructDragStart()
{
	var objWidth = this.offsetWidth + "px";
	var objHeight = this.offsetHeight + "px";
	this.style.width = "100%";
	BX.firstChild(this).style.height = objHeight;
	BX.addClass(BX.firstChild(this), "bx_kit_item_slider_drag");

	BX.removeClass(BX.firstChild(this), "bx_kit_item");

	window.bxcatalogset = document.body.appendChild(BX.create('DIV', {             //div to move
		style: {
			position: 'absolute',
			zIndex: '2000',
			height: objHeight,
			width: objWidth
		},
		children: [this]
	}));
}

function catalogSetConstructDragMove(x, y)
{
	window.bxcatalogset.style.left = x-(this.clientWidth/2) + 'px';
	window.bxcatalogset.style.top = y-(this.clientHeight/2) + 'px';
}

function catalogSetConstructDragHover(dest, x, y)
{
	if (BX.hasClass(dest, "bx_kit_item_empty"))
		dest.style.border = "1px solid grey";
}

function catalogSetConstructDragOut(dest, x, y)
{
	if (BX.hasClass(dest, "bx_kit_item_empty"))
		dest.style.border = "";
}

function catalogSetConstructDragRelease()
{
	this.style.width = catalogSetPopupObj.catalogSetItemsWidth+"%";
	var element_id = this.getAttribute("data-main-element-id");
	BX.addClass(BX.firstChild(this), "bx_kit_item");
	BX("bx_catalog_set_construct_slider_"+element_id).appendChild(this);
	BX.remove(window.bxcatalogset);
	window.bxcatalogset = null;
}

function catalogSetConstructDestFinish(curNode, x, y)    //node was thrown inside of dest
{
	if (BX.hasClass(this, "bx_kit_item_empty"))
	{
		this.style.border = "";

		catalogSetPopupObj.catalogSetAdd(BX.firstChild(curNode), this);

		BX.remove(curNode);
		BX.removeClass(this, "bx_kit_item_empty");
		BX.remove(window.bxcatalogset);
		window.bxcatalogset = null;

		jsDD.refreshDestArea();

		return true;
	}
	else
		return false;
}