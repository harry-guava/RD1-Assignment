<?php
session_start();
require("connect.php");
$countryId = $_SESSION["selectId"];
$key = $_SESSION["key"];
$urlr="https://opendata.cwb.gov.tw/api/v1/rest/datastore/O-A0002-001?Authorization=$key&format=JSON";
$jsonr= file_get_contents($urlr);
$jslinkr = json_decode($jsonr,true);

$selsql ="select countries from forecast where countryId= $countryId";
$result = mysqli_query($link,$selsql);
$countrynum = mysqli_fetch_assoc($result);

$sqld = "truncate rainview";
mysqli_query($link,$sqld);
foreach($jslinkr['records']['location'] as $b)
{
  
    $site = $b['locationName'];
    $city = $b['parameter'][0]['parameterValue'];
    //echo substr($countrynum["countries"],0,-1) ."<br>";
    
    $stationId = $b['stationId'];
    $subcity= substr($city,0,strlen('縣')*-1);
    //echo $subcity."<br>";
    $Rain = $b['weatherElement'][1]['elementValue'];
    $Rain24= $b['weatherElement'][6]['elementValue'];
    
    if(substr($countrynum["countries"],0,strlen('縣')*-1)==$subcity)
    {
    //echo $subcity;
    //echo $subcity."br";
      $sql = "insert into rainview (`stationId`,`site`,`city`,`Rain`,`Rain24`) values ('$stationId','$site','$subcity',$Rain,$Rain24)";
      mysqli_query($link,$sql);
    }
     //echo $b['locationName'].",".$b['parameter'][0]['parameterValue']."<br>";
     //echo $site;
 }
     $showsql= "select * from rainview";
     $resultshow= mysqli_query($link,$showsql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel=stylesheet href="index.css">
    <title>雨量觀測</title>
</head>
<body>
<body style="background-color:#fffeab;">
    <div class="pic">
        <div class="head">
            <h1>母湯氣象局</h1>
        </div>
    </div>

<div class= "tb">
    <table class="table table-dark">
    <thead>
      <tr style="color:	#FFFFAA">
        <th>觀測站ID</th>
        <th>觀測站名</th>
        <th>縣市</th>
        <th>1小時雨量</th>
        <th>24小時雨量</th>
      </tr>
    </thead>
    <tbody>
    <?php while ($row= mysqli_fetch_assoc($resultshow))  {?>
      <tr>
        <td style="color:yellow"><?=$row["stationId"]?></td>
        <td style="color:	#97CBFF"><?=$row["site"]?></td>
        <td><?=$row["city"]?></td>
        <td style="color:<?php if($row["Rain"]<0)echo "red";else echo "green"?>"><?=$row["Rain"]?></td>
        <td style="color:<?php if($row["Rain24"]<0)echo "red";else echo "green"?>"><?=$row["Rain24"]?></td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
</div>
</body>
</html>
