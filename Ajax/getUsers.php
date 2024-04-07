<?php
include '../src/includes/autoloader.inc.php';

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["userName"])){

    $user = new User();
    $name = $_POST['userName'];
    $users=$user->searchUserAsWrite($name);
    echo json_encode($users);
}