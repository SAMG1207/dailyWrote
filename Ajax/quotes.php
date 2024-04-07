<?php

require_once "config.php";
include '../src/includes/autoloader.inc.php';


    try {
        $user = new User();
        $quotes = $user->selectQuotes();
        echo json_encode($quotes); // Codificar todas las citas en un array y enviarlo como respuesta
    } catch (Exception $e) {
        echo json_encode(["error" => "Error en el servidor"]);
    }





