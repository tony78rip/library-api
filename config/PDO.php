<?php 
$host = "localhost";
$username = "root";
$password = "";
$dbname = "book api";

//on recupère les donnée en tableau associatif
$options = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

//si ca ne fonctionne pas on recuperrera les erreurs avec le block catch
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password,$options);

} catch (PDOException $error){

// message si erreur
    die("erreur : " . $error->getMessage());

}

?>