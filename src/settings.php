<?php
require_once "config.php";
include '../src/includes/autoloader.inc.php';
if(!isset($_SESSION["username"])){
    header("location:index.php");
    exit();
}else{
    $user = new User();
    $visible = $user->getVisibility($_SESSION["username"]);
    if(isset($_POST["send"])){
       
        $user->changeVisibility($_SESSION["username"]);
        header("Location: settings.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="shortcut icon" href="DW.png" type="image/x-icon">
    <link rel="stylesheet" href="css/background.css">
    <script src="javascript/dark-mode.js" defer></script>
    <title>Settings</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container-fluid">
    <a href="" class="navbar-brand text-info fw-semibold fs-4">DailyWrote</a>
    <button class="navbar-toggler" type="button"
     data-bs-toggle="offcanvas"
     data-bs-target="#sideBar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <section 
    class="offcanvas offcanvas-start bg-dark" 
    id="sideBar" 
    tabindex="-1">
        <div class="offcanvas-header" data-bs-theme="dark">
            <h5 class="offcanvas-title text-info">Nombre del Usuario</h5>
            <button class="btn-close" type="button" 
            aria-label="Close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between px-0">
            <ul class="navbar-nav fs-5 justify-content-evenly">
                <li class="navi-item p-3 py-md-1"><a class="nav-link" href="profile.php">Home</a></li>
                <li class="navi-item p-3 py-md-1"><a class="nav-link" href="posts.php">Projects</a></li>
                <li class="navi-item p-3 py-md-1"><a href="about.php" class="nav-link">About</a></li>
                <li class="navi-item p-3 py-md-1"><a href="settings.php" target="_blank" class="nav-link">Settings</a></li>
                <li class="navi-item p-3 py-md-1">
                    <form action="profile.php" method="POST">
                    <input type="submit" value="End Session" name="end" class="btn btn-light">
                    </form>
                </li>
            </ul>

            <div class="d-lg-none align-self-center py-3">
                <a href=""><i class="bi bi-twitter-x px-2 text-info fs-2"></i></a>
                <a href=""><i class="bi bi-facebook px-2 text-info fs-2"></i></a>
                <a href=""><i class="bi bi-whatsapp px-2 text-info fs-2"></i></a>
                <a href=""><i class="bi bi-github px-2 text-info fs-2"></i></a>
            </div>
        </div>
    </section>
</div>
</nav>
<div id="main">
<section class="container-fluid">
<div class="row">
    <div class="col-4 mx-auto my-4">
       <div id="security" class="bg-secondary-subtle border border-black p-2">
        <h3>Security</h3>
        <a href="recoverpsw.php" class="d-block">Change Password</a>
       </div>
    
       <div id="privacy" class="bg-secondary-subtle border border-black p-2 mt-2">
        <h3>Privacy</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method ="POST" name="changeVisibility">
        <label for="">Can anybody find me?</label>
        <input type="checkbox" name="visibility" id="" <?php echo ($visible == 1) ? "checked" : ""; ?>>
        <span class="d-block">if checked, your profile could be find by anyone who looks for you in the search engine</span>
        <input type="submit" value="Apply Changes" name="send" id="send">
        </form>
       </div>
       <div id="serious" class="bg-secondary-subtle border border-black p-2 mt-2">
        <h3>Delete Account</h3>
      
            <p>DELETE ACCOUNT</p>
            <input type="button" value="DELETE" class="bg-primary" id="delete"> <span>This is for good!</span>
        
       </div>
    </div>
</div>
</section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script>
     document.addEventListener("DOMContentLoaded", function() {
        // Verifica si el formulario se ha enviado
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    });

  
      function createWindow(){
        let options ="width=500,height=250,top=100,left=100,scrollbars=yes,resizable=no";
        let windowWar = window.open("delete.php"," Warning Window", options);
      }

    document.getElementById("delete").addEventListener("click", createWindow);


     
</script>
</body>
</html>