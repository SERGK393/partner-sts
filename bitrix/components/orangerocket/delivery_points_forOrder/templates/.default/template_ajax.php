<div>Пункты выдачи:<br>
<? $i=0;
	foreach($arResult as $punkt):
?>
<label for='punkt_<?=$i?>' style='cursor:pointer'> <input id="punkt_<?=$i?>" type="radio" name="ORDER_PROP_68" value="<?=$punkt?>"<?=($i)?'':' checked'?> > <?=$punkt?></label><br>
<?$i++?>
<?endforeach?>
<?if($i==0):?>
<label><input id="punkt_<?=$i?>" type="text" name="ORDER_PROP_68" placeholder="Введите новый пункт выдачи"></label>
<?endif?>
</div>
