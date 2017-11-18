$(document).ready(IPOLSESR_init);

function IPOLSESR_init()
{

	var selects = {};
	var j = 0;
	/*приводим массив, пришедший с сервера к нужному виду*/
	$.each(IPOLSESR_SET_SELECTS, function( index, value ) {
				
		if(value.on == "on") {
		
			selects[value.name] = {
			
				'class':'chosen-select'+j,
				'multy': value.multy,
				'placeholder':value.placeholder,
				'rm_pt_class':value.rm_pt_class,
				'rm_first_el':value.rm_first_el,
				'config': {width:value.width+"%", no_results_text:value.no_results_text, allow_single_deselect: value.allow_single_deselect}		
			
			};
		
			++j;
						
		}
		
	});
		
	var config = {};
	
	$.each(selects, function( index, value ) {
		
		var select = "select[name='"+index+"']";
				
		if($(document).find($(select)).attr("name") != undefined) {
	
			/*очищаем классы родителей*/
			if(value.rm_pt_class > 0) {
			
				var parent = $(select).parent();
				for ($i=0; value.rm_pt_class > $i; $i++) {
					
					$(parent).attr('class', '');

					parent = $(parent).parent();
					
				}
			
			}
		
			/*очистим первый элемент*/
			if(value.rm_first_el) {
						
				$(select).attr('id', index);	

				if(value.multy != "Y")
					$("#"+index+" :first").text('');
				else
					$(select).find("option[value='']").remove();
			
			}
			
			$(select).attr('class', value.class);
			$(select).attr('data-placeholder', value.placeholder);

			config["."+value.class] = value.config;
		
		}
		
	});
      
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
	
	$.each(selects, function( index, value ) {
		
		// console.log();
		if(value.multy == "Y") {
		
			$('.'+value.class).parent().append("<div class='sh_old' purse='"+value.class+"'></div>");	
			
			$('.'+value.class).change(function() {
				$('.'+value.class).trigger("chosen:updated");
			});
		
		}
		
	});
	
	$('.sh_old').click(function() {
		
		if($("."+$(this).attr('purse')).css("display") == "none") {
			$("."+$(this).attr('purse')).show();
			$("."+$(this).attr('purse')).siblings(".chosen-container").hide();
		}	
		else {
			$("."+$(this).attr('purse')).hide();
			$("."+$(this).attr('purse')).siblings(".chosen-container").show();
		}
		
	});	
	
}