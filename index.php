<?php
session_start();
//require_once "json.php";
require "connect.php";

$sql2 = "select * from forecast";
$result = mysqli_query($link, $sql2);

//echo $_POST['selcountry'];

//判斷下拉式是否有選擇
//echo $_POST['selcountry'];
//echo $selectvalue;
$res = "";
if (isset($_POST['selcountry'])) {
    $selectvalue = isset($_POST['selcountry']) ? $_POST['selcountry'] : '';

    $_SESSION["selectId"] = $selectvalue;
    // header("Location: Twoday.php");
   
    header("Location: liveweather.php");
}
$AT = $_SESSION["AT"];
$tPoP = $_SESSION["tPoP"];
//明天天氣
$TomoPoP = $tPoP[0];
$TomoPoP1 = $tPoP[1];
$TomoAT = $AT[0];
$TomoAT1 = $AT[1];
$TomoCI = $_SESSION["tCI"];
$TomoWx = $_SESSION["tWx"];
//
$name = $_SESSION["name"];
$Wx = $_SESSION["Wx"];
$PoP = $_SESSION["PoP"];
$MinT = $_SESSION["MinT"];
$MaxT = $_SESSION["MaxT"];
$CI = $_SESSION["CI"];
//
//後天天氣
$sqlac = "select CI,Wx,count(*) as count from `twoday` where Time=DATE_ADD(`date`,INTERVAL 2 day) GROUP by CI,Wx order by count desc limit 2";
$resultac = mysqli_query($link, $sqlac);
$AcCI = array();
$AcWx = array();
$AcPoP = $tPoP[2];
$AcPoP1 = $tPoP[3];
$AcAT = $AT[2];
$AcAT1 = $AT[3];
while ($rowac = mysqli_fetch_assoc($resultac)) {
    $AcCI[] = $rowac["CI"];
    $AcWx[] = $rowac["Wx"];
}
//星期幾
$sqlw = "select DISTINCT DATE_FORMAT(`fTime`,'%Y-%m-%d') as dtime from sevendays";
$weeka = array("星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");
$week = array();
$wresult = mysqli_query($link, $sqlw);
while ($wrow = mysqli_fetch_assoc($wresult)) {
    //echo $wrow["dtime"];
    $week[] = $wrow["dtime"];
}

//一週預測

$amsevenWxV = $_SESSION["amWxV"];
$amsevenWx = $_SESSION["amWx"];
$amsevenT = $_SESSION["amT"];
$amsevenCI = $_SESSION["amCI"];
$amsevenHum = $_SESSION["amHum"];
$pmsevenWxV = $_SESSION["pmWxV"];
$pmsevenWx = $_SESSION["pmWx"];
$pmsevenT = $_SESSION["pmT"];
$pmsevenCI = $_SESSION["pmCI"];
$pmsevenHum = $_SESSION["pmHum"];

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
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</head>

<body style="background-color:#fffeab;">
    <div class="pic">
        <div class="head">
            <h1>母湯氣象局</h1>
            <p style="color:black"><br><br><br>點選想要查詢的縣市</p>
        </div>
    </div>



    <div class="sel">
        <form method="post">
            <select class="form-control" name="selcountry" onchange="submit()" style="color:#e6e33c;background-color:#216096;width:97%">
                <option value="" selected disabled>請選擇縣市</option>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?= $row["countryId"] ?>" <?= $selectvalue == $row['countryId'] ? 'selected' : '' ?>><?= $row["countries"] ?></option>
                <?php } ?>
            </select>
        </form>
    </div>



    <div class="row rowl1 col-sm" style="background-color: #add2d9; min-height: 250px">
        <span class="cnname"><?= $name ?></span><span class="cnow">即時天氣</span>
        <p><br>
            <p>
                <span class="cWx"><?= "目前天氣：" . $Wx ?></span>
                <p><br>
                    <p>
                        <span class="cCI"><?= "舒適程度：" . $CI ?></span>
                        <hr style="border:1px solid blue; width:100%">
                        <span class="cMT"><?= $MinT . "˚C~" . $MaxT . "˚C" ?></span>
                        <span class="cPoP" style="color:<?php if ($PoP >= 70) {
                                                            echo "red";
                                                        } else if ($PoP <= 30) {
                                                            echo "green";
                                                        } elseif ($PoP > 30 && $PoP <= 50) {
                                                            echo "blue";
                                                        } else {
                                                            echo "yellow";
                                                        } ?>"><?= "降雨率" . $PoP . "%" ?></span>
    </div>


    <div class="row rowl2" style="background-color: #add2d9; min-height: 250px">
        <span class="cnname"><?= $name ?></span><span class="cnow">明天天氣</span>
        <p><br>
            <p>
                <span class="cWx"><?php if ($TomoWx[1] != $TomoWx[0]) {
                                        echo "天氣預報：" . $TomoWx[0] . $TomoWx[1];
                                    } else {
                                        echo "天氣預報：" . $TomoWx[0];
                                    } ?></span>
                <p><br>
                    <p>
                        <span class="cCI"><?php if ($TomoCI[1] != "") {
                                                echo "舒適程度：" . $TomoCI[0] . "至" . $TomoCI[1];
                                            } else {
                                                echo "舒適程度：" . $TomoCI[0];
                                            } ?></span>
                        <hr style="border:1px solid blue; width:100%">
                        <span class="cMT"><?= $TomoAT . "˚C~" . $TomoAT1 . "˚C" ?></span>
                        <span class="cPoP2" style="color:<?php if ($TomoPoP >= 70) {
                                                                echo "red";
                                                            } else if ($TomoPoP <= 30) {
                                                                echo "green";
                                                            } elseif ($TomoPoP > 30 && $TomoPoP <= 50) {
                                                                echo "blue";
                                                            } else {
                                                                echo "yellow";
                                                            }
                                                            ?>">
                            <?= "上午降雨率" . $TomoPoP . "%" ?></span>
                        <span class="cPoP3" style="color:<?php if ($TomoPoP1 >= 70) {
                                                                echo "red";
                                                            } else if ($TomoPoP1 <= 30) {
                                                                echo "green";
                                                            } elseif ($TomoPoP1 > 30 && $TomoPoP1 <= 50) {
                                                                echo "blue";
                                                            } else {
                                                                echo "yellow";
                                                            }
                                                            ?>"><?= "下午降雨率" . $TomoPoP1 . "%" ?></span>
    </div>



    <!--圖片-->
    <div class="imgs"><img src="./imagec/<?= $_SESSION["selectId"] ?>.jpg" height="100%" width="100%" /></div>



    <!--雨量觀測 -->
    <a href="rainview.php" class="btn btn btn-danger btn-lg rainv">雨量觀測</a>

    <div class="row rowl3" style="background-color: #add2d9; min-height: 250px">
        <span class="cnname"><?= $name ?></span><span class="cnow">後天天氣</span>
        <p><br>
            <p>
                <span class="cWx"><?php if ($AcWx[1] != $AcWx[0]) {
                                        echo "天氣預報：" . $AcWx[0] . $AcWx[1];
                                    } else {
                                        echo "天氣預報：" . $AcWx[0];
                                    } ?></span>
                <p><br>
                    <p>
                        <span class="cCI"><?php if ($AcCI[1] != "") {
                                                echo "舒適程度：" . $AcCI[0] . "至" . $AcCI[1];
                                            } else {
                                                echo "舒適程度：" . $AcCI[0];
                                            } ?></span>
                        <hr style="border:1px solid blue; width:100%">
                        <span class="cMT"><?= $AcAT . "˚C~" . $AcAT1 . "˚C" ?></span>
                        <span class="cPoP2" style="color:<?php if ($AcPoP >= 70) {
                                                                echo "red";
                                                            } else if ($AcPoP <= 30) {
                                                                echo "green";
                                                            } elseif ($AcPoP > 30 && $AcPoP <= 50) {
                                                                echo "blue";
                                                            } else {
                                                                echo "yellow";
                                                            }
                                                            ?>">
                            <?= "上午降雨率" . $AcPoP . "%" ?></span>
                        <span class="cPoP3" style="color:<?php if ($AcPoP1 >= 70) {
                                                                echo "red";
                                                            } else if ($AcPoP1 <= 30) {
                                                                echo "green";
                                                            } elseif ($AcPoP1 > 30 && $AcPoP1 <= 50) {
                                                                echo "blue";
                                                            } else {
                                                                echo "yellow";
                                                            }
                                                            ?>"><?php if(is_null($AcPoP1)){echo "尚未取得資料";}else{ echo "下午降雨率" . $AcPoP1 . "%" ;}?></span>
    </div>






    <div class="row" style="position:absolute;bottom:-110%;">
        <?php for ($i = 0; $i < 6; $i++) { ?>
            <div class="col-2 bord" style="background-color: #AAFFEE; height: 600px">
                <span class="datefont"><?= $weeka[date("w", strtotime("$week[$i]"))] . "&emsp;&emsp;&emsp;上午" ?></span>
                <hr style="border:1px solid; color:#444444;width:100%">
                <img width="50" src="./image/<?= $amsevenWxV[$i] ?>.png" /><br>
                <span style="font-size:15px;">天氣狀況：<?= $amsevenWx[$i] . "<br>" ?></span>
                <span style="font-size:15px;">溫度：<?= $amsevenT[$i] . "<br>" ?></span>
                <span style="font-size:15px;">舒適度：<?= $amsevenCI[$i] . "<br>" ?></span>
                <span style="font-size:15px;">相對濕度：<?= $amsevenHum[$i] ?></span>
                <hr class="hrs" style="border:1px solid; color:#444444;width:100%">
                <span class="datefontpm">下午</span>
                <div class="pm">
                    <img width="50" src="./image/<?= $pmsevenWxV[$i] ?>.png" /><br>
                    <span style="font-size:15px;">天氣狀況：<?= $pmsevenWx[$i] . "<br>" ?></span>
                    <span style="font-size:15px;">溫度：<?= $pmsevenT[$i] . "<br>" ?></span>
                    <span style="font-size:15px;">舒適度：<?= $pmsevenCI[$i] . "<br>" ?></span>
                    <span style="font-size:15px;">相對濕度：<?= $pmsevenHum[$i] ?></span>
                </div>
            </div>
        <?php } ?>
    </div>
</body>

</html>