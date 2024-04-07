<?php
include '../src/includes/autoloader.inc.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["userId"]) && isset($_POST["myId"])) {
    try {
        $user = new User();
        $userId = $_POST["userId"];
        $myId = $_POST["myId"];
        $unfollowed = $user->unfollow($userId, $myId);

        echo json_encode(["success" => $unfollowed]);
    } catch (PDOException $e) {
        // Captura errores de conexión o consulta SQL
        echo json_encode(["error" => "Error en la consulta SQL: " . $e->getMessage()]);
    } catch (Exception $e) {
        // Captura otros errores
        echo json_encode(["error" => "Error en el servidor: " . $e->getMessage()]);
    }
}

