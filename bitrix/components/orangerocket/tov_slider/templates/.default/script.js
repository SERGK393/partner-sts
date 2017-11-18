$('document').ready(function(){
    function sldr_prev(){
        var ths=$(".top3_wrapper");
        ths.find('.top3_item').eq(last).hide();
        ths.find('.top3_item').eq((--last<0)?last=num-1:last).show();
    }
    function sldr_next(){
        var ths=$(".top3_wrapper");
        ths.find('.top3_item').eq(last).hide();
        ths.find('.top3_item').eq((++last==num)?last=0:last).show();
    }
    var last=0;
    var num=$(".top3_item").size();
    $(".top3_btn_l").bind('click', sldr_prev);
    $(".top3_btn_r").bind('click', sldr_next);
})