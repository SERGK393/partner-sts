function fast_buy(path){
    var buy=$('#fast-buy');
    var n=$('input[name=valn]', buy).val();
    if(n.length!=18){
        window.alert("Введите номер телефона,\nнапример: +7 (123) 123-45-67\n");
        return;
    }
    var str=$('input[name=valstr]', buy).val();
    if(str.length<2 || /[0-9]/.test(str)){
        window.alert("Введите свое имя");
        return;
    }
    var wait = BX.showWait('fast-buy');
    BX.ajax.post(
        path,
        {
            sessid : BX.bitrix_sessid(),
            action : "buy",
            data : buy.serializeArray()
        },
        function(message){
            BX.closeWait('fast-buy', wait);
            $('#fast-buy-container').html(message);
        }
    )
}
BX.ready(function(){
    setTimeout(function(){
        var buy=$('#fast-buy');
	    var nb=$('input[name=valn]', buy);
	    nb.mask('+7 (999) 999-99-99');
    },2000);
});
