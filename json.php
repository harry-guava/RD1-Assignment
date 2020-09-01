<?php

require("connect.php");
$key = "CWB-6016183D-CA4E-4CC6-B126-5F1901B86112";
$url = "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=$key&format=JSON";

$json = file_get_contents($url);
$jslink = json_decode($json, true);


?>