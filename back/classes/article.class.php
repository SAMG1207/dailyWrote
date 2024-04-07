<?php
require '../vendor/autoload.php';
require_once 'connect_pass.class.php';


use Google\Cloud\Translate\V2\TranslateClient;
Class Article extends Connection{

    private $pass;
    private $translate;
   private $sT = array(
        "child porn info",
        "porn info",
        "naked pictures",
        "children porn",
        "children naked",
        "teen naked",
        "sell teen",
        "video info"
    );
    
    private $dT = array(
        "sell some weed",
        "sell cocaine",
        "sell drugs info here",
        "sell heroine",
        "sell heroine",
        "sell fentanile",
        "buy cocaine",
        "buy drugs"
    );

    

    private $aT = array("sell guns", "alahu akbar");


    private $ban = array();
    
    public function __construct(){
       $this->pass = new Pass();
       $this->ban = array_merge($this->sT, $this->dT, $this->aT);
       $this->translate = new TranslateClient([
        "projectId" =>"My Maps Project",
         "key"=> $this->pass->giveMeG("my maps project")
       ]);
    }

    public function translateWithApi($title, $article){
       
        $text = $title . ' ' . $article;

        $translation = $this->translate->translate($text, [
            'target' => 'en', 
        ]);
        if($this->checkBan($translation['text'])){
            return true;
        }
        
        return false; 
    }
    
    
    
    public function checkBan($parag){
       foreach($this->ban as $bans){
        if(stripos($parag, $bans) !== false){
            return true;
        }
       
       }
       return false;
    }

    public function countStrikes ($user_id){
        $sql = "SELECT * FROM entries WHERE user_id = $user_id AND strike = 1";
        $stmt=$this->connect()->prepare($sql);
        $stmt->execute();
        $total = $stmt->rowCount();
        $this->close();
        return $total;
    }
    
    public function test_input($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = strip_tags($data);
        $data = stripslashes($data);
        return $data;
    }

    /**
     * insertStrike() es innecesario debido a que se ha implementado un trigger SQL que hace la inserción automatica si 
     * el valor de strike es 1
     */
    public function insertArticle ($user_id, $visible, $title1,  $font_family, $font_size, $article1){
        $strike = 0;
        $fecha = date('Y-m-d');
        
        if(!empty($title1)){
            $title = $this->test_input($title1);
        }else{
            return false;
        }
    
        if(!empty($article1)){
            $article = $this->test_input($article1);
        }else{
            return false;
        }
        
        // Verificar si hay traducción prohibida en el título o el artículo
        if($this->translateWithApi($title, $article)){
            $strike = 1;
        }
        
        // Preparar la consulta SQL para insertar la entrada
        $sql = "INSERT into entries (user_id, date, visible, title, font_family, font_size, entry, strike) VALUES (?,?,?,?,?,?,?,?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(2, $fecha);
        $stmt->bindParam(3, $visible);
        $stmt->bindParam(4, $title);
        $stmt->bindParam(5, $font_family);
        $stmt->bindParam(6, $font_size);
        $stmt->bindParam(7, $article);
        $stmt->bindParam(8, $strike);
        
        // Ejecutar la consulta y obtener el resultado
        $stmt->execute();
        $row = $stmt->fetch();
        
        // Cerrar la conexión y devolver el resultado
        $this->close();
        return $row;
    }
    

    public function retrieveArticles($user_id){
        $sql="SELECT * FROM entries WHERE user_id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1,$user_id);
        $stmt->execute();
        $col = $stmt->fetchAll();
        $this->close();
        return $col;
    }

    public function giveMeUserId($name){
        $sql = "SELECT user_id FROM users WHERE userName = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1,$name);
        $stmt->execute();
        $col = $stmt->fetchColumn();
        $this->close();
        return $col;
    }

    public function retrieveArticlesFromOtherUser($user_id){
        $sql="SELECT * FROM entries WHERE user_id = ? AND visible = true AND strike = 0";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1,$user_id);
        $stmt->execute();
        $col = $stmt->fetchAll();
        $this->close();
        return $col;
    }
    public function deleteArticle($article_id){
        $sql ="DELETE FROM entries WHERE entry_id = $article_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $this->close();
    }

    public function retrieveArticlesPaginated($userId, $lastArticleId, $articulosPorPagina) {
     
        if ($lastArticleId == 0) {
            // Si $lastArticleId es 0, significa que estamos en la primera página
            $sql = "SELECT * FROM entries WHERE user_id = :userId ORDER BY entry_id DESC LIMIT :limit";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $articulosPorPagina, PDO::PARAM_INT);
        } else {
            // Si $lastArticleId no es 0, seleccionamos los artículos antes del artículo con el ID dado
            $sql = "SELECT * FROM entries WHERE user_id = :userId AND entry_id < :lastArticleId ORDER BY entry_id DESC LIMIT :limit";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':lastArticleId', $lastArticleId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $articulosPorPagina, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        $articulos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $articulos;
    }
    
    public function retrieveArticlesFromOtherUserPaginated($user_id, $start, $pagesPerPage){
       
        $sql="SELECT * FROM entries WHERE user_id = ? AND visible = true AND strike = 0 LIMIT $start, $pagesPerPage";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1,$user_id);
        $stmt->execute();
        $col = $stmt->fetchAll();
        $this->close();
        return $col;
    }


    
    public function givemePages($user_id){
        $total = count($this->retrieveArticlesFromOtherUser($user_id));
     
        $pages = ceil($total/4);
        return $pages;
    }
}