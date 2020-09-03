<?php
session_start();
require "connect.php";
$countryId = $_SESSION["selectId"];
$sql = "select countries from forecast where countryId = $countryId";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$country = $row["countries"];
$incountry = urlencode($country);
$key = "CWB-6016183D-CA4E-4CC6-B126-5F1901B86112";
$url3 = "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-089?Authorization=$key&format=JSON&locationName=$incountry";
$json3 = file_get_contents($url3);
$jslink3 = json_decode($json3, true);

 $sqld = "truncate twoday";
 mysqli_query($link,$sqld);

foreach ($jslink3['records']['locations'][0]['location'] as $b) {
    //echo $b['locationName'];
    // echo $b['weatherElement'][1]['elementName']."<br>";
    // if ($check == 0) 
    // {
        for ($j = 0; $j < 24; $j++) 
        {
            $weaTi = $b['weatherElement'][1]['time'][$j]['startTime'];
            $weaWx = $b['weatherElement'][1]['time'][$j]['elementValue'][0]['value'];
            $weaT = $b['weatherElement'][3]['time'][$j]['elementValue'][0]['value'];
            $weaCI =$b['weatherElement'][5]['time'][$j]['elementValue'][1]['value'];
            $sql2 = "insert into twoday (countryId,Wx,T,CI,Time,`date`) values ($countryId,'$weaWx',$weaT,'$weaCI',DATE_FORMAT('$weaTi','%Y-%m-%d'),CURRENT_DATE)";
            mysqli_query($link, $sql2);
            
            //echo $weaTi[$j]['elementValue'][0]['value']."<br>";
        }
}
$sqld = "delete from twoday where Time = `date` and Time = `date`" ;
mysqli_query($link,$sqld);
