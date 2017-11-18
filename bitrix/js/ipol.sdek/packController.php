<?
global $orderId;
global $IPOLSDEK_isLoaded;

$arGoods = self::getGoodsArray($orderId);
$strPackGabsST = (COption::GetOptionString(self::$MODULE_ID,"widthD",300) / 10)." ".GetMessage("IPOLSDEK_cm")." x ".(COption::GetOptionString(self::$MODULE_ID,"lengthD",400) / 10)." ".GetMessage("IPOLSDEK_cm")." x ".(COption::GetOptionString(self::$MODULE_ID,"heightD",200) / 10)." ".GetMessage("IPOLSDEK_cm");
?>
<style>
	#IPOLSDEK_PackController{
		display:none;
	}
	#IPOLSDEK_packPlace{
		height: 445px;
		overflow: auto;
	}
	#IPOLSDEK_buttonPlace{
		padding-top: 7px;
	}
	/*����� ��������*/
		.IPOLSDEK_PC{
			background-color: #E0E8EA;
			color: #4B6267;
			font-size: 14px;
			text-align: center !important;
			text-shadow: 0px 1px #FFF;
			padding: 8px 4px 10px !important;
			width: 635px;
		}
		.IPOLSDEK_PC div{
			float: left;
			font-weight: bold !important;
		}
		.IPOLSDEK_PC_KG{
			font-weight: bold !important;
		}
		.IPOLSDEK_PC_gabsEdit,.IPOLSDEK_PC_gabsSave,.IPOLSDEK_PC_gabsCount, .IPOLSDEK_PC_weightEdit,.IPOLSDEK_PC_weightSave,.IPOLSDEK_PC_weightCancel,.IPOLSDEK_PC_packDelete{
				width: 15px;
				height: 15px;
				display: inline-block;
			}
		.IPOLSDEK_PC_packDelete, .IPOLSDEK_PC_packDelete:hover{
			background: url("/bitrix/images/<?=self::$MODULE_ID?>/delPack.png") !important;
		}
		/*��������*/
			.IPOLSDEK_PC_gabLabel{
				width: 155px;
			}			
			.IPOLSDEK_PC_gabCLabel{
				width: 155px;
				display:none;
			}
			.IPOLSDEK_PC_gabBLabel a{
				margin-left: 2px;
			}
			.IPOLSDEK_PC_gabsSave{
				display:none;
			}
			.IPOLSDEK_PC_gabsEdit, .IPOLSDEK_PC_gabsEdit:hover{
				background: url("/bitrix/images/<?=self::$MODULE_ID?>/edit.png") !important;
			}
			.IPOLSDEK_PC_gabsCount, .IPOLSDEK_PC_gabsCount:hover{
				background: url("/bitrix/images/<?=self::$MODULE_ID?>/recall.png") !important;
			}			
			.IPOLSDEK_PC_gabsSave, .IPOLSDEK_PC_gabsSave:hover{
				background: url("/bitrix/images/<?=self::$MODULE_ID?>/save.png") !important;
			}
		/*���*/
			.IPOLSDEK_PC_gabCLabel input{
				width: 20px;
				margin-top: -6px !important;
			}
			.IPOLSDEK_PC_weightChange input{
				width: 20px;
				margin-top: -6px !important;			
			}
			.IPOLSDEK_PC_weightButtons a{
				margin-left: 2px;
			}
			.IPOLSDEK_PC_weightSave, .IPOLSDEK_PC_weightCancel{
				display:none;
			}
			.IPOLSDEK_PC_weightLabeled{
				display:none;
			}
			.IPOLSDEK_PC_weightChange{
				display:none;
			}
			.IPOLSDEK_PC_weightEdit, .IPOLSDEK_PC_weightEdit:hover{
				background: url("/bitrix/images/<?=self::$MODULE_ID?>/edit.png") !important;
			}		
			.IPOLSDEK_PC_weightCancel, .IPOLSDEK_PC_weightCancel:hover{
				background: url("/bitrix/images/<?=self::$MODULE_ID?>/recall.png") !important;
			}			
			.IPOLSDEK_PC_weightSave, .IPOLSDEK_PC_weightSave:hover{
				background: url("/bitrix/images/<?=self::$MODULE_ID?>/save.png") !important;
			}
			
	/*������*/
	.IPOLSDEK_PC_goodsPlace{
		width: 644px;
		min-height: 30px;
	}
	.IPOLSDEK_PC_goodsPlace p{
		margin: 0px;
		padding: 0px;
		clear: both;
	}
	.IPOLSDEK_PC_item{
		height: 30px;
		cursor: pointer;
		padding-top: 2px;
	}
	<?if(!$IPOLSDEK_isLoaded){?>
	.IPOLSDEK_PC_item:hover{
		background-color: #D5EFC6;
	}
	<?}?>
	.IPOLSDEK_PC_item div{
		line-height: 2;
	}
	.IPOLSDEK_PC_goodsPlace [id^="IPOLSDEK_pack_"] div{
		overflow: hidden;
		float: left;
		max-height: 26px;
	}
	.IPOLSDEK_PC_packName{
		width: 280px;
	}
	.IPOLSDEK_PC_checkB{
		width: 50px;
		text-align: center !important;
		<?if(!$IPOLSDEK_isLoaded || count($arGoods) < 10){?>
		visibility:hidden;
		<?}?>
	}
	.IPOLSDEK_PC_name{
		width: 230px;
	}
	.IPOLSDEK_PC_gabs{
		width: 200px;
	}
	.IPOLSDEK_PC_weight, .IPOLSDEK_PC_kg{
		width: 85px;
	}
	.IPOLSDEK_PC_weight .IPOLSDEK_PC_weightLabeled,.IPOLSDEK_PC_weight .IPOLSDEK_PC_weightTrue{
		max-height: 14px;
		max-width: 50px;
		overflow: hidden;
	}
	.IPOLSDEK_PC_delete{
		width: 50px;
		padding-left: 17px;
	}
	#IPOLSDEK_addPacker{
		clear: both;
	}
	
		/* ����������*/
	.IPOLSDEK_PC_unity{
		display: none;
	}
	.IPOLSDEK_PC_cChange{
		display: none;
	}
	#IPOLSDEK_PC_CChange_cntNew{
		margin-top: -5px;
		width: 20px;
		text-align: center;
	}
	#IPOLSDEK_PC_separator{
		width: 100px;
	}
	#IPOLSDEK_PC_separator div{
		text-align: center;
	}
	<?if($IPOLSDEK_isLoaded){?>
		#IPOLSDEK_buttonPlace, #IPOLSDEK_packApply, #IPOLSDEK_packCancel{
			display:none;
		}
		.IPOLSDEK_PC_gabBLabel, .IPOLSDEK_PC_weightButtons, .IPOLSDEK_PC_delete a{
			display:none;
		}
	<?}?>
</style>
<script>
	var IPOLSDEK_packHandler = {
		goods: {
			<?foreach($arGoods as $good){?>
			'<?=$good['PRODUCT_ID']?>': {
				NAME   : '<?=$good['NAME']?>',
				ID     : '<?=$good['PRODUCT_ID']?>',
				WEIGHT : '<?=($good['WEIGHT'])?>',
				GABS   : {
					WIDTH  : '<?=($good['DIMENSIONS']['WIDTH'])?>',
					HEIGHT : '<?=($good['DIMENSIONS']['HEIGHT'])?>',
					LENGTH : '<?=($good['DIMENSIONS']['LENGTH'])?>',
				}
			},
			<?}?>
		},

		packsSrc: {
			1 : {
				<?foreach($arGoods as $good){?>
					<?=$good['PRODUCT_ID']?>: <?=intval($good['QUANTITY'])?>,
				<?}?>
			},
		},

		packs: false,

		packsGabs: {},

		saveObj: {},

		load: function(){
			var savedPacks = $('#IPOLSDEK_PLACES').val();
			if(savedPacks)
				IPOLSDEK_packHandler.saveObj = JSON.parse(savedPacks);
			
			if(!IPOLSDEK_packHandler.packs)
				IPOLSDEK_packHandler.packs = {};
			
			for(var i in IPOLSDEK_packHandler.saveObj){
				IPOLSDEK_packHandler.packs[i] = IPOLSDEK_packHandler.saveObj[i]['goods'];
				if(typeof(IPOLSDEK_packHandler.packsGabs[i]) == 'undefined')
					IPOLSDEK_packHandler.packsGabs[i] = {};
				IPOLSDEK_packHandler.packsGabs[i].gabs = IPOLSDEK_packHandler.saveObj[i].gabs.replace(/( x )/g,' <?=GetMessage("IPOLSDEK_cm")?> x ') + " <?=GetMessage("IPOLSDEK_cm")?>";
				IPOLSDEK_packHandler.packsGabs[i].weight = IPOLSDEK_packHandler.saveObj[i].weight;
			}
		},

		loadPacks: function(){
			$('#IPOLSDEK_packPlace').find('[id^="IPOLSDEK_pack_"]').replaceWith('');
			for(var i in IPOLSDEK_packHandler.packs)
				IPOLSDEK_packHandler.loadPack(i);
		},

		loadPack: function(id){
			if($('#IPOLSDEK_pack_'+id).length < 1)
				IPOLSDEK_packHandler.addPack(id);

			for(var j in IPOLSDEK_packHandler.packs[id])
				IPOLSDEK_packHandler.handleItem(id,j);

			IPOLSDEK_packHandler.makePackWeight(id);

			// remove moved goods
			$('#IPOLSDEK_pack_'+id+' .IPOLSDEK_PC_goodsPlace').children().each(function(){
				if($(this).attr('id').indexOf('IPOLSDEK_pack_'+id+'_') === -1)
					$(this).replaceWith("");
			});

			IPOLSDEK_packHandler.initUi();
		},

		makePackWeight(id){
			if(typeof(IPOLSDEK_packHandler.packs[id]) == 'undefined') return false;

			var wei = 0;
			for(var i in IPOLSDEK_packHandler.packs[id]){
				if(typeof(IPOLSDEK_packHandler.goods[i]) == 'undefined') continue;
				wei += IPOLSDEK_packHandler.goods[i]['WEIGHT'] * IPOLSDEK_packHandler.packs[id][i];
			}

			$('#IPOLSDEK_pack_'+id+' .IPOLSDEK_PC .IPOLSDEK_PC_weightTrue .IPOLSDEK_PC_KG').html(wei);
			IPOLSDEK_packHandler.weight_editting = id;
			IPOLSDEK_packHandler.weight_stopChanges();
		},

		handleItem: function(pack,item){
			if($('#IPOLSDEK_pack_'+pack+'_good_'+item).length)
				$('#IPOLSDEK_pack_'+pack+'_good_'+item).find('.IPOLSDEK_PC_curCnt a').html(IPOLSDEK_packHandler.packs[pack][item]);
			else
				IPOLSDEK_packHandler.addItem(pack,item);
		},

		addItem: function(pack,item){
			var packHTML = "";
			var cG = IPOLSDEK_packHandler.goods[item].GABS;
			packHTML += "<div id='IPOLSDEK_pack_"+pack+"_good_"+item+"' class='IPOLSDEK_PC_item'>";
				packHTML += "<div class='IPOLSDEK_PC_checkB'><input class='IPOLSDEK_PC_cbController' type='checkbox' value='"+pack+"_"+item+"' onchange='IPOLSDEK_packHandler.cb_Change()'></div>";
				packHTML += "<div class='IPOLSDEK_PC_name' title='"+IPOLSDEK_packHandler.goods[item].NAME+"'>"+IPOLSDEK_packHandler.goods[item].NAME+"</div>";
				packHTML += "<div class='IPOLSDEK_PC_gabs'>"+cG.WIDTH + " <?=GetMessage("IPOLSDEK_cm")?> x " + cG.LENGTH + " <?=GetMessage("IPOLSDEK_cm")?> x " + cG.HEIGHT + "<?=GetMessage("IPOLSDEK_cm")?></div>";
				packHTML += "<div class='IPOLSDEK_PC_kg'>"+IPOLSDEK_packHandler.goods[item].WEIGHT + " <?=GetMessage("IPOLSDEK_kg")?></div>";
				packHTML += "<div class='IPOLSDEK_PC_cnt'><span class='IPOLSDEK_PC_curCnt'><a href='javascript:void(0)' onclick='IPOLSDEK_packHandler.renderCnt($(this))'>"+ IPOLSDEK_packHandler.packs[pack][item] + "</a></span><span class='IPOLSDEK_PC_cChange'><span class='IPOLSDEK_PC_cChange_left'></span>&nbsp;&gt;&nbsp;<input type='text' onchange='IPOLSDEK_packHandler.onChangeCnt($(this))'></span><span class='IPOLSDEK_PC_unity'>&nbsp;</span></div>";
				packHTML += "<p></p>";
			packHTML += "</div>";
			var packCap = $('#IPOLSDEK_pack_'+pack).find(".IPOLSDEK_PC_goodsPlace");
			packCap.html(packCap.html()+packHTML);
		},

		addPack: function(id){
			if(typeof(id) == 'undefined')
				for(var id = 1;; id++)
					if($('#IPOLSDEK_pack_'+id).length < 1)
						break;
			var gabSign = (typeof(IPOLSDEK_packHandler.packsGabs[id]) != 'undefined' && typeof(IPOLSDEK_packHandler.packsGabs[id].gabs) != 'undefined') ? IPOLSDEK_packHandler.packsGabs[id].gabs : '<?=$strPackGabsST?>';
			var weightVal = (typeof(IPOLSDEK_packHandler.packsGabs[id]) != 'undefined' && typeof(IPOLSDEK_packHandler.packsGabs[id].weight) != 'undefined') ? parseFloat(IPOLSDEK_packHandler.packsGabs[id].weight) : false;
			var packHeaderHtml =  "<div id='IPOLSDEK_pack_"+id+"'><div class='IPOLSDEK_PC'>";
				packHeaderHtml += "<div class='IPOLSDEK_PC_packName'><?=GetMessage("IPOLSDEK_JS_SOD_Pack")?> "+id+"</div>";
				packHeaderHtml += "<div class='IPOLSDEK_PC_gabs'>";
					packHeaderHtml += "<span class='IPOLSDEK_PC_gabLabel'>"+gabSign+"</span>";
					packHeaderHtml += "<span class='IPOLSDEK_PC_gabCLabel'><input type='text' class='IPOLSDEK_PC_gabCLabel_W'>&nbsp;X&nbsp;<input type='text' class='IPOLSDEK_PC_gabCLabel_L'>&nbsp;X&nbsp;<input type='text' class='IPOLSDEK_PC_gabCLabel_H'></span>";
					packHeaderHtml += "<span class='IPOLSDEK_PC_gabBLabel'><a href='javascript:void(0)' class='IPOLSDEK_PC_gabsEdit' onclick='IPOLSDEK_packHandler.gabs_edit("+id+")' title='<?=GetMessage('MAIN_EDIT')?>'></a><a href='javascript:void(0)' class='IPOLSDEK_PC_gabsSave' onclick='IPOLSDEK_packHandler.gabs_applyChanges()' title='<?=GetMessage('MAIN_APPLY')?>'></a><a href='javascript:void(0)' class='IPOLSDEK_PC_gabsCount' onclick='IPOLSDEK_packHandler.gabs_requestGabs("+id+")' title='<?=GetMessage('IPOLSDEK_JS_SOD_requestCnt')?>'></a></span>";
				packHeaderHtml += "</div>";
				packHeaderHtml += "<div class='IPOLSDEK_PC_weight'>";
					packHeaderHtml += "<span class='IPOLSDEK_PC_weightTrue'><span class='IPOLSDEK_PC_KG'>0</span> <?=GetMessage("IPOLSDEK_kg")?></span>";
					packHeaderHtml += "<span class='IPOLSDEK_PC_weightLabeled'>"+weightVal+" <?=GetMessage("IPOLSDEK_kg")?></span>";
					packHeaderHtml += "<span class='IPOLSDEK_PC_weightChange'><input type='text'/> <?=GetMessage("IPOLSDEK_kg")?></span>";
					packHeaderHtml += "<span class='IPOLSDEK_PC_weightButtons'><a href='javascript:void(0)' class='IPOLSDEK_PC_weightEdit' onclick='IPOLSDEK_packHandler.weight_edit("+id+")' title='<?=GetMessage('MAIN_EDIT')?>'></a><a href='javascript:void(0)' class='IPOLSDEK_PC_weightSave' onclick='IPOLSDEK_packHandler.weight_applyChanges()' title='<?=GetMessage('MAIN_APPLY')?>'></a><a href='javascript:void(0)' class='IPOLSDEK_PC_weightCancel' onclick='IPOLSDEK_packHandler.weight_cancelChanges("+id+")' title='<?=GetMessage('IPOLSDEK_JS_SOD_autoCnt')?>'></a></span>";
				packHeaderHtml += "</div>";
				packHeaderHtml += "<div class='IPOLSDEK_PC_delete'>";
					if(id != 1)
						packHeaderHtml += "<a href='javascript:void(0)' class='IPOLSDEK_PC_packDelete' onclick='IPOLSDEK_packHandler.deletePack("+id+")' title='<?=GetMessage('MAIN_APPLY')?>'></a>";
				packHeaderHtml += "</div>";
				packHeaderHtml += "<p></p></div><div class='IPOLSDEK_PC_goodsPlace'></div></div>";
				
			$('#IPOLSDEK_addPacker').before(packHeaderHtml);
			<?if(!$IPOLSDEK_isLoaded){?>IPOLSDEK_packHandler.initUi();<?}?>
			if(typeof(IPOLSDEK_packHandler.packs[id]) == 'undefined')
				IPOLSDEK_packHandler.packs[id]={};
		},

		deletePack: function(id){
			if(id == 1) return;
			for(var i in IPOLSDEK_packHandler.packs[id])
				if(typeof(IPOLSDEK_packHandler.packs[1][i]) == 'undefined')
					IPOLSDEK_packHandler.packs[1][i] = IPOLSDEK_packHandler.packs[id][i];
				else
					IPOLSDEK_packHandler.packs[1][i] += IPOLSDEK_packHandler.packs[id][i];
			for(var i = id;;i++){
				if(typeof(IPOLSDEK_packHandler.packs[i+1]) == 'undefined'){
					delete IPOLSDEK_packHandler.packs[i];
					break;
				}else{
					IPOLSDEK_packHandler.packs[i] = IPOLSDEK_packHandler.copyObj(IPOLSDEK_packHandler.packs[i+1]);
					if(typeof(IPOLSDEK_packHandler.packsGabs[i+1]) != 'undefined'){
						IPOLSDEK_packHandler.packsGabs[i] = IPOLSDEK_packHandler.copyObj(IPOLSDEK_packHandler.packsGabs[i+1]);
						delete IPOLSDEK_packHandler.packsGabs[i+1];
					}else
						delete IPOLSDEK_packHandler.packsGabs[i];
				}
			}
			IPOLSDEK_packHandler.loadPacks();
		},

		initUi: function(){
			$('.IPOLSDEK_PC_goodsPlace').sortable({connectWith:".IPOLSDEK_PC_goodsPlace",stop:IPOLSDEK_packHandler.moveItem,start:IPOLSDEK_packHandler.closeRender}).disableSelection();
		},

		moveItem: function(a,e){
			var item = $(e.item);
			var packFrom = parseInt(item.attr('id').substr(14));
			var itemId   = parseInt(item.attr('id').substr(20+packFrom.toString().length));
			var packTo   = parseInt(item.parents('[id^="IPOLSDEK_pack_"]').attr('id').substr(14));

			if(packTo != packFrom){
				if(typeof(IPOLSDEK_packHandler.packs[packTo][itemId]) != 'undefined')
					IPOLSDEK_packHandler.packs[packTo][itemId] = parseInt(IPOLSDEK_packHandler.packs[packFrom][itemId]) + parseInt(IPOLSDEK_packHandler.packs[packTo][itemId]);
				else
					IPOLSDEK_packHandler.packs[packTo][itemId] = IPOLSDEK_packHandler.packs[packFrom][itemId];

				delete IPOLSDEK_packHandler.packs[packFrom][itemId];

				IPOLSDEK_packHandler.makePackWeight(packFrom);
				IPOLSDEK_packHandler.loadPack(packTo);
			}
		},

		// ����������� ����������
		cb_Change: function(cb){
			<?if($IPOLSDEK_isLoaded){?>return;<?}?>
			if($('.IPOLSDEK_PC_cbController:checked').length > 0)
				IPOLSDEK_packHandler.cb_showWindow();
			else
				IPOLSDEK_packHandler.cb_kill();
		},

		cb_showWindow: function(){
			IPOLSDEK_packHandler.closeEdits('cb');

			var tmpHtml = '<select id="IPOLSDEK_PC_cbChange_select">';
			for(var i in IPOLSDEK_packHandler.packs)
				tmpHtml += "<option value='"+i+"'><?=GetMessage("IPOLSDEK_JS_SOD_Pack")?> "+i+"</option>";

			tmpHtml += "</select><p><a href='javascript:void(0)' onclick='IPOLSDEK_packHandler.moveItems()'>OK</a></p>";

			$('#IPOLSDEK_PC_separator .pop-text').html(tmpHtml);

			IPOLSDEK_packHandler.showWindow("80px",'540px');
		},

		moveItems: function(){
			var packTo = $('#IPOLSDEK_PC_cbChange_select').val();
			$('.IPOLSDEK_PC_cbController:checked').each(function(){
				var itemOperated = $(this).val();
				var packFrom = itemOperated.substr(0,itemOperated.indexOf('_'));
				var item = itemOperated.substr(itemOperated.indexOf('_')+1);
				if(packTo != packFrom){
					if(typeof(IPOLSDEK_packHandler.packs[packTo][item]) != 'undefined')
						IPOLSDEK_packHandler.packs[packTo][item] += IPOLSDEK_packHandler.packs[packFrom][item];
					else
						IPOLSDEK_packHandler.packs[packTo][item] = IPOLSDEK_packHandler.packs[packFrom][item];
					delete(IPOLSDEK_packHandler.packs[packFrom][item]);
				}
			});
			IPOLSDEK_packHandler.cb_kill();
			IPOLSDEK_packHandler.loadPacks();
		},

		cb_kill: function(){
			$('.IPOLSDEK_PC_cbController:checked').removeAttr('checked');
			IPOLSDEK_packHandler.closeRender();
		},

		// ���������
		renderCnt: function(link){
			<?if($IPOLSDEK_isLoaded){?>return;<?}?>
			var extCnt = parseInt(link.html());
			if(extCnt == 1 || typeof(IPOLSDEK_packHandler.packs[2]) == 'undefined')
				return;
			IPOLSDEK_packHandler.closeEdits();
			var packId = parseInt(link.closest('[id^="IPOLSDEK_pack_"]').attr('id').substr(14));

			var tmpHtml = '<select id="IPOLSDEK_PC_cChange_select">';
			for(var i in IPOLSDEK_packHandler.packs){
				if(i != packId)
					tmpHtml += "<option value='"+i+"'><?=GetMessage("IPOLSDEK_JS_SOD_Pack")?> "+i+"</option>";
			}

			tmpHtml += "</select><p><span id='IPOLSDEK_PC_CChange_cntLeft' maxCnt='"+(extCnt-1)+"'>"+extCnt+"</span>&nbsp;/&nbsp;<input type='text' value='1' id='IPOLSDEK_PC_CChange_cntNew' onchange='IPOLSDEK_packHandler.onChangeCnt($(this))'></p><p><a href='javascript:void(0)' onclick='IPOLSDEK_packHandler.confirmChangings()'>OK</a><input id='IPOLSDEK_PC_goodDescr' type='hidden' value='"+(link.closest('.IPOLSDEK_PC_item').attr('id'))+"'></p>";

			$('#IPOLSDEK_PC_separator .pop-text').html(tmpHtml);

			IPOLSDEK_packHandler.showWindow(($(link).position().top)+'px','540px');
		},

		onChangeCnt: function(link){
			var curCnt = link.val();
			curCnt = parseInt(curCnt);
			if(curCnt <= 0 || isNaN(curCnt))
				curCnt = 0;
			var maxCnt = $('#IPOLSDEK_PC_CChange_cntLeft').attr('maxCnt');
			if(curCnt > maxCnt)
				curCnt = maxCnt;
			link.val(curCnt);
			$('#IPOLSDEK_PC_CChange_cntLeft').html((maxCnt - curCnt + 1));
		},

		showWindow: function(top,left){
			var obj = $('#IPOLSDEK_PC_separator');
			var LEFT = (typeof(left) == 'undefined') ? '0px':left;
			var TOP  = (typeof(top) == 'undefined')  ? '0px':top;
			obj.css({
				top: TOP,
				left: LEFT,
				display: 'block'
			});
			$('#IPOLSDEK_PC_CChange_cntNew').select();
			return false;
		},

		confirmChangings: function(){
			var changeVal = parseInt($('#IPOLSDEK_PC_CChange_cntNew').val());
			if(!isNaN(changeVal) && changeVal > 0){
				var packFrom = parseInt($('#IPOLSDEK_PC_goodDescr').val().substr(14));
				var packTo   = $('#IPOLSDEK_PC_cChange_select').val();
				var goodId   = parseInt($('#IPOLSDEK_PC_goodDescr').val().substr(20+packFrom.toString().length));
				//script
					IPOLSDEK_packHandler.packs[packFrom][goodId] -= changeVal;
					if(typeof(IPOLSDEK_packHandler.packs[packTo][goodId]) == 'undefined')
						IPOLSDEK_packHandler.packs[packTo][goodId] = changeVal;
					else
						IPOLSDEK_packHandler.packs[packTo][goodId] += changeVal;
				//ui
					IPOLSDEK_packHandler.loadPack(packFrom);// remove
					IPOLSDEK_packHandler.loadPack(packTo);// add
			}
			IPOLSDEK_packHandler.closeRender();
		},

		closeRender: function(){
			$('#IPOLSDEK_PC_separator').hide();
		},

		closeSeparatior: function(){
			IPOLSDEK_packHandler.cb_kill();
			IPOLSDEK_packHandler.closeRender();
		},

		// ���
		weight_editting: false,

		weight_edit: function(id){
			IPOLSDEK_packHandler.closeEdits();
			IPOLSDEK_packHandler.weight_editting = id;
			var boss = $('#IPOLSDEK_pack_'+id);
			// links
			boss.find('.IPOLSDEK_PC_weightEdit').css('display','none');
			boss.find('.IPOLSDEK_PC_weightCancel').css('display','none');
			boss.find('.IPOLSDEK_PC_weightSave').css('display','inline-block');
			// signs
			boss.find('.IPOLSDEK_PC_weightTrue').css('display','none');
			boss.find('.IPOLSDEK_PC_weightLabeled').css('display','none');
			boss.find('.IPOLSDEK_PC_weightChange').css('display','inline-block');
			// fillings
			var oldVal = boss.find('.IPOLSDEK_PC_weightTrue .IPOLSDEK_PC_KG').html();
			if(IPOLSDEK_packHandler.weight_isManual(id))
				oldVal = parseFloat(boss.find('.IPOLSDEK_PC_weightLabeled').html());
			boss.find('.IPOLSDEK_PC_weightChange').find('input').val(isNaN(oldVal)?1:oldVal);
			boss.find('.IPOLSDEK_PC_weightChange').find('input').select();
		},

		weight_applyChanges: function(){
			if(!IPOLSDEK_packHandler.weight_editting)
				return;
			var boss = $('#IPOLSDEK_pack_'+IPOLSDEK_packHandler.weight_editting);
			var tmpVal = parseFloat(boss.find('.IPOLSDEK_PC_weightChange input').val());
			tmpVal = ((isNaN(tmpVal) || tmpVal == 0) ? '1' : tmpVal) + " <?=GetMessage('IPOLSDEK_kg')?>";
			boss.find('.IPOLSDEK_PC_weightLabeled').html(tmpVal);
			IPOLSDEK_packHandler.editPackGab(IPOLSDEK_packHandler.weight_editting,{weight:tmpVal}); // weight was editted
			IPOLSDEK_packHandler.weight_stopChanges();
		},

		weight_stopChanges: function(){
			if(!IPOLSDEK_packHandler.weight_editting)
				return;
			var boss = $('#IPOLSDEK_pack_'+IPOLSDEK_packHandler.weight_editting);
			// links
			boss.find('.IPOLSDEK_PC_weightEdit').css('display','');
			if(IPOLSDEK_packHandler.weight_isManual(IPOLSDEK_packHandler.weight_editting))
				boss.find('.IPOLSDEK_PC_weightCancel').css('display','inline-block');
			else
				boss.find('.IPOLSDEK_PC_weightCancel').css('display','none');
			boss.find('.IPOLSDEK_PC_weightSave').css('display','none');
			// signs
			if(IPOLSDEK_packHandler.weight_isManual(IPOLSDEK_packHandler.weight_editting)){
				boss.find('.IPOLSDEK_PC_weightTrue').css('display','none');
				boss.find('.IPOLSDEK_PC_weightLabeled').css('display','inline-block');
			}else{
				boss.find('.IPOLSDEK_PC_weightTrue').css('display','inline-block');
				boss.find('.IPOLSDEK_PC_weightLabeled').css('display','none');
			}
			boss.find('.IPOLSDEK_PC_weightChange').css('display','none');
			// fillings
			IPOLSDEK_packHandler.weight_editting = false;
		},

		weight_cancelChanges: function(id){
			var boss = $('#IPOLSDEK_pack_'+id);
			IPOLSDEK_packHandler.editPackGab(id,{weight:false});
			boss.find('.IPOLSDEK_PC_weightTrue').css('display','inline-block');
			boss.find('.IPOLSDEK_PC_weightLabeled').css('display','none');
			boss.find('.IPOLSDEK_PC_weightCancel').css('display','none');
		},
		
		weight_isManual: function(id){ // check weither weight was changed manually for pack id
			return (typeof(IPOLSDEK_packHandler.packsGabs[id]) != 'undefined' && typeof(IPOLSDEK_packHandler.packsGabs[id].weight) != 'undefined');
		},

		// ��������
		gabs_editting: false,
		gabs_request: false,

		gabs_edit: function(id){
			if(IPOLSDEK_packHandler.gabs_request)
				return;
			IPOLSDEK_packHandler.closeEdits();
			IPOLSDEK_packHandler.gabs_editting = id;
			var boss = $('#IPOLSDEK_pack_'+id);
			// links
			boss.find('.IPOLSDEK_PC_gabsEdit').css('display','none');
			boss.find('.IPOLSDEK_PC_gabsCount').css('display','none');
			boss.find('.IPOLSDEK_PC_gabsSave').css('display','inline-block');
			// signs
			boss.find('.IPOLSDEK_PC_gabLabel').css('display','none');
			var gsl = boss.find('.IPOLSDEK_PC_gabCLabel');
			gsl.css('display','inline');
			// fillings
			var oldVal = boss.find('.IPOLSDEK_PC_gabLabel').html();
			var gabs = [0,0,0];
			if(oldVal.indexOf('x') !== -1)
				gabs = oldVal.split(' x ');
			boss.find('.IPOLSDEK_PC_gabCLabel_W').val(parseInt(gabs[0]));
			boss.find('.IPOLSDEK_PC_gabCLabel_L').val(parseInt(gabs[1]));
			boss.find('.IPOLSDEK_PC_gabCLabel_H').val(parseInt(gabs[2]));
		},

		gabs_applyChanges: function(){
			if(!IPOLSDEK_packHandler.gabs_editting)
				return;
			var boss = $('#IPOLSDEK_pack_'+IPOLSDEK_packHandler.gabs_editting);
			var newSignHtml = "";
			var tmpVal = 0;
			var checkArr = ['W','L','H'];
			for(var i in checkArr){
				tmpVal = parseInt(boss.find('.IPOLSDEK_PC_gabCLabel_'+checkArr[i]).val());
				newSignHtml += ((isNaN(tmpVal) || tmpVal == 0) ? '1' : tmpVal) + " <?=GetMessage('IPOLSDEK_cm')?> x ";
			}
			newSignHtml = newSignHtml.substr(0,newSignHtml.length-3);
			boss.find('.IPOLSDEK_PC_gabLabel').html(newSignHtml);
			IPOLSDEK_packHandler.editPackGab(IPOLSDEK_packHandler.gabs_editting,{gabs:newSignHtml});
			IPOLSDEK_packHandler.gabs_stopChanges();
		},

		gabs_stopChanges: function(){
			if(!IPOLSDEK_packHandler.gabs_editting)
				return;
			var boss = $('#IPOLSDEK_pack_'+IPOLSDEK_packHandler.gabs_editting);
			// links
			boss.find('.IPOLSDEK_PC_gabsEdit').css('display','');
			boss.find('.IPOLSDEK_PC_gabsCount').css('display','');
			boss.find('.IPOLSDEK_PC_gabsSave').css('display','none');
			// signs
			boss.find('.IPOLSDEK_PC_gabLabel').css('display','inline');
			boss.find('.IPOLSDEK_PC_gabCLabel').css('display','none');
			// fillings
			IPOLSDEK_packHandler.gabs_editting = false;
		},

		gabs_requestGabs: function(id){
			if(IPOLSDEK_packHandler.gabs_request)
				return;
			if(IPOLSDEK_packHandler.gabs_editting)
				IPOLSDEK_packHandler.gabs_stopChanges();
			if(typeof(IPOLSDEK_packHandler.packs[id]) == 'undefined')
				return false;
			var boss = $('#IPOLSDEK_pack_'+id);
			boss.find('.IPOLSDEK_PC_gabsCount').css('display','none');
			IPOLSDEK_packHandler.gabs_request = id;
			boss.find('.IPOLSDEK_PC_gabLabel').css('color','gray');

			$.post("/bitrix/js/<?=self::$MODULE_ID?>/ajax.php",{action:'countGoods',goods: IPOLSDEK_packHandler.packs[id]},function(data){
				if(data.indexOf('G{') !== -1 && data.indexOf('}G') !== -1){
					data = data.substr(data.indexOf('G{')+2).replace(/(,)/g,' <?=GetMessage("IPOLSDEK_cm")?> x ');
					data = data.substr(0,data.indexOf('}G'));
					data = data.substr(0,data.length-3);
					$('#IPOLSDEK_pack_'+id).find('.IPOLSDEK_PC_gabLabel').html(data);
					IPOLSDEK_packHandler.editPackGab(IPOLSDEK_packHandler.gabs_request,{gabs:data});
				}
				var boss = $('#IPOLSDEK_pack_'+id);
				boss.find('.IPOLSDEK_PC_gabsCount').css('display','');
				boss.find('.IPOLSDEK_PC_gabLabel').css('color','black');
				IPOLSDEK_packHandler.gabs_request = false;
			});
		},

		// ���������� �� ���� ���������
		editPackGab: function(pack,params){
			if(typeof(IPOLSDEK_packHandler.packsGabs[pack]) == 'undefined')
				IPOLSDEK_packHandler.packsGabs[pack] = {};
			for(var i in params)
				if(params[i])
					IPOLSDEK_packHandler.packsGabs[pack][i] = params[i];
				else
					if(typeof(IPOLSDEK_packHandler.packsGabs[pack][i]) != 'undefined')
						delete IPOLSDEK_packHandler.packsGabs[pack][i];
			var cnt=0;
			for(var i in IPOLSDEK_packHandler.packsGabs[pack])
				cnt ++;
			if(cnt === 0)
				delete IPOLSDEK_packHandler.packsGabs[pack];
		},

		// �������� �������
		showGoods: function(){
			$('.IPOLSDEK_PC_item').show();
			$('#IPOLSDEK_PC_hideGoods').css('display','inline');
			$('#IPOLSDEK_PC_showGoods').css('display','none');
		},

		hideGoods: function(){
			$('.IPOLSDEK_PC_item').hide();
			$('#IPOLSDEK_PC_hideGoods').css('display','none');
			$('#IPOLSDEK_PC_showGoods').css('display','inline');
		},

		// ������ ��������
		cntDost: function(){
			var packs = IPOLSDEK_packHandler.getSO();
			delete packs.cnt;
			var reqParams = IPOLSDEK_getInputsCountDeliv({packs: packs});
			$('#IPOLSDEK_PC_cntDost input').attr('disabled','disabled');
			$.ajax({
				type : 'POST',
				url  : "/bitrix/js/<?=self::$MODULE_ID?>/ajax.php",
				data : reqParams,
				dataType: 'json',
				success: function(data){
					if(typeof data.success != 'undefined'){
						var dayLbl = data.termMin + "-" + data.termMax + " <?=GetMessage("IPOLSDEK_JS_SOD_HD_DAY")?>";
						if(data.termMin == data.termMax) dayLbl = data.termMax + " <?=GetMessage("IPOLSDEK_JS_SOD_HD_DAY")?>";
						var text = "<?=GetMessage("IPOLSDEK_JSC_SOD_NEWCONDITIONS_1")?>" + dayLbl + "<?=GetMessage("IPOLSDEK_JSC_SOD_NEWCONDITIONS_2")?>" + data.price + " <?=GetMessage("IPOLSDEK_JSC_SOD_RUB")?>";
						$('#IPOLSDEK_newPrDel').html(data.price);
					}else{
						var text = '';
						for(var i in data)
							text += data[i]+" ("+i+") \n";
					}
					alert(text);
					$('#IPOLSDEK_PC_cntDost input').removeAttr('disabled');
				}
			});
		},

		// ����������
		autoDistribution: function(){
			var packsCnt = 0;

			for(var i in IPOLSDEK_packHandler.packs){IPOLSDEK_packHandler.packs[i] = {}}
			if(i < 2){
				IPOLSDEK_packHandler.packs = IPOLSDEK_packHandler.copyObj(IPOLSDEK_packHandler.packsSrc);
				alert('<?=GetMessage("IPOLSDEK_JS_SOD_alertMoarPacks")?>');
				return;
			}

			packsCnt = i;
			for(var j in IPOLSDEK_packHandler.packsGabs)
				if(typeof(IPOLSDEK_packHandler.packsGabs[j].weight) != 'undefined' && IPOLSDEK_packHandler.packsGabs[j].weight)
					i--;

			// if(i){alert('<?=GetMessage("IPOLSDEK_JS_SOD_alertGiveAll")?>');return;}

			var cnt = 0;
			for(var i in IPOLSDEK_packHandler.packsSrc[1])
				for(var j = 0; j < IPOLSDEK_packHandler.packsSrc[1][i]; j++)
					cnt ++;
			var goodsInPack = Math.floor(cnt / packsCnt);
			if(goodsInPack < 1){alert('<?=GetMessage("IPOLSDEK_JS_SOD_alertImpossiburu")?>');return;}

			cnt -=  goodsInPack * packsCnt;

			var gdWorked  = 0;
			var packIndex = 1;
			for(var i in IPOLSDEK_packHandler.packsSrc[1])
				for(var j = 0; j < IPOLSDEK_packHandler.packsSrc[1][i]; j++){
					if(typeof(IPOLSDEK_packHandler.packs[packIndex][i]) == 'undefined')
						IPOLSDEK_packHandler.packs[packIndex][i] = 1;
					else
						IPOLSDEK_packHandler.packs[packIndex][i] += 1;
					if((++gdWorked == goodsInPack && packIndex > cnt) || gdWorked > goodsInPack){
						gdWorked = 0;
						packIndex ++;
					}
				}
			IPOLSDEK_packHandler.loadPacks();
		},

		// ������� ��� ����������
		closeEdits: function(mode){
			IPOLSDEK_packHandler.weight_stopChanges();
			IPOLSDEK_packHandler.gabs_stopChanges();
			if(typeof(mode) == 'undefined' || mode != 'cb')
				IPOLSDEK_packHandler.cb_kill();
			else
				IPOLSDEK_packHandler.closeRender();
		},

		// �������� ����
		wnd: false,

		open: function(){
			if(!IPOLSDEK_packHandler.wnd){
				var html=$('#IPOLSDEK_PackController').html();
				$('#IPOLSDEK_PackController').replaceWith('');

				IPOLSDEK_packHandler.packs = IPOLSDEK_packHandler.copyObj(IPOLSDEK_packHandler.packsSrc);

				IPOLSDEK_packHandler.wnd = new BX.CDialog({
					title: "<?=GetMessage('IPOLSDEK_JS_SOD_PC_HEADER')?>",
					content: html,
					icon: 'head-block',
					resizable: false,
					draggable: true,
					height: '505',
					width: '685',
					buttons: [
						'<input type=\"button\" id=\"IPOLSDEK_packApply\" value=\"<?=GetMessage('MAIN_APPLY')?>\" onclick=\"IPOLSDEK_packHandler.apply()\"/>',
						'<input type=\"button\" id=\"IPOLSDEK_packCancel\" value=\"<?=GetMessage('MAIN_RESET')?>\"  onclick=\"IPOLSDEK_packHandler.cancel()\"/>',
					]
				});
				IPOLSDEK_packHandler.load();
			}
			IPOLSDEK_packHandler.loadPacks();
			IPOLSDEK_packHandler.showGoods();
			IPOLSDEK_packHandler.wnd.Show();
		},

		apply: function(){
			var SO = IPOLSDEK_packHandler.getSO();
			IPOLSDEK_packHandler.saveObj = SO;

			IPOLSDEK_packHandler.close();
			IPOLSDEK_gabsHandler.onPackHandlerEnd();
		},

		getSO: function(){
			var saveObj = {};			
			var SOCnt = 0;
			for(var i in IPOLSDEK_packHandler.packs){
				var cnt = 0;
				for(var j in IPOLSDEK_packHandler.packs[i])
					cnt++;
				if(cnt < 1) continue;

				SOCnt++;

				saveObj[i] = {'goods':IPOLSDEK_packHandler.packs[i]};

				if(typeof(IPOLSDEK_packHandler.packsGabs[i]) != 'undefined' && typeof(IPOLSDEK_packHandler.packsGabs[i].gabs) != 'undefined')
					saveObj[i].gabs = IPOLSDEK_packHandler.packsGabs[i].gabs.replace(/( <?=GetMessage("IPOLSDEK_cm")?>)/g,'');
				else
					saveObj[i].gabs = $('#IPOLSDEK_pack_'+i).find('.IPOLSDEK_PC_gabLabel').html().replace(/( <?=GetMessage("IPOLSDEK_cm")?>)/g,'');
				if(typeof(IPOLSDEK_packHandler.packsGabs[i]) != 'undefined' && typeof(IPOLSDEK_packHandler.packsGabs[i].weight) != 'undefined')
					saveObj[i].weight = parseFloat(IPOLSDEK_packHandler.packsGabs[i].weight);
				else
					saveObj[i].weight = parseFloat($('#IPOLSDEK_pack_'+i).find('.IPOLSDEK_PC_weightTrue .IPOLSDEK_PC_KG').html());
			}
			saveObj.cnt = SOCnt;
			return saveObj;
		},

		cancel: function(){
			IPOLSDEK_packHandler.packs = IPOLSDEK_packHandler.copyObj(IPOLSDEK_packHandler.packsSrc);
			IPOLSDEK_packHandler.packsGabs = {};
			IPOLSDEK_packHandler.saveObj = false;
			IPOLSDEK_packHandler.close();
		},

		close: function(){
			IPOLSDEK_packHandler.closeEdits();
			IPOLSDEK_packHandler.wnd.Close();
		},

		copyObj(obj){
			if(obj == null || typeof(obj) != 'object')
				return obj;
			if(obj.constructor == Array)
				return [].concat(obj);
			var temp = {};
			for(var key in obj)
				temp[key] = IPOLSDEK_packHandler.copyObj(obj[key]);
			return temp;
		} 
	};
</script>
<div id='IPOLSDEK_PackController'>
	<div id='IPOLSDEK_packPlace'>
		<div id='IPOLSDEK_addPacker'></div>
	</div>
	<div id='IPOLSDEK_buttonPlace'>
		<input type='button' onclick='IPOLSDEK_packHandler.addPack();' value='<?=GetMessage("IPOLSDEK_JS_SOD_addPack")?>'/>&nbsp;
		<span id='IPOLSDEK_PC_cntDost'><input type='button' onclick='IPOLSDEK_packHandler.cntDost();' value='<?=GetMessage("IPOLSDEK_JS_SOD_cntDost")?>'/>&nbsp;</span>
		<span id='IPOLSDEK_PC_autoCnt'><input type='button' onclick='IPOLSDEK_packHandler.autoDistribution();' value='<?=GetMessage("IPOLSDEK_JS_SOD_autoDistribution")?>'/>&nbsp;</span>
		<span id='IPOLSDEK_PC_hideGoods' style='display:none;'><input type='button' onclick='IPOLSDEK_packHandler.hideGoods();' value='<?=GetMessage("IPOLSDEK_JS_SOD_hideGoods")?>'/>&nbsp;</span>
		<span id='IPOLSDEK_PC_showGoods' style='display:none;'><input type='button' onclick='IPOLSDEK_packHandler.showGoods();' value='<?=GetMessage("IPOLSDEK_JS_SOD_showGoods")?>'/>&nbsp;</span>
	</div>
	<div id="IPOLSDEK_PC_separator" class="b-popup" >
		<div class="pop-text"></div>
		<div class="close" onclick="IPOLSDEK_packHandler.closeSeparatior()"></div>
	</div>
</div>