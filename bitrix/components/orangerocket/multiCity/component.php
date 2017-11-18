<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!$APPLICATION->get_cookie("multiCity_city")){
require_once("classes/ipgeo/ipgeobase.php");
$gb = new IPGeoBase();
$ip=$_SERVER["REMOTE_ADDR"];
$data = $gb->getRecord($ip);
$APPLICATION->set_cookie("multiCity_city",$data["city"]);
echo "вы из города ".$APPLICATION->get_cookie("multiCity_city");
}else{ echo "вы из города ".$APPLICATION->get_cookie("multiCity_city")." и уже не первый раз ";
}




//$this->IncludeComponentTemplate()2000;
?>