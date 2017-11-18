<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональный раздел");
?>
<style>
    .bx_page a:hover{
        text-decoration: underline;
    }
</style>
    <div style="font-size: 20px; line-height: 1.4;" class="bx_page">
        <p style="padding: 20px; background-color: rgb(238, 221, 238);">В личном кабинете Вы можете проверить текущее состояние корзины, ход выполнения Ваших заказов, просмотреть или изменить личную информацию, а также подписаться на новости и другие информационные рассылки. </p>

        <div style="margin-bottom: 20px;">
            <h2 style="font-size: 24px;">Личная информация</h2>
            <a href="profile/">Изменить регистрационные данные</a> 	</div>

        <div style="margin-bottom: 20px;">
            <h2 style="font-size: 24px;">Заказы</h2>
            <a href="order/">Ознакомиться с состоянием заказов</a>
            <br>
            <a href="cart/">Посмотреть содержимое корзины</a>
            <br>
            <a href="order/">Посмотреть историю заказов</a>
            <br>
        </div>

        <div style="margin-bottom: 20px;">
            <h2 style="font-size: 24px;">Подписка</h2>
            <a href="subscribe/">Изменить подписку</a></div>

        <div>
            <ul class="b-media-list" style="margin: 0px; padding: 0px; border: 0px; font-family: arial, sans-serif; font-size: 13px; line-height: 18.0049991607666px; vertical-align: baseline; list-style: none; background-color: rgb(255, 255, 255);"> </ul>
        </div>
    </div>
 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>