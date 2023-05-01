<?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  require_once('db.php');
  session_start();
  $_SESSION['date'] = isset($_POST['date']) ? $_POST['date'] : '';
  $_SESSION['heure'] = isset($_POST['heure']) ? $_POST['heure'] : '';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Page d'accueil</title>
</head>
<body>
	<h1>Bienvenue sur la page d'accueil</h1>
	<p>Veuillez choisir l'une des options suivantes :</p>
	<a href="planning.php"><button>Consulter vos réservations</button></a>
	<a href="dateheure.php"><button>Créer une réservation</button></a>
</body>
</html>