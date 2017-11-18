/**
 * Created by Sol on 12.08.14.
 */
BX(
    function(){
        var last;
        var last1;
        var click=0;
        var timer;
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
               clearTimeout(timer); 
                last1=$(this);
               timer=setTimeout(function(){
                   last=last1;
                   var submenu=$(".sub-menu");
                   if(last.index()==0)submenu.addClass('sub-dacha');
                   else submenu.removeClass('sub-dacha');
                   submenu.css("display","block").html('').html(last.find('div').eq(0).html());
                   last.removeClass("left-menu ul li");
                   $("#menu-li-hover").attr("id","");
                   last.attr("id","menu-li-hover")
               },200);
           },
           "mouseout":function(){
               clearTimeout(timer); 
               timer=setTimeout(function(){
                   $(".sub-menu").css("display","");
                   $("#menu-li-hover").attr("id","")
               },200);
           }
       });
       $(".sub-menu").bind({
           "mouseover":function(){
               clearTimeout(timer); 
               last.attr("id","menu-li-hover");
               $(".sub-menu").css("display","block")
           },
           "mouseout":function(){
               clearTimeout(timer); 
               timer=setTimeout(function(){
                   $(".sub-menu").css("display","");
                   $("#menu-li-hover").attr("id","")
               },200);
           }
       })
    }
);
