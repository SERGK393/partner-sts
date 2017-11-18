<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$APPLICATION->RestartBuffer();
unset($arResult["COMBO"]);

function ClearResult(&$arFields)
{
   foreach ($arFields as $key => $value)
   {
      if (0 === strpos($key,'~'))
      {
         unset($arFields[$key]);
      }
      else
      {
         if (true == is_array($value))
            ClearResult($arFields[$key]);
      }
   }
}
ClearResult($arResult);
echo CUtil::PHPToJSObject($arResult, true);
//echo json_encode($arResult,JSON_UNESCAPED_UNICODE);
?>
