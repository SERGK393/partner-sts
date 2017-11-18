/**
 * Created by Sol on 12.08.14.
 */
$(document).ready(
    function(){
        var last;
       $(".left-menu ul li").bind({
           "mouseover":function(){
               $(".sub-menu").css("display","block").html($(this).find('div').eq(0).html())
               last=$(this);
         $(this).removeClass("left-menu ul li" )
               $("#menu-li-hover").attr("id","")
               $(this).attr("id","menu-li-hover")
           },
           "mouseout":function(){
               $(".sub-menu").css("display","")
               $("#menu-li-hover").attr("id","")
           }
       })
       $(".sub-menu").bind({
           "mouseover":function(){
               last.attr("id","menu-li-hover")
               $(".sub-menu").css("display","block")
           },
           "mouseout":function(){
               $(".sub-menu").css("display","")
               $("#menu-li-hover").attr("id","")
           }
       })
    }
)
