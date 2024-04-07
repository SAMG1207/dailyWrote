<?php
include '../src/includes/autoloader.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["username"])){
    $username = $_POST["username"];
    $user = new User();
    $isUser = $user->isRegistered($username);


    try {
        echo json_encode(["registered" => (bool)$isUser]); 
    } catch (Exception $e) {
        echo json_encode(["error" => "Error en el servidor"]);
    }
}    