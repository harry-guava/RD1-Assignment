<?php
session_start();
//require_once "json.php";
require "connect.php";

$sql2 = "select * from forecast";
$result = mysqli_query($link, $sql2);
$res = "";
//echo $_POST['selcountry'];
$selectvalue = isset($_POST['selcountry']) ? $_POST['selcountry'] : '';
//判斷下拉式是否有選擇
//echo $_POST['selcountry'];
//echo $selectvalue;
$_SESSION["selectId"] = $selectvalue;
if (isset($_POST['selcountry'])) {
    //header("Location: Twoday.php");
    header("Location: liveweather.php");
    
}
//明天天氣
$TomoCI =  $_SESSION["tCI"];
$TomoWx = $_SESSION["tWx"];
$TomoPoP= $_SESSION["tPoP"];
//
$name = $_SESSION["name"];
$Wx = $_SESSION["Wx"];
$PoP = $_SESSION["PoP"];
$MinT = $_SESSION["MinT"];
$MaxT = $_SESSION["MaxT"];
$CI = $_SESSION["CI"];
//
//後天天氣
$sqlac= "select CI,PoP,Wx,count(*) as count from `twoday` where Time=DATE_ADD(`date`,INTERVAL 2 day) GROUP by PoP,CI,Wx order by count desc limit 2";
$resultac= mysqli_query($link,$sqlac);
$AcCI =array();
$AcWx =array();
$AcPoP =array();
while($rowac= mysqli_fetch_assoc($resultac))
{
    $AcCI[]= $rowac["CI"];  
    $AcWx[]= $rowac["Wx"];
    $AcPoP[]=$rowac["PoP"];
}
//溫度
//$sqlT= "select T from twoday where DATE_FORMAT(`Time`,'%Y-%m-%d')= DATE_ADD(`date`,INTERVAL 1 day)"
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
    <link rel=stylesheet  href="index.css">
    <script>
    if ( window.history.replaceState )
    {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
</head>
<body style="background-color:#fffeab;">
<div class= "pic">
<div class="head">
         <h1>母湯氣象局</h1>
    <p style="color:black"><br><br><br>點選想要查詢的縣市</p>
</div>
</div>



<div class ="sel">
  <form  method="post">
    <select class="form-control" name = "selcountry" onchange="submit()" style="color:#e6e33c;background-color:#216096;width:97%">
        <option value="" selected disabled >請選擇縣市</option>
        <?php while ($row = mysqli_fetch_assoc($result)) {?>
        <option class="selcol" value= "<?=$row["countryId"]?>"<?=$selectvalue == $row['countryId'] ? 'selected' : ''?>><?=$row["countries"]?></option>
        <?php }?>
     </select>
  </form>
</div>



<div class= "row rowl1" style="background-color: #add2d9; min-height: 250px">
    <span class="cnname" ><?= $name?></span><span class="cnow">即時天氣</span>
    <p><br><p>
    <span class="cWx"><?="目前天氣：".$Wx?></span>
    <p><br><p>
    <span class="cCI"><?= "舒適程度：".$CI?></span>
    <hr style="border:1px solid blue; width:100%">
    <span class="cMT"><?=$MinT."˚C~".$MaxT."˚C"?></span>
    <span class="cPoP" style="color:<?php if($PoP>=70){echo "red";}else if($PoP<=30){echo "green";}elseif($PoP>30&&$PoP<=50){echo "blue";}else echo "yellow";?>">
    <?="降雨率".$PoP."%"?></span>
</div>
<div class= "row rowl2" style="background-color: #add2d9; min-height: 250px">
    <span class="cnname" ><?= $name?></span><span class="cnow">明天天氣</span>
    <p><br><p>
    <span class="cWx"><?php if($TomoWx[1]!= $TomoWx[0]){echo "天氣預報：".$TomoWx[0].$TomoWx[1];}else{echo "天氣預報：". $TomoWx[0];}?></span>
    <p><br><p>
    <span class="cCI"><?php if($TomoCI[1]!=""){ echo "舒適程度：".$TomoCI[0]."至".$TomoCI[1];}else{echo "舒適程度：".$TomoCI[0];}?></span>
    <hr style="border:1px solid blue; width:100%">
    <span class="cPoP" style="color:<?php if($TomoPoP[0]>=70){echo "red";}else if($TomoPoP[0]<=30){echo "green";}elseif($TomoPoP[0]>30&&$TomoPoP[0]<=50){echo "blue";}else echo "yellow";?>">
    <?="可能降雨率".$TomoPoP[0]."%"?></span>
</div>
<div class= "row rowl3" style="background-color: #add2d9; min-height: 250px">
    <span class="cnname" ><?= $name?></span><span class="cnow">後天天氣</span>
    <p><br><p>
    <span class="cWx"><?php if($AcWx[1]!= $AcWx[0]){echo "天氣預報：".$AcWx[0].$AcWx[1];}else{echo "天氣預報：". $AcWx[0];}?></span>
    <p><br><p>
    <span class="cCI"><?php if($AcCI[1]!=""){ echo "舒適程度：".$AcCI[0]."至".$AcCI[1];}else{echo "舒適程度：".$AcCI[0];}?></span>
    <hr style="border:1px solid blue; width:100%">
    <span class="cPoP" style="color:<?php if($AcPoP[0]>=70){echo "red";}else if($AcPoP[0]<=30){echo "green";}elseif($AcPoP[0]>30&&$AcPoP[0]<=50){echo "blue";}else echo "yellow";?>">
    <?="可能降雨率".$AcPoP[0]."%"?></span>
</div>

</body>
</html>
