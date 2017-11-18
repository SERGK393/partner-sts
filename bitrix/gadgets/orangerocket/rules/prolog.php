<?php
/**
 * Created by PhpStorm.
 * User: kan
 * Date: 08.10.15
 * Time: 14:26
 */

include_once "/var/www/west/data/INOUT/sts-platform-api/rules/PriceRulesForRetail.php";
$rul=new PriceRulesForRetail();

if(isset($_REQUEST['rule'])){
    $rul->setRule($_REQUEST['rule']);
}
if(isset($_REQUEST['change'])){
    foreach($_REQUEST['change'] as $id=>$rule) {
        $rule['id'] = $id;
        if(isset($rule['delete'])){
            $rul->deleteRule($rule);
        }else{
            $rul->setRule($rule);
        }
    }
}