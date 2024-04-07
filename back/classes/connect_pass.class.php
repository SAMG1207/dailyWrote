<?php

class Pass{
    
    private $host ="localhost";
    private $user ="root";
    private $pwd ="";
    private $dbName ="home";

    private $pdo;

    protected function connect(){
        $dsn = 'mysql:host='. $this->host .';dbname='. $this->dbName;
        try{
            $this->pdo = new PDO($dsn, $this->user, $this->pwd);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $this->pdo;
        }catch(PDOException $e){
            die("Connection failed: " . $e->getMessage());
        }
    }

    protected function close(){
        return $this->pdo = null;
    }

    public function giveMeG($variable){
        $sql="SELECT clav FROM claves WHERE donde = ?";
        $stmt=$this->connect()->prepare($sql);
        $stmt->bindValue(1, $variable);
        $stmt->execute();
        $pass = $stmt->fetch();
        $this->close();
        return $pass["clav"];
    }
}