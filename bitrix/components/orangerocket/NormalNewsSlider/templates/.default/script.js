BX.ready(function() {
		
		$(".nss-tab").click(function(){
		var position=$(this).attr("data-position");
		$(".nss-tab")
		.css("background-color","#f5f5f5").css("border-bottom-color","silver");
		$(this).css("border-bottom-color","orangered")
			.css("background-color","rgb(223, 223, 223)");
			$(".nss-items-line").css("left",'-'+position+'px');
		
		})
	

});