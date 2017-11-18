<?php
if($arResult['content']!==''):
$this->setFrameMode(true);?>
<section class="delivery_package">
    <div class="delivery_package--header"><span>В комплект поставки входит:</span></div>
    <div class="delivery_package--content">
        <?echo $arResult['content'];?>
    </div>
</section>
<?endif?>




