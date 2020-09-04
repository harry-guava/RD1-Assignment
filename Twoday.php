<?php
session_start();
require "connect.php";
$countryId = $_SESSION["selectId"];
$incountry = $_SESSION["incountry"];
$key = $_SESSION["key"];
$sql = "select countries from forecast where countryId = $countryId";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$country = $row["countries"];
//$incountry = urlencode($country);
//$key = "CWB-6016183D-CA4E-4CC6-B126-5F1901B86112";
$url3 = "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-089?Authorization=$key&format=JSON&locationName=$incountry";
$json3 = file_get_contents($url3);
$jslink3 = json_decode($json3, true);

$sqld = "truncate twoday";
mysqli_query($link, $sqld);
$weaPoP = array();
foreach ($jslink3['records']['locations'][0]['location'] as $b) {
    //echo $b['locationName'];
    // echo $b['weatherElement'][1]['elementName']."<br>";
    // if ($check == 0)
    // {
    for ($j = 0; $j < 24; $j++) {
        $weaTi = $b['weatherElement'][1]['time'][$j]['startTime'];
        $weaWx = $b['weatherElement'][1]['time'][$j]['elementValue'][0]['value'];
        $weaWxV = $b['weatherElement'][1]['time'][$j]['elementValue'][1]['value'];
        $weaT = $b['weatherElement'][3]['time'][$j]['elementValue'][0]['value'];
        $weaCI = $b['weatherElement'][5]['time'][$j]['elementValue'][1]['value'];
        $sql2 = "insert into twoday (countryId,Wx,WxV,T,CI,Time,`date`) values ($countryId,'$weaWx',$weaWxV,$weaT,'$weaCI','$weaTi',CURRENT_DATE)";
        mysqli_query($link, $sql2);
        //echo $weaTi[$j]['elementValue'][0]['value']."<br>";
    }
    for ($i = 1; $i < 5; $i++) {
        $weaPoP[] = $b['weatherElement'][0]['time'][$i]['elementValue'][0]['value'];
    }
    // mysqli_query($link,$sql3);
}
$sqla = array();
$timea = array();
$timea[0] = "(DATE_FORMAT(`Time`,'%Y-%m-%d')= DATE_ADD(`date`,INTERVAL 1 day)) and (DATE_FORMAT(`Time`,'%H') < 12)";
$timea[1] = "(DATE_FORMAT(`Time`,'%Y-%m-%d')= DATE_ADD(`date`,INTERVAL 1 day)) and (DATE_FORMAT(`Time`,'%H') >=12)";
$timea[2] = "(DATE_FORMAT(`Time`,'%Y-%m-%d')= DATE_ADD(`date`,INTERVAL 2 day)) and (DATE_FORMAT(`Time`,'%H') < 12)";
$timea[3] = "(DATE_FORMAT(`Time`,'%Y-%m-%d')= DATE_ADD(`date`,INTERVAL 2 day)) and (DATE_FORMAT(`Time`,'%H') >= 12)";

//$sqlu0 = "update twoday set PoP = $weaPoP[0] where $time1";
//$sqlu1 = "update twoday set PoP = $weaPoP[1] where $time2";
//$sqlu2 = "update twoday set PoP = $weaPoP[2] where $time3";
//$sqlu3 = "update twoday set PoP = $weaPoP[3] where $time4";
for ($i = 0; $i < 4; $i++) {
    $sqla[$i] = "update twoday set PoP = $weaPoP[$i] where $timea[$i]";
    mysqli_query($link, $sqla[$i]);
}
//  mysqli_query($link,$sqlu0);
//  mysqli_query($link,$sqlu1);
//  mysqli_query($link,$sqlu2);
//  mysqli_query($link,$sqlu3);

$sqld = "delete from twoday where (DATE_FORMAT(`Time`,'%Y-%m-%d') = `date`) or (DATE_FORMAT(`Time`,'%Y-%m-%d') = DATE_ADD(`date`,INTERVAL 3 day))";
mysqli_query($link, $sqld);

$sqlt = "select CI,Wx,count(*) as count from `twoday` where Time=DATE_ADD(`date`,INTERVAL 1 day) GROUP by CI,Wx order by count desc limit 2";
$resultt = mysqli_query($link, $sqlt);

$sqltp = "select round(AVG(PoP),0) as rPoP,round(AVG(T),0) as AT from `twoday` where $timea[0] union all select round(AVG(PoP),0) as rPoP ,round(AVG(T),0) as AT from `twoday` where $timea[1] union all select round(AVG(PoP),0) as rPoP ,round(AVG(T),0) as AT from `twoday` where $timea[2] union all
select round(AVG(PoP),0) as rPoP,round(AVG(T),0) as AT from `twoday` where $timea[3]";

$tPoP = array();
$AT = array();
$tCI = array();
$tWx = array();
$resPoP = mysqli_query($link, $sqltp);
while ($rowP = mysqli_fetch_assoc($resPoP)) {
    $tPoP[] = $rowP["rPoP"];
    $AT[] = $rowP["AT"];
}
while ($rowt = mysqli_fetch_assoc($resultt)) {
    $tCI[] = $rowt["CI"];
    $tWx[] = $rowt["Wx"];
    //print_r($rowt[0]);
}
$_SESSION["AT"] = $AT;
$_SESSION["tCI"] = $tCI;
$_SESSION["tWx"] = $tWx;
$_SESSION["tPoP"] = $tPoP;
//echo '<script>window.history.go(-1);</script>';
