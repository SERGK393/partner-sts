/**
 * Created by Sol on 12.08.14.
 */
$(document).ready(
    function(){
        var last;
        var click=0;
        $(".slogan").bind({
            "mouseover":function(){
                if(click){
                    $(this).addClass("sub-button-opn");
                    $(".left-menu>ul").children("li").each(function(){
                        $(this).show();
                    });
                }
            },
            "click":function(){
                click=1;
                $(this).addClass("sub-button-opn");
                $(".left-menu>ul").children("li").each(function(){
                    $(this).show();
                });
            },
            "mouseout":function(){
                $(this).removeClass("sub-button-opn");
                $(".left-menu>ul").children("li").each(function(){
                    $(this).hide();
                });
            }
        });
        $("#left-menu").bind({
            "mouseover":function(){
                if(click){
                  $(".slogan").addClass("sub-button-opn");
                    $(".left-menu>ul").children("li").each(function(){
                        $(this).show();
                    });
                }
            },
            "mouseout":function(){
                $(".slogan").removeClass("sub-button-opn");
                $(".left-menu>ul").children("li").each(function(){
                    $(this).hide();
                });
            }
        });
       $(".left-menu ul li").bind({
           "mouseover":function(){
               last=$(this);
               $(".sub-menu").css("display","block").html($(this).find('div').eq(0).html());
               $(this).removeClass("left-menu ul li" );
               $("#menu-li-hover").attr("id","");
               $(this).attr("id","menu-li-hover")
           },
           "mouseout":function(){
               $(".sub-menu").css("display","");
               $("#menu-li-hover").attr("id","")
           }
       });
       $(".sub-menu").bind({
           "mouseover":function(){
               last.attr("id","menu-li-hover");
               $(".sub-menu").css("display","block")
           },
           "mouseout":function(){
               $(".sub-menu").css("display","");
               $("#menu-li-hover").attr("id","")
           }
       })
    }
);
