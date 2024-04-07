<?php
include '../src/includes/autoloader.inc.php';

if (isset($_POST['article_id'])){

    $art = new Article();
    $artId = $_POST["article_id"];
    $art->deleteArticle($artId);
    error_log("Received request to delete article with ID: " . $_POST['article_id']);
}