<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?> 
<table cellspacing="8" cellpadding="1" border="0"> 
  <tbody> 
    <tr><td style="border-image: initial;"></td><td valign="top" style="border-image: initial;"> 
        <br />
       </td><td style="border-image: initial;"></td><td rowspan="10" style="border-image: initial;"><?$APPLICATION->IncludeComponent(
	"bitrix:map.google.view",
	".default",
	Array(
		"API_KEY" => " AIzaSyAXOWztMxq-7QN4yzvsuoscFqKbNLZWhDs ",
		"INIT_MAP_TYPE" => "ROADMAP",
		"MAP_DATA" => "a:4:{s:10:\"google_lat\";d:51.670342259435216;s:10:\"google_lon\";d:39.17789981481928;s:12:\"google_scale\";i:17;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:4:\"TEXT\";s:11:\"Santehsmart\";s:3:\"LON\";d:39.17804002761841;s:3:\"LAT\";d:51.669920269944704;}}}",
		"MAP_WIDTH" => "600",
		"MAP_HEIGHT" => "300",
		"CONTROLS" => array(0=>"SMALL_ZOOM_CONTROL",1=>"TYPECONTROL",2=>"SCALELINE",),
		"OPTIONS" => array(0=>"ENABLE_SCROLL_ZOOM",1=>"ENABLE_DBLCLICK_ZOOM",2=>"ENABLE_DRAGGING",3=>"ENABLE_KEYBOARD",),
		"MAP_ID" => "",
		"COMPONENT_TEMPLATE" => ".default"
	)
);?></td></tr>
   
    <tr><td style="border-image: initial;"></td><td valign="top" style="border-image: initial;"> 
        <br />
       </td><td style="border-image: initial;"></td></tr>
   
    <tr><td style="border-image: initial;"><b>Название компании:</b></td><td valign="top" style="border-image: initial;"> 
        <br />
       </td><td style="border-image: initial;">ООО &quot;СантехСмарт&quot;</td> </tr>
   
    <tr><td style="border-image: initial;"><b>ИНН / КПП:</b></td><td valign="top" style="border-image: initial;"> 
        <br />
       </td><td style="border-image: initial;">3664022007 / 366401001</td></tr>
   
    <tr><td style="border-image: initial;"><b>ОГРН:</b></td><td valign="top" style="border-image: initial;"> 
        <br />
       </td><td style="border-image: initial;"> 1143668076760 </td></tr>
   
    <tr><td style="border-image: initial;"><b>Фактический адрес:</b></td><td valign="top" style="border-image: initial;"> 
        <br />
       </td><td style="border-image: initial;">Россия, г. Воронеж, ул Донбасская, д. 21</td></tr>
   
    <tr><td style="border-image: initial;"><b>Телефон:</b></td><td valign="top" style="border-image: initial;"> 
        <br />
       </td><td style="border-image: initial;">8 (800) 500-13-84</td></tr>
   
    <tr><td style="border-image: initial;"><b>E-mail:</b></td><td valign="top" style="border-image: initial;"> 
        <br />
       </td><td style="border-image: initial;">sale@santehsmart.ru</td></tr>
   
    <tr><td style="border-image: initial;"> 
        <h3 style="color: rgb(0, 129, 198); line-height: 1.2em; white-space: nowrap; font-family: &quot;PT Sans&quot;, sans-serif; background-color: rgb(255, 255, 255);"><b><font size="3">График работы:</font></b></h3>
       
        <p style="padding: 5px; font-family: &quot;PT Sans&quot;, sans-serif; background-color: rgb(255, 255, 255);"> 
          <br />
         </p>
       </td><td valign="top" style="border-image: initial;"> 
        <br />
       </td><td style="border-image: initial;"> 
        <p style="padding: 5px; font-family: &quot;PT Sans&quot;, sans-serif; background-color: rgb(255, 255, 255);">Понедельник-Пятница: с 9ч. до 19ч. 
          <br />
         <b style="color: rgb(0, 129, 198);">Суббота: с 10ч. до 17ч.</b></br><b style="color: rgb(200, 0, 0);">Воскресенье: Выходной день.</b></p>
       </td></tr>
   
    <tr><td style="border-image: initial;"></td><td valign="top" style="border-image: initial;"> 
        <br />
       </td><td style="border-image: initial;"></td></tr>
   </tbody>
 </table>
 
<br />
 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>