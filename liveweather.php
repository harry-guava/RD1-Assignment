<?php
session_start();
require("twoday.php");
require "connect.php";
$countryId = $_SESSION["selectId"];
$sql = "select countries from forecast where countryId = $countryId";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$country = $row["countries"];
$incountry = urlencode($country);
$key = "CWB-6016183D-CA4E-4CC6-B126-5F1901B86112";
$url2 = "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=$key&format=JSON&locationName=$incountry&sort=time";
$json2 = file_get_contents($url2);
$jslink2 = json_decode($json2, true);

//echo $sql."<br>";
// mysqli_query($link,$sql);
// echo "天氣現象:(" . $b['weatherElement'][0]['elementName'] . ")" . "<br>"; //wx天氣現象

//echo $row["countries"];

//echo $incountry . "<br>";
$sqlc = "select countryId from liveweather where countryId = $countryId";
$result = mysqli_query($link, $sqlc);
$check = mysqli_num_rows($result);

$e = array();
$p = array();
//echo $check . "<br>";
foreach ($jslink2['records']['location'] as $b) {
    //echo $b['locationName'];
    for ($i = 0; $i < 5; $i++) //36個小時天氣預報
    {
        $e[$i] = $b['weatherElement'][$i]['elementName'];
        $p[$i] = $b['weatherElement'][$i]['time'][0]['parameter']['parameterName'];
    }
}
if ($check == 0) {
    $sqlin = "insert into `liveweather` (countryId,`$e[0]`,`$e[1]`,`$e[2]`,`$e[3]`,`$e[4]`) values ($countryId,'$p[0]','$p[1]','$p[2]','$p[3]','$p[4]')";
} else {
    $sqlin = "update liveweather set Wx='$p[0]',PoP=$p[1],MinT=$p[2],CI='$p[3]',MaxT=$p[4] where countryId = $countryId";

}

mysqli_query($link, $sqlin);
$sql3= <<< selname
    select Wx,PoP,MaxT,MinT,CI,f.countries from liveweather l join forecast f on (l.countryId = f.countryId) where f.countryId = $countryId
    selname;
    $result2= mysqli_query($link,$sql3);
    $selcountry = mysqli_fetch_assoc($result2);
    $_SESSION["name"] = $selcountry["countries"];
    $_SESSION["Wx"]  = $selcountry["Wx"];
    $_SESSION["PoP"]  = $selcountry["PoP"];
    $_SESSION["MinT"]  = $selcountry["MinT"];
    $_SESSION["MaxT"]  = $selcountry["MaxT"];
    $_SESSION["CI"]  = $selcountry["CI"];
    echo '<script>window.history.go(-1);</script>';
 

?>