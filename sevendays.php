<?php
session_start();
require "connect.php";
$incountry = $_SESSION["incountry"];
$key = $_SESSION["key"];
$countryId= $_SESSION["selectId"];
//echo $_SESSION["selectId"];
// echo $key."<br>";
// echo $incountry;
//$key = "CWB-6016183D-CA4E-4CC6-B126-5F1901B86112";
//$incountry = $_SESSION["incountry"];
//echo $incountry;
$url7 ="https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-091?Authorization=$key&locationName=$incountry";
$json7 = file_get_contents($url7);
$jlink7 = json_decode($json7, true);
//存時間陣列
$starttime = array();
$fweather = array();
//清除資料表內容
$sqltt= "truncate sevendays";
mysqli_query($link,$sqltt);

$WxV = array();
foreach($jlink7['records']['locations'] as $b)
{

    for($i=0;$i<14;$i++)
    {
       $etime=$b['location'][0]['weatherElement'][10]['time'][$i];
       $starttime[]= $etime['startTime'];
       //echo $endtime[$i]."<br>";
       $WxV[]= $b['location'][0]['weatherElement'][6]['time'][$i]['elementValue'][1]['value'];
       $fweather[] = $etime['elementValue'][0]['value'];
    //echo $fweather[$i]."<br>";
    // echo $fweather[$i]."<br>";
       //echo $WxV[$i]."<br>";
       $cut = explode("。",$fweather[$i]);
       $cWx = $cut[0];
       if($i<6)
       {
       $cT = $cut[2];
       $cCI = $cut[3];
       $cWind= $cut[4];
       $cHum = $cut[5];
       }
       else
       {
           $cT =$cut[1];
           $cCI = $cut[2];
           $cWind= $cut[3];
           $cHum = $cut[4];
       }
       $sql = "insert into sevendays (countryId,Wx,WxV,T,CI,Wind,Hum,fTime) values ($countryId,'$cWx',$WxV[$i],'$cT','$cCI','$cWind','$cHum','$starttime[$i]')";
       mysqli_query($link,$sql);
    }
}
$sqld = "delete from sevendays where DATE_FORMAT(`fTime`,'%Y-%m-%d') = ANY(SELECT `date` from twoday)";
mysqli_query($link,$sqld);

$amsevenWxV = array();
$pmsevenWxV = array();
$amsevenWx= array();
$pmsevenWx= array();
$amsevenT= array();
$pmsevenT= array();
$amsevenCI= array();
$pmsevenCI= array();
$amsevenHum= array();
$pmsevenHum= array();
$sqlsevenam = "select Wx,T,CI,Hum,WxV from sevendays where DATE_FORMAT(`fTime`,'%H')=6";
$sevenam = mysqli_query($link,$sqlsevenam);
$sqlsevenpm="select Wx,T,CI,Hum,WxV from sevendays where DATE_FORMAT(`fTime`,'%H')=18";
$sevenpm = mysqli_query($link,$sqlsevenpm);
while ($rowam = mysqli_fetch_assoc($sevenam))
{
    $amsevenWxV[]= $rowam["WxV"];
    $amsevenWx[]= $rowam["Wx"];
    $amsevenT[]= $rowam["T"];
    $amsevenCI[]= $rowam["CI"];
    $amsevenHum[]= $rowam["Hum"];
}

while ($rowpm = mysqli_fetch_assoc($sevenpm))
{
    $pmsevenWxV[]= $rowpm["WxV"];
    $pmsevenWx[] = $rowpm["Wx"];
    $pmsevenT[] = $rowpm["T"];
    $pmsevenCI[] = $rowpm["CI"];
    $pmsevenHum[] = $rowpm["Hum"];
}
$_SESSION["amWxV"] = $amsevenWxV;
$_SESSION["amWx"] = $amsevenWx;
$_SESSION["amT"] = $amsevenT;
$_SESSION["amCI"] = $amsevenCI;
$_SESSION["amHum"] = $amsevenHum;

$_SESSION["pmWxV"] = $pmsevenWxV;
$_SESSION["pmWx"] = $pmsevenWx;
$_SESSION["pmT"] = $pmsevenT;
$_SESSION["pmCI"] = $pmsevenCI;
$_SESSION["pmHum"] = $pmsevenHum;



//print_r($S_SESSION["pmWxV"]);

    //    $cut = explode("。",$fweather[7]);
    //    print_r($cut);





?>
