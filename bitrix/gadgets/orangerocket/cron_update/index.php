<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div id="or_cron_update">
<?
$id=6;

if (CModule::IncludeModule("highloadblock")){
    $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById($id)->fetch();
    $hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
    $hlDataClass = $hldata['NAME'].'Table';
    
    $result = $hlDataClass::getList(array(
         'order' => array('ID' =>'DESC'),
         'select'=> array('UF_TIMESTAMP','UF_PRODUCTS'),
         'limit' => $arGadgetParams["UPDATES_COUNT"]
    ));

    while($res = $result->fetch()){?>
        <p><?=$res['UF_TIMESTAMP']?>-<?=$res['UF_PRODUCTS']?></p>
    <?}
}
?>
<a href="/bitrix/admin/highloadblock_rows_list.php?ENTITY_ID=<?=$id?>&lang=ru"><?=GetMessage("OR_CRONUPDATE_GOTO")?></a>
</div>
