<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$CurT=time();
///////////////////////////////////////////////////////
function get_work_time($CurT,$arParams){
 $CurH=date('G',$CurT);
 $no_call_days=$arParams['NO_CALL_WEEKDAY'];
 if(count($no_call_days)>=7) return false;

//////////////////////////////////////////////////////
if(in_array(date('N',$CurT),$no_call_days)){

        $CurT=$CurT+(60*60)*(25-$CurH);
        return get_work_time($CurT,$arParams);



}

/////////////////////////////////////////////////////////////////////


$hollydays=$arParams['HOLLYDAYS'];

 if(!empty($hollydays)){

$arMonth=array('1'=>"January",
"February", 
"March",
"April",
"May",
"June",
"July",
"August",
"September",
"October",
"November",
"December");

foreach ($hollydays as $hollyday){
    $first_exp=explode('-',$hollyday); 
      if (count($first_exp)==3){
         $firstDate=explode('.',$first_exp[0]);
         $fDay=$firstDate[0];
         $fMon=$firstDate[1];
         $fYear=$firstDate[2];
     


       $lastDate=explode('.',$first_exp[1]);
       $lDay=$lastDate[0];
       $lMon=$lastDate[1];
       $lYear=$lastDate[2];

       $time=explode('.',$first_exp[2]);
       $sTime=$time[0];
       $eTime=$time[1];
       $holidateStrt=strtotime("$fDay $arMonth[$fMon] 20".$fYear);
       $holidateEnd=strtotime("$lDay $arMonth[$lMon] 20".$lYear);



         if($CurT>=$holidateStrt && $CurT<=$holidateEnd){
            if($sTime==0){
             $CurT=$holidateEnd+(60*60)*(25-$CurH);
             return get_work_time($CurT,$arParams);
            }//Проверка нулевого времени
else{ 
$CurH=date('G',$CurT);
if($CurH<=$eTime){
 return $arGetWorkTime=array('TIME' =>$CurT,'START'=>$sTime,'END'=>$eTime );//День не закончен возвращаем время
}//Проверяем закончился ли день
else{    $CurT=$CurT+(60*60)*(25-$CurH);

                    return get_work_time($CurT,$arParams);}//Если день закончился
}//Иначе если время не нулевое
         }//ЕСЛИ В ДИАПАЗОНЕ




      }////Проверка нормального массива
}//Перебираем
 


 }//Если Есть праздники

////////////////////////////////////////////////////////////////////////
if(date('N',$CurT)==6){
     $sTime=$arParams['SATURDAY_TIME_BEGIN'];
                $eTime=$arParams['SATURDAY_TIME_END'];
                $CurH=date('G',$CurT);
                 if($CurH<=$eTime){
                  return $arGetWorkTime=array('TIME' =>$CurT,'START'=>$sTime,'END'=>$eTime );
                 }//Проверяем закончен ли рабочий день
                 else{
                    $CurT=$CurT+(60*60)*(25-$CurH);
                     return get_work_time($CurT,$arParams);

                 }//Если закончен

}//ЕСЛИ СУББОТА
////////////////////////////////////////////////////////////////////

if(date('N',$CurT)==7){
     $sTime=$arParams['SUNDAY_TIME_BEGIN'];
                $eTime=$arParams['SUNDAY_TIME_END'];
                $CurH=date('G',$CurT);
                 if($CurH<=$eTime){
                  return $arGetWorkTime=array('TIME' =>$CurT,'START'=>$sTime,'END'=>$eTime );
                 }//Проверяем закончен ли рабочий день
                 else{
                    $CurT=$CurT+(60*60)*(25-$CurH);
                    return get_work_time($CurT,$arParams);
                 }//Если закончен

}//Если воскресенье
///////////////////////////////////////////////////////////////////////////////

$sTime=$arParams['TIME_BEGIN'];
$eTime=$arParams['TIME_END'];
$CurH=date('G',$CurT); 
 if($CurH<=$eTime){
                  return $arGetWorkTime=array('TIME' =>$CurT,'START'=>$sTime,'END'=>$eTime );
                 }//Проверяем закончен ли рабочий день
                 else{
                    $CurT=$CurT+(60*60)*(25-$CurH);
                     return get_work_time($CurT,$arParams);
                 }//Если закончен
}//ФУНКЦИЯ


$resultik=get_work_time($CurT,$arParams);
$_SESSION['WORK_TIME']=$resultik; /////////////////Для использывания другими компонентами

////////////////ПРОВЕРКа РЕЗУЛЬТАТА/////////////////////

if(date('N')==date('N',$resultik['TIME'])){
  if(date('G')>=$resultik['START']){

    $arResult=array('DAY'=>'cегодня','TIME'=>$resultik['END'],'FROM_AFTER'=>'до ');

  }//проверяем рабочий день начался или нет
  else{
   $arResult=array('DAY'=>'cегодня','TIME'=>$resultik['START'],'FROM_AFTER'=>'после ');
  }//Если до начала рабочего дня
}//проверяем является ли рабочий день текущим
elseif ($resultik['TIME']-time()<=60*60*24) {
  $arResult=array('DAY'=>'завтра','TIME'=>$resultik['START'],'FROM_AFTER'=>'после ');
}//Проверяем не завтра ли ближайший рабочий день
else{
$arDays=array(
'1'=>'в Пн. ','во Вт. ','в Cр. ','в Чт. ','в Пт. ','в Cб. ','в Вс. ');
$day=date('N',$resultik['TIME']);
$ch=date('j',$resultik['TIME']);
$arResult=array('DAY'=>$arDays[$day].$ch.'го ','TIME'=>$resultik['START'],'FROM_AFTER'=>'c ');

}//Иначе конкретный день

$this->includeComponentTemplate();
