<?php
include '../src/includes/autoloader.inc.php';

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['user_id'])) {
    try {
        $article = new Article();
        $user_id = $_POST['user_id'];
        $articles = $article->retrieveArticles($user_id);
       

         echo json_encode($articles);
    } catch (Exception $e) {
        echo json_encode(["error" => "Error en el servidor: " . $e->getMessage()]);
    }
}

