<?php
require_once "config.php";
include '../src/includes/autoloader.inc.php';
if(!isset($_SESSION["username"])){
    header("location:index.php");
    exit();
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
    <title>About</title>
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
                <li class="navi-item p-3 py-md-1"><a href="" class="nav-link">About</a></li>
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
<div class="container">
    <div class="row">
        <div class="col">
        <h2>English:</h2>
         <p>This project is created by Sergio Moreno as a web development endeavor aimed at enhancing his skills and serving as a portfolio project.</p>
        <p>The project functions as a blog promoting freedom of speech, with additional features aimed at increasing complexity. 
            One notable feature is the implementation of a banning system. 
            If a user submits a sentence that violates the platform's guidelines, regardless of the language used, 
            the system identifies and records the infraction by adding a strike to the user's comment record in the database. 
            This functionality is achieved using the Google Translate API (API keys are not shared on GitHub).
             If a user accumulates two strikes, their account will be banned.
     </p>
      <p>
      Another noteworthy feature is the ability to send emails to users for password resets. 
      This functionality is implemented using <em>Composer -> PHP Mailer</em>. Also requiring an API key.
      </p>
        </div>
    </div>
</div>

<div class="container ">
    <div class="row my-3">
        <div class="col">
        <h2>Español:</h2>
 <p>Este proyecto es realizado por Sergio Moreno como proyecto de desarrollo web, está hecho para mejorar sus capacidades y como proyecto portafolio</p>
 <p>El proyecto es un blog que invita a la libertad de expresión, pero se le quiso añadir más características, haciéndolo más coplejo.
    El proyecto tiene un sistema de bloqueos. Cuando un usuario escribe un comentario que no está permitido, no importa en qué idioma se haya escrito, el sistema captura el comentario y añade un strike en la fila de este comentario en la base de datos.
    La forma en que lo hice ha sido usando <a href="https://cloud.google.com/translate" target="_blank">Google Translate API</a> (No se comparten las API Keys en GitHub),
        si el usuario tiene dos strikes, su cuenta será bloqueada
 </p>
 <p>
   Otra destacable característica es la capacidad de enviar emails al usuario para que este pueda obtener una nueva contraseña.
   Esto se logró usando <em>Composer -> PHP Mailer</em>. Esto también usa una API KEY.
 </p>
        </div>
    </div>
</div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>