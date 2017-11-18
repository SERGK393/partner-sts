<?
	require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
	if (CModule::IncludeModule("iblock")) {
  $arOrder = Array("SORT"=>"ASC");
 $arFilter = Array('IBLOCK_ID'=>$arParams['IBLOC_ID'],"NAME"=>$_SESSION['TF_LOCATION_SELECTED_CITY_NAME']);

$razdel=CIBlockSection::GetList(
    $arOrder ,$arFilter );
    
    
$razdel;
$arOrder=array('NAME'=>'ASC');
$id=$razdel->getNext();
$id=$id['ID'];
///////////////////////////////////////////////////////////////////////////////////////////////
$arFilter=array('CNT_ACTIVE'=>'Y');
$colvo=CIBlockSection::GetSectionElementsCount($id,
 $arFilter
);
switch($colvo){
	case($colvo>1&&$colvo<5):$pnkt="Пункта"; break;
	case($colvo>=5):$pnkt="Пунктов";break;
	default:$pnkt="Пункт";
}
}?>
<style>
	.del-points-count{
	width: 18px;
	text-align: center;
	color: white;
		padding: 3px;
		background-color: rgb(255, 97, 0);		
		border-radius:5px;
		float: left;
	}
	.del-points-s{
		float: left;
		color: white;
		font-size: 14px;
		padding: 3px 0px 0px 0px;
		margin-left: 3px;
		border-bottom: dotted 1px white;
	}
	.del-points-count a,.del-points-s a {
		color: white;
	}
</style>

<div class="del-points-count"><a href="/punkty-vydachi/"><?=$colvo?></a></div><div class="del-points-s"><a href="/punkty-vydachi/"><?=$pnkt.' '?> выдачи</a></div>