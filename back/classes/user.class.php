<?php

Class User extends Connection{

   public function test_input($data){
      $data = trim($data);
      $data = htmlspecialchars($data);
      $data = strip_tags($data);
      $data = stripslashes($data);
      return $data;
  }
     
     /**
      * alreadyIn
      *
      * @param  mixed $email
      * @return void
      */
     public function alreadyIn(string $email){

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        $row = $stmt->fetch();
        $this->close();
        return $row;
     }

     public function LogIn($email, $password) {
      $userDetails = $this->alreadyIn($email);
      if ($userDetails !== false) {
          $hashedPass = $userDetails["passwrd"];
          return password_verify($password, $hashedPass);
      } else {
          return false;
      }
  }
  
 
  function validatePassword($password) {
      $requirements = array(
          "/[A-Z]/",    
          "/\d/",      
          "/[!@#$%^&*(),.?\":{}|<>]/", 
          "/^.{8,}$/"     
      );
  
      $results = array_map(function($regex) use ($password) {
          return preg_match($regex, $password);
      }, $requirements);
  
      return array_reduce($results, function($carry, $item) {
          return $carry && $item;
      }, true);
  }
  
     public function selectUserIdRow($id) {
      $sql = "SELECT * FROM users WHERE user_id = ?";
      $stmt = $this->connect()->prepare($sql);
      $stmt->bindParam(1,$id);
      $stmt->execute();
      $row = $stmt->fetch();
      $this->close();
      return $row;
     }
     
     /**
      * insertUser
      *
      * @param  mixed $username
      * @param  mixed $passwrd
      * @param  mixed $email
      * @param  mixed $visible
      * @return void
      */
     public function insertUser(string $username, string $passwrd, string $email, bool $visible) {
        if($this->alreadyIn($email)){
            return;
        }else{
            $passwordHash = password_hash($passwrd, PASSWORD_DEFAULT);
            $sql= "INSERT into users (userName, passwrd, email, visible) values(?,?,?,?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $passwordHash);
            $stmt->bindParam(3, $email);
            $stmt->bindParam(4, $visible);
            $stmt->execute();
            $row = $stmt->fetch();
            $this->close();
            return $row;
        }
     }

      public function isRegistered($user_name){
         $sql ="SELECT * FROM users WHERE userName =?";
         $stmt = $this->connect()->prepare($sql);
         $stmt->bindParam(1, $user_name);
         $stmt->execute();
         $row = $stmt->fetch();
         $this->close();
         return ($row !==false);
        
      }

      public function searchUserAsWrite($user_name){
       $sql = "SELECT userName FROM users WHERE userName LIKE ? AND visible = 1";
      $stmt = $this->connect()->prepare($sql);
       $stmt->bindParam(1, $searchValue);
       $searchValue = $user_name."%";
       $stmt->execute();
       $col = $stmt->fetchAll();
       $this->close();
      return $col;
      }

      public function isVisible ($user_name){
         $user = $this->test_input($user_name);
         $sql = "SELECT * FROM users WHERE userName = ? AND visible = 1";
         $stmt = $this->connect()->prepare($sql);
         $stmt->bindParam(1, $user);
         $stmt->execute();
         $row = $stmt->fetch();
         $this->close();
         return $row;
      }

      public function getVisibility ($user_id){
        $sql = "SELECT visible FROM users WHERE user_id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $user_id);
         $stmt->execute();
         $row = $stmt->fetch();
         $this->close();
         return $row["visible"];
      }

      public function changeVisibility ($user_id){
        $isVisible = $this->getVisibility($user_id);
        $newVisible = ($isVisible == 1)? 0:1;
        $sql = "UPDATE users SET visible = ? WHERE user_id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $newVisible);
        $stmt->bindParam(2, $user_id);
        $stmt->execute();
        $this->close();

      }


      public function followCheck($followed, $follower) {
         $sql = "SELECT * FROM follows WHERE followed_id = :followed AND follower_id = :follower";
         $statement = $this->connect()->prepare($sql);
         $statement->execute([
             ':followed' => $followed,
             ':follower' => $follower
         ]);
         $row = $statement->fetch();
         $this->close();
         return ($row !== false); 
     }
     

     public function follow ($followed, $follower){
        if(!$this->followCheck($followed, $follower)){
         $date = date("Y-m-d");
         $sql = "INSERT INTO follows(followed_id, follower_id, follow_date) VALUES($followed, $follower, $date)";
         $stmt = $this->connect()->prepare($sql);
         $stmt->execute();
         $row = $stmt->fetch();
         $this->close();
         return ($row !== false);
     }
     return false;
   }

   public function unfollow($followed, $follower) {
    $sql = "DELETE FROM follows WHERE followed_id = ? AND follower_id = ?";
    $statement = $this->connect()->prepare($sql);
    $result = $statement->execute([$followed, $follower]);
    $rowCount = $statement->rowCount();
    $this->close();
    if ($rowCount == 1) {
        return true;
    } else {
        return false;
    }
}



  //  public function unfollow($followed, $follower)
  //  {
  //      if ($this->followCheck($followed, $follower)) {
  //        $sql = "DELETE FROM follows WHERE followed_id = $followed AND follower_id = $follower";  
  //          $statement = $this->connect()->prepare($sql);
  //          $result = $statement->execute();
  //          $this->close();
  //          return $result; // Devolver true si la operación se realizó con éxito, false de lo contrario
  //      } else {
  //        echo "estas en false";
  //          return false; // Devolver false si no se encontró ninguna relación de seguimiento para eliminar
  //      }
  //  }
   

    public function showFollowers ($followed_id){
      $sql="SELECT follower_id FROM follows WHERE followed_id  = $followed_id";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll();
      $this->close();
      return $rows;
    }

    public function showFollows ($follower_id){
      $sql="SELECT followed_id FROM follows WHERE follower_id  = $follower_id";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll();
      $this->close();
      return $rows;
    }

    public function countFollowers($followed_id){
      return count($this->showFollowers($followed_id));
    }

    public function countFollows ($follower_id){
      return count($this->showFollows($follower_id));
    }
    

    public function selectQuotes(){
      $sql = "SELECT * FROM quotes";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll();
      $this->close();
      return $rows;
    
    }

    public function updatePassword($email, $password){
      $sql ="UPDATE users SET passwrd = ? WHERE email = ?";
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $this->connect()->prepare($sql);
      $stmt->bindParam(1,$passwordHash);
      $stmt->bindParam(2,$email);
      $stmt->execute();
      $this->close();
    }

    public function deleteUser($id){
      $sql="DELETE FROM users WHERE user_id = ?";
      $stmt = $this->connect()->prepare($sql);
      $stmt->bindParam(1,$id);
      $stmt->execute();
      $this->close();
    }
}