<?php
include '../src/includes/autoloader.inc.php';

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['user_id'])) {
    try {
        $article = new Article();
        $user_id = $_POST['user_id'];
        
        // Obtener los valores de limit y offset de la solicitud POST
        $limit = isset($_POST['limit']) ? $_POST['limit'] : 10; // Por defecto, 10 artículos por página
        $offset = isset($_POST['offset']) ? $_POST['offset'] : 0; // Por defecto, comenzamos desde el principio
        
        $articles = $article->retrieveArticlesPaginated($user_id, $limit, $offset);
        
        echo json_encode($articles);
    } catch (Exception $e) {
        echo json_encode(["error" => "Error en el servidor: " . $e->getMessage()]);
    }
}
