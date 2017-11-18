BX.ready(function() {
	var ti2cartButton = $(".ti_2cart_button");
	ti2cartButton.click(function() {
		var button=$(this);
		if($(this).attr("data-cart")=="Y"){
		$.ajax({
			type: "POST",
			url: $(this).attr("data-path") + "/ajax.php",
			data:{
				'path':$(this).attr("data-path"),
				'itemId':$(this).attr("data-itemId"),
				'discountValue':$(this).attr("data-discountValue"),
				'parentId':$(this).attr("data-parentId")
			},
			success: function(data){
				button.attr("class","ti_2cart_href")
				.val("В корзине")
				.attr("data-cart","N")
				.parent().wrapInner("<a href='/personal/cart'></a>");
			}
		});}
	})

 
  $("#ti_carousel").owlCarousel({
    navigation: true,
    navigationText: [
      "<i class='icon-chevron-left icon-white'></i>",
      "<i class='icon-chevron-right icon-white'></i>"
      ],
   
  });
 

            
});