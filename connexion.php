<?php   
error_reporting(E_ALL);
ini_set("display_errors", 1);
require ("db.php");
$pdo=connexion();
$stmt=userexist($pdo);

$data = $stmt->fetchall();
if ( count($data) == 1) { 
    //vérifie si l'utilisateur existe dans la base de donnée 
    session_start();
    $row = $data[0];
    $_SESSION['Login'] = $row['Login'];
    $_SESSION['iduser'] = $row['id'];
    header('Location: index.php');

}else{
    header('location: login.php?login_err=loginorpassword'); die();
 }
 ?>