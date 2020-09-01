<?php
session_start();
require("connect.php");
$countryId = $_SESSION["selectId"];
$sql = "select countries from forecast where countryId = $countryId";
$result =mysqli_query($link,$sql);
$row = mysqli_fetch_assoc($result);
$country= $row["countries"];
$incountry = urlencode($country);
$key = "CWB-6016183D-CA4E-4CC6-B126-5F1901B86112";
$url = "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=$key&format=JSON&locationName=$incountry&sort=time";
$json = file_get_contents($url);
$jslink = json_decode($json, true);

//echo $sql."<br>";
// mysqli_query($link,$sql);
// echo "天氣現象:(" . $b['weatherElement'][0]['elementName'] . ")" . "<br>"; //wx天氣現象

//echo $row["countries"];

echo $incountry;
echo $url;
foreach ($jslink['records']['location'] as $b) {
     if($country==$b['locationName'])
     {

     }
    // for ($i = 0; $i < 5; $i++) //36個小時天氣預報
    // {
        
    //     echo $b['weatherElement'][$i]['elementName'] . ":";
    //     echo $b['weatherElement'][$i]['time'][0]['parameter']['parameterName']."<br>";
    //     // echo "StartTime:" . $b['weatherElement'][0]['time'][$i]['startTime'] . "<br>"; //測量時間始
    //     // echo "EndTime:" . $b['weatherElement'][0]['time'][$i]['endTime'] . "<br>"; //測量時間末
    //     // echo $b['weatherElement'][0]['time'][$i]['parameter']['parameterName'] . "<br>"; //氣象狀態
    // }
}
