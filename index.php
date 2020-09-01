<?php
//require("connect.php");
require_once("json.php");

$sqlc = "select countries from forecast ";
$resultc =mysqli_query($link,$sqlc);
$check = mysqli_fetch_assoc($resultc);

foreach ($jslink['records']['location'] as $b) {
    $country = $b['locationName'];
    if($check["countries"]=="")
    {
    $sql = "insert into forecast (countries) values ('$country')";
    //echo $sql."<br>";
    $ins =mysqli_query($link,$sql);
    }
    //echo $sql."<br>";
   // mysqli_query($link,$sql);
    // echo "天氣現象:(" . $b['weatherElement'][0]['elementName'] . ")" . "<br>"; //wx天氣現象
    // for ($i = 0; $i < 3; $i++) //36個小時天氣預報
    // {
    //     echo "StartTime:" . $b['weatherElement'][0]['time'][$i]['startTime'] . "<br>"; //測量時間始
    //     echo "EndTime:" . $b['weatherElement'][0]['time'][$i]['endTime'] . "<br>"; //測量時間末
    //     echo $b['weatherElement'][0]['time'][$i]['parameter']['parameterName'] . "<br>"; //氣象狀態
    // }
}
$sql2="select * from forecast";
$result= mysqli_query($link,$sql2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel=stylesheet href="index.css">
    <style>
        
    </style>
</head>
<body>
<div class="head">
    <h1>母湯氣象局</h1>
    <p style="color:black">點選想要查詢的縣市</p>
</div>
  <form>
  <?php while($row= mysqli_fetch_assoc($result)) {?>
    <a href="countries.php?id=<?=$row["countryId"]?>" class="btn btn-outline-info"><?= $row["countries"]?></a>
  <?php }?>
  </form>
</body>
</html>
