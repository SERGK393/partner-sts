
BX(function(){
$('#pv_menu > li >ul').click(function(event){event.stopPropagation();}).filter(':not(.pv_this_city)').hide();



$('#pv_menu  >li').click(function(){
	var selfclick= $(this).find('.pv_this_city').is(':visible');//если открыт и виден
	if(!selfclick){
		$(this).parent().find('> li ul:visible').slideToggle();

	}
	$(this).find('ul:first').slideToggle();
$('.pv_this_city').removeClass('pv_this_city');
$(this).addClass('pv_this_city');
});
	


	
	$('#pv_menu>li>ul>li').click(function(){
		$('#pv_menu>li>ul>li').css('font-weight','');
		$('#pv_menu>li>ul>li').css('color','');
		$(this).css('font-weight','700');
		$(this).css('color','rgba(255, 144, 0, 1)')
	})	
});
function searchik(url){


	BX.ajax.insertToNode(url,"re");
};