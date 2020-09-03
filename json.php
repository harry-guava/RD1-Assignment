<?php

require("connect.php");
$key = "CWB-6016183D-CA4E-4CC6-B126-5F1901B86112";
$url = "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-089?Authorization=CWB-6016183D-CA4E-4CC6-B126-5F1901B86112&format=JSON&locationName=%E5%AE%9C%E8%98%AD%E7%B8%A3";

$json = file_get_contents($url);
$jslink = json_decode($json, true);


?>