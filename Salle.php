
<?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  require_once('db.php');
  session_start();
  $date = $_SESSION['date'];
  $heure = $_SESSION['heure'];
  // Vérifier si la date et l'heure ont été sélectionnées dans le formulaire
  if (isset($date) && isset($heure)) {

    // Sélectionner toutes les salles qui ne sont pas réservées à la date et l'heure choisies
    $stmt = $pdo->prepare("
      SELECT *
      FROM salle
      WHERE idsalle NOT IN (
        SELECT idsalle
        FROM reservation
        WHERE date = :date AND heure = :heure
      )
    ");
    $stmt->execute(array('date' => $date, 'heure' => $heure));
    $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Afficher les salles disponibles dans un menu déroulant
    echo '<h2>Salles disponibles :</h2>';
    echo '<form method="POST" action="reservation.php">';
    echo '<select name="idsalle">';
    foreach ($salles as $salle) {
      echo '<option value="' . $salle['idsalle'] . '">' . $salle['nomsalle'] . '</option>';
    }
    echo '</select><br><br>';
    echo '<input type="hidden" name="date" value="' . $date . '">';
    echo '<input type="hidden" name="heure" value="' . $heure . '">';
    echo '<button type="submit">Réserver cette salle</button>';
    echo '</form>';
  }
?>
