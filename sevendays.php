<?php
session_start();
require "connect.php";
$incountry = $_SESSION["incountry"];
$key = $_SESSION["key"];
$countryId= $_SESSION["selectId"];
echo $_SESSION["selectId"];
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
       echo $WxV[$i]."<br>";
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




    //    $cut = explode("。",$fweather[7]);
    //    print_r($cut);





?>
