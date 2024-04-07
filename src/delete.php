<?php
require_once "config.php";
include '../src/includes/autoloader.inc.php';

if (!isset($_SESSION["username"])) {
    header("location: index.php"); // Corregido el nombre del archivo
    exit();
} else {
    if (isset($_POST["yes"])) {
        $user = new User();
        $user->deleteUser($_SESSION["username"]);
        session_unset();
        session_abort();
        session_destroy();
        header("location: delete.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
    <style>
        body {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="alert alert-warning" role="alert">
            <h4 class="alert-heading">Delete This Account</h4>
            <p>This decision is irreversible</p>
            <p>Delete this account?</p>
            <hr>
            <div class="d-grid gap-2">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="form">
                    <button type="submit" id="yes" class="btn btn-danger" name="yes">YES</button> <!-- Corregido el atributo type y agregado el formulario -->
                    <button type="button" id="no" class="btn btn-secondary" name="no">NO</button> <!-- Corregido el atributo type -->
                    <p class="text-center">You have <span id ="time"> </span> seconds left</p>
                </form>
            </div>
        </div>
    </div>
    <!-- Enlace al archivo JavaScript de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", reverse);
        time = 5;
        document.getElementById("no").addEventListener("click", function(){
            window.close();
        });

        document.getElementById("yes").addEventListener("click",()=>{
              document.getElementById("form").submit();
              window.close()
        })
         
        function reverse(){
            document.getElementById("time").innerText=time;
            if(time==0){
                window.close();
            }else{
                time-=1;
                setTimeout(reverse,1000)
            }
        }
        
    </script>
</body>
</html>
