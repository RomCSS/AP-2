<?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  require_once('db.php');
  session_start();

// Récupérer les réservations en fonction de la date et l'heure choisies
// Récupérer les réservations en fonction de la date et l'heure choisies
$date = $_SESSION['date'];
$heure = $_SESSION['heure'];
$sql = "SELECT * FROM reservation WHERE date = ? AND heure = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$date, $heure]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html>
  <head>
    <title>Planning</title>
  </head>
  <body>
    <h2>Réservations pour le <?php echo $date ?> à <?php echo $heure ?></h2>
    <table>
      <thead>
        <tr>
          <th>ID réservation</th>
          <th>Date</th>
          <th>Heure</th>
          <th>Salle</th>
          <th>Utilisateur</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reservations as $reservation) : ?>
          <tr>
            <td><?php echo $reservation['idreservation'] ?></td>
            <td><?php echo $reservation['date'] ?></td>
            <td><?php echo $reservation['heure'] ?></td>
            <td><?php echo $reservation['idsalle'] ?></td>
            <td><?php echo $reservation['iduser'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </body>
</html>
