BX(function(){
    var time=$(".main-banner #sldr-cmp").attr('data-time');
    $(".menu-banner-features #sldr-cmp").removeAttr('data-time');
    function sldr_cmp(){
        clearTimeout(timersldr);
        var ths=$("#sldr-cmp");
        ths.find('div').eq(last).fadeOut(300);
        ths.find('div').eq((++last==num)?last=0:last).fadeIn(300);
        timersldr=setTimeout(sldr_cmp, time);
    }
    var last=0;
    var num=$("#sldr-cmp").bind('click', sldr_cmp).children().size();
    var timersldr=setTimeout(sldr_cmp, time);
});