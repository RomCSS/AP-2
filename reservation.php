<?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  require_once('db.php');
  session_start();

  // Vérifier si la salle, la date et l'heure ont été envoyées en POST
  if (isset($_POST['idsalle']) && isset($_SESSION['date']) && isset($_SESSION['heure'])) {
    $idsalle = $_POST['idsalle'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $iduser = $_SESSION['iduser']; // ou récupérez l'id de l'utilisateur connecté à partir de la session

    // Insérer la nouvelle réservation dans la table reservation

    //$stmt = $pdo->prepare("INSERT INTO reservation (date, heure, idsalle, iduser) VALUES (:date, :heure, :idsalle, :iduser)");
    //$stmt->execute(array('date' => $date, 'heure' => $heure, 'idsalle' => $idsalle, 'iduser' => $iduser));

    $stmt = $pdo->prepare("INSERT INTO reservation (date, heure, idsalle, iduser) VALUES (?, ?, ?, ?)");
    $stmt->execute(array( $date, $heure, $idsalle, $iduser));
    echo 'La réservation a été créée avec succès !';
  }
?>
<br>
<br>
    <html>
    <form method="POST" action="planning.php">
  <button type="submit">Continuer</button>
</form>
</html>
