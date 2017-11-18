<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

if (empty($arResult))
	return;
?>
<?
/*echo'<pre>';
print_r($arResult);
echo'</pre>';*/?>
<div class="left-menu">
    <ul>
        <?foreach($arResult["ALL_ITEMS_ID"] as $itemIdLevel_1=>$arItemsLevel_2):?> <!-- first level-->
        <li>
            <a href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_1]["LINK"]?>"><?=$arResult["ALL_ITEMS"][$itemIdLevel_1]["TEXT"]?></a>
            <?if (is_array($arItemsLevel_2) && !empty($arItemsLevel_2)):?>
                <div class="menu-section">
                    <ul>
                    <?foreach($arItemsLevel_2 as $itemIdLevel_2=>$arItemsLevel_3):?> <!-- second level-->
                        <li>
                            <a href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"]?>"><?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"]?></a>
                            <?if (is_array($arItemsLevel_3) && !empty($arItemsLevel_3)):?>
                            <ul>
                            <?foreach($arItemsLevel_3 as $itemIdLevel_3):?> <!-- third level-->
                                <li><a href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_3]["LINK"]?>"><?=$arResult["ALL_ITEMS"][$itemIdLevel_3]["TEXT"]?></a></li>
                            <?endforeach?>
                            </ul>
                            <?endif?>
                        </li>
                    <?endforeach?>
                    </ul>
                </div>
            <?endif?>
        </li>
        <?endforeach?>
    </ul>
</div>
<div class="sub-menu"></div>

<!--<a href="" class="menu-separator-user">
	<span class="menu-item-avatar" style=""></span>
</a>
-->

