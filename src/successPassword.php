<?php
session_start();
include '../src/includes/autoloader.inc.php';
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["pass1"]) && isset($_POST["email"]) && ($_POST["token"]===$_SESSION["token"])){
    $_SESSION["complete"]=1;
    $user = new User();
    $pass = $user->validatePassword($_POST["pass1"]);
    if($pass){
        $user->updatePassword($_POST["email"], $_POST["pass1"]);
        $recovery = new Recover();
        $recovery->deleteAllKeys($_POST["email"]);
    }else{
        header("location: newPassword.php");
        exit();
    }
   
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="DW.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>The DailyWrote</title>
</head>
<body onload="deshabilitaRetroceso()">
    <h1 class="text-light text-center">
       The DailyWrote
    </h1>
    <p class="text-light text-center">
        Your password has been changed, you can't go back.
   </p>
    <p class="text-light text-center">
         <a href="index.php">TheDailyWrote</a>
    </p>
    <script>
        function deshabilitaRetroceso(){
    window.location.hash="no-back-button";
    window.location.hash="Again-No-back-button" //chrome
    window.onhashchange=function(){window.location.hash="";}
}
    </script>
</body>
</html>
<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header("Refresh: 3; URL=index.php");
}else{
    header("location: newPassword.php");
    exit();
}
?>