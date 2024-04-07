<?php
include '../src/includes/autoloader.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["email"])){

    $email = $_POST["email"];
    $user = new User();
    $isUsed = $user->alreadyIn($email);

    try {
        echo json_encode(["registered" => (bool)$isUsed]); 
    } catch (Exception $e) {
        echo json_encode(["error" => "Error en el servidor"]);
    }
}