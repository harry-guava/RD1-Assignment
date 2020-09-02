<?php
session_start();
//require_once "json.php";
require("connect.php");

$sql2 = "select * from forecast";
$result = mysqli_query($link, $sql2);
$res =  "";
//echo $_POST['selcountry'];
$selectvalue = isset($_POST['selcountry'])? $_POST['selcountry'] : '';
//判斷下拉式是否有選擇
//echo $_POST['selcountry'];
echo $selectvalue;
$_SESSION["selectId"] = $selectvalue;
if(isset($_POST['selcountry']))
{
    header("Location: countries.php");
}
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
    if ( window.history.replaceState )
    {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
</head>
<body>

<div class="head">
    <h1>母湯氣象局</h1>
    <p style="color:black">點選想要查詢的縣市</p>
</div> 
<div class ="sel">
  <form  method="post">
  <select name = "selcountry" onchange="submit()">
  <?php while ($row = mysqli_fetch_assoc($result)) { ?>

  <option value= "<?=$row["countryId"]?>"<?= $selectvalue == $row['countryId'] ? 'selected' : ''?>><?=$row["countries"]?></option>
  <?php }?>
  </select>
  </form>
</div>
<div id="result"></div>
</body>
</html>
