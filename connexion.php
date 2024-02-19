<?php
 $servername = 'localhost';
 $username = 'root';
 $password = '';

   try{
    $conn = new PDO("mysql:host=$servername;dbname=tresorperso", $username, $password);   
}
catch(PDOException $e){
  echo "Erreur de connexion : " . $e->getMessage();
}
