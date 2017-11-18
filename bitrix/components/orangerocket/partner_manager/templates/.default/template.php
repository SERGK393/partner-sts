<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?if($arResult['MANAGER']){?>
<table>
    <tr>
        <td>Имя и фамилия менеджера</td><td><?=$arResult['MANAGER']['UF_FIO']?></td>
    </tr>
    <tr>
        <td>Эл. почта</td><td><?=$arResult['MANAGER']['UF_EMAIL']?></td>
    </tr>
    <tr>
        <td>Доступ по внетреннему номеру</td><td>+7(473) 233-48-17 доб. <?=$arResult['MANAGER']['UF_PHONE_INNER']?></td>
    </tr>
    <tr>
        <td>Мобильный телефон</td><td><?=$arResult['MANAGER']['UF_PHONE_MOBILE']?></td>
    </tr>
</table>
<?}?>