

var $kjq=jQuery.noConflict()
$kjq.fn.offsetTop=function(){return (this.offset().top-48)}
$kjq.fn.showFade=function(t){var elm=this;$kjq.timer(function(){elm.fadeIn(t?t:300)},80)}
$kjq.timer=function(f,t){$kjq('body').data('timer',setTimeout(f,t?t:300))}
$kjq.clearTimer=function(){clearTimeout($kjq('body').data('timer'))}

var last=''
var menul=null
var menuc101=null
var menuc102=null
var itemlight=null
var arrow=null
var arrowstep=0
var menupos=0

function show_hide_div(id){
	id = '#'+id
	if(last!='') if(last!=id) $kjq(last).hide()
	$kjq(id).slideToggle(300)
	last=id
}

function getMenuInformer(obj,num){
	$kjq.clearTimer()
	
	var id="#inf_pop_102"
	if(last!='') if(last!=id) $kjq(last).hide()
	
	if(menul[0]&&menuc102[0]){
		if(arrow[0])arrow.css('margin-top',(obj.offsetTop()-menupos-6)+'px')
		menuc102.html(arrow.parent().html()).append(menul.eq(num).html())
		
		$kjq(id).showFade()
		
		if(itemlight && itemlight!=obj) itemlight.removeClass('lighter')
		obj.addClass('lighter')
		itemlight=obj
		
		last=id
	}
}
function toPopupSubMenu(num){
	if(menul[0]&&menuc101[0]){
		if(arrow[0])arrow.css('margin-top',(arrowstep*num+5)+'px')
		menuc101.html(arrow.parent().html()).append(menul.eq(num).html())
		menuc101.fadeIn(300)
	}
}
function hidePopupSubMenu(){
	if(menuc101[0]){
		menuc101.fadeOut(150)
		if(arrow[0])arrow.css('margin-top','')
	}
}
function getInformerById(num,left,top){
	$kjq.clearTimer()
	var id="#inf_pop_"+num.toFixed()
	if(last!='') if(last!=id) $kjq(last).hide()
	last=id
	
	$kjq(id).showFade()
	/*
	var elm=document.getElementById(id)
	if(elm){
		elm.style.display = "block"
		if(left)elm.style.left=left.toFixed()+'px'
		if(top)elm.style.top=top.toFixed()+'px'
	}*/
}
function hideInformerById(num){
	$kjq.clearTimer()
	var id="#inf_pop_"+num.toFixed()
	if(last!='') if(last!=id) $kjq(last).hide()
	var elm=$kjq(id)
		
	$kjq.timer(function(){elm.fadeOut(150)
	if(itemlight) itemlight.removeClass('lighter')
	itemlight=null
	hidePopupSubMenu()})
		
	last=id
}

//loading
menul=$kjq("#inf_popup_res").find('div')
arrow=menul.find('.pop_menu_element_arrow')
menuc101=$kjq("#inf_pop_101_content")
menuc102=$kjq("#inf_pop_102_content")


$kjq("#inf_pop_101").find('div[name="list_dop"]').find('h5').each(function()
{$kjq(this).bind('mouseover',function(){hidePopupSubMenu()})})

var li=$kjq('#inf_pop_101').find('div[name="list"]').find('h5')
$kjq("#inf_pop_101").show()
arrowstep=li.eq(1).offsetTop()-li.eq(0).offsetTop()
$kjq("#inf_pop_101").hide()
menupos=$kjq("#body_main1").offsetTop()
li.each(function(i){ $kjq(this).find('a').bind('mouseover',function(){toPopupSubMenu(i)}) })

$kjq("[id^=inf_pop_]").each(function(i){
	var j=parseInt($kjq(this).attr('id').substr(8))
	$kjq(this).bind({
		mouseover:function(){getInformerById(j)},
		mouseout:function(){hideInformerById(j)}
	});
});

var mmenu = $kjq("#categ_menu_tbl")
var imbutton = $kjq("#inetmagazin_button").parent()
if(mmenu[0]){
	menuc102.css('height',(mmenu.outerHeight()-24)+'px')
	mmenu=mmenu.find('li')
	mmenu.each(function(i){
		$kjq(this).bind({
			mouseover:function(){getMenuInformer($kjq(this),i)},
			mouseout:function(){hideInformerById(102)}
		});
	});
}else{
	//imbutton.bind('mouseover',new Function("getInformerById(101);"));
	$kjq("#body_crumb").find('.ove_crumb1').bind('click',function(){show_hide_div('inf_pop_101')})
}
imbutton.bind({
	click:function(){show_hide_div('inf_pop_101')}//,
	//mouseout:function(){$kjq("#inf_pop_101").hide()}
});
$kjq('#top_block').bind('mouseover',function(){$kjq(last).fadeOut(300)})