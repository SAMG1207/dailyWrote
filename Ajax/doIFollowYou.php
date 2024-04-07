<?php
include '../src/includes/autoloader.inc.php';

if($_SERVER["REQUEST_METHOD"]==="POST" && isset($_POST["userId"]) && isset($_POST["myId"])){
    $user = new User();
    $userId = $_POST["userId"];
    $myId = $_POST["myId"];
 
    $register = $user->followCheck($userId, $myId);
    
    try {
        echo json_encode(["follow" => (bool)$register]); 
    } catch (Exception $e) {
        echo json_encode(["error" => "Error en el servidor"]);
    }
}