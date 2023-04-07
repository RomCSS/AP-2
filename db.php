<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);


// Connexion à la bdd :
function connexion(){
    $host = 'localhost';
    $db   = 'ap2';
    $user = 'root';
    $pass = 'root';
    $dsn = "mysql:host=$host;dbname=$db";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } 
    catch (\PDOException $e) {
        print"ERREUR:".$e;
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
    return $pdo;  
}


$pdo=connexion();
function userexist($pdo){
    $stmt = $pdo->prepare("
        SELECT u.iduser AS 'id', u.Login AS 'Login' , u.Password
        FROM user u
        WHERE Login= ? and Password= ? "
    );
    $stmt->execute(array($_POST['login'], $_POST['password']));
return $stmt;

}
?>