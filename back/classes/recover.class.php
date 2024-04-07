<?php

Class Recover extends Connection{

public function insertKey($email, $key, $expDate){
    $sql = "INSERT INTO password_reset_temp (email, p_key, expDate) values (?,?,?)";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(1, $email);
    $stmt->bindParam(2, $key);
    $stmt->bindParam(3, $expDate);
    $stmt->execute();
    $this->close();
}
public function getKey($email, $key){
    $sql = "SELECT * FROM password_reset_temp WHERE email = ? AND p_key = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(1, $email);
    $stmt->bindParam(2, $key);
    $stmt->execute(); // Pasar los valores como un array al método execute
    $row = $stmt->fetch();
    $this->close();
    return $row;
}

public function deleteAllKeys($email){
    $sql = "DELETE FROM password_reset_temp WHERE email = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(1, $email);
    $stmt->execute();
    $this->close();
}

public function checkIfKeyExists($key){
    $sql = "SELECT * FROM password_reset_temp WHERE p_key = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$key]);
    $row = $stmt->fetch();
    $this->close();
    return ($row !== false); // Check if row is found
}

}