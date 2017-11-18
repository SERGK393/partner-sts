<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");
?>

<style>
	.del-wrp{
		width: 100%;
		font-size: 18px;
	}
	.del-wrp:first-child{
		margin-right: 35px;
	}
	.del-opt-ctn{
		background-color: white;
		padding: 7px;
		width: 570px;
		float: left;
	}
	.del-opt-header{
		text-align: center;
		font-size: 24px;
		color: #007fc3;
	}
	.del-opt-main{
		padding: 20px 10px 30px 270px;
		background-repeat: no-repeat;
		font-size: 18px;
		line-height: 22px;

	}
	.point{
		background-image: url("/images/del_point.png");
			}
	.currier{
		background-image: url("/images/currier.png");
	}
	.del-opt-img{
		padding: 25px 0px 25px 82px;
		background-repeat: no-repeat;
		background-position-y: 50%;
		
	}
	.clock{
		background-image: url("/images/clock.png");
		color: #007fc3;
	}
	.cash{
		background-image: url("/images/wallet.png");
	}
	.card{
		background-image: url("/images/visamstc.png");
	}
	.bank{
		background-image: url("/images/bank.png");
	}		
	.buy-opt{
		font-size: 18px;
		color: #007fc3; 
	}
	.del-point-btn{
		padding: 7px;
		background-color: #55b4d3;
		cursor: pointer;
		}
		.del-point-btn a{
			color: white;
		}
</style>
<div class="del-wrp">
<div class="del-opt-ctn" style="margin-right: 30px">
	<div class="del-opt-header">Доставка до пункта выдачи в Вашем городе</div>
	<div  class="del-opt-main point"> Доставка до пункта выдачи -
это экономичный и быстрый
 способ получения товара.<br>
Для заказов cвыше 3000 р.
доставка до пункта выдачи 
осуществляется бесплатно
<div class="del-opt-img clock">1-4 дня</div>
</div>
<div class="buy-opt">Доступные способы оплаты:</div>
<div class="del-opt-img cash">Оплата наличными в пункте выдачи</div>
<div class="del-opt-img card">Онлайн оплата банковской картой</div>
<div class="del-opt-img bank">Оплата банковским переводом</div>
<div class="del-point-btn"><a href="/punkty-vydachi/">Список пунктов выдачи &#8594;</a></div>
</div>
<div class="del-opt-ctn">
	<div class="del-opt-header">Доставка курьером</div>
	<div  class="del-opt-main currier">
		Вы можете получить заказ не выходя из дома или офиса.
		Для заказов cвыше 30000 р. доставка курьерской службой осуществляется бесплатно
		<div class="del-opt-img clock">1-6 дней</div>
	</div>
	<div class="buy-opt">Доступные способы оплаты:</div>
	<div class="del-opt-img cash">Оплата наличными в пункте выдачи</div>
<div class="del-opt-img card">Онлайн оплата банковской картой</div>
<div class="del-opt-img bank">Оплата банковским переводом</div>
</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>