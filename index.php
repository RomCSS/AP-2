<?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  require_once('db.php');
  session_start();
  $_SESSION['date'] = isset($_POST['date']) ? $_POST['date'] : '';
  $_SESSION['heure'] = isset($_POST['heure']) ? $_POST['heure'] : '';
?>

<html>
  <h2> Bonjour cher Utilisateur </h2>
  <br>
<?php
  // On vérifie si l'utilisateur est connecté     
if(!isset($_SESSION['iduser'])){
    header("Location: login.php"); // rediriger vers la page de connexion si l'utilisateur n'est pas connecté
}

// Récupérer l'identifiant de l'utilisateur connecté
$user_id = $_SESSION['iduser'];

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['iduser'])) {
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['iduser'];

// Traitement du filtre de salle
$salle_filter = isset($_POST['salle_filter']) ? $_POST['salle_filter'] : null;

// Traitement de la suppression de réservation
if (isset($_POST['delete_reservation'])) {
    $reservation_id = $_POST['reservation_id'];
    $stmt = $pdo->prepare("DELETE FROM reservation WHERE idreservation = :id AND iduser = :user_id");
    $stmt->execute(array(':id' => $reservation_id, ':user_id' => $user_id));
}

// Traitement de la modification de réservation
if (isset($_POST['modify_reservation'])) {
    $reservation_id = $_POST['reservation_id'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $salle_id = $_POST['salle'];
    $stmt = $pdo->prepare("UPDATE reservation SET date = :date, heure = :heure, idsalle = :salle_id WHERE idreservation = :id AND iduser = :user_id");
    $stmt->execute(array(':date' => $date, ':heure' => $heure, ':salle_id' => $salle_id, ':id' => $reservation_id, ':user_id' => $user_id));
}

// Traitement de l'affichage des réservations
if ($salle_filter !== null) {
    // Récupération du nom de la salle sélectionnée
    $stmt_salle = $pdo->prepare("SELECT nomsalle FROM salle WHERE idsalle = :salleId");
    $stmt_salle->execute(array(':salleId' => $salle_filter));
    $nom_salle = $stmt_salle->fetchColumn();
    // Vérification de la validité de l'ID de salle
    if (!is_numeric($salle_filter) || $salle_filter <= 0) {
        echo "ID de salle invalide"; ?>
        <br>
        <br>
        <a href="planning.php" class="btn btn-primary">Retour</a>
        <?php
        exit();
    }
    // Récupération des réservations pour la salle choisie sur les 7 prochains jours
    $stmt = $pdo->prepare("SELECT r.idreservation, r.date, r.heure, s.nomsalle AS salle, u.Login as utilisateur
                       FROM reservation r
                       JOIN user u ON r.iduser = u.iduser
                       JOIN salle s ON r.idsalle = s.idsalle
                       WHERE r.idsalle = :salleId AND r.date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND r.iduser = :user_id
                       ORDER BY r.date ASC, r.heure ASC");
    $stmt->bindValue(':salleId', $salle_filter, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    // Affichage du titre de la page avec le nom de la salle
    echo "<h1> Planning de la salle \"" . $nom_salle . " des 7 prochains jours</h1>"; 
} ?>   

    <table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Heure</th>
            <th>Salle</th>
            <th>Utilisateur</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?= $row['date'] ?></td>
            <td><?= $row['heure'] ?></td>
            <td><?= $row['salle'] ?></td>
            <td><?= $row['utilisateur'] ?></td>
            <td>
                <?php if ($_SESSION['iduser'] == $row['iduser']): ?>
                    <a href="modifier_reservation.php?idreservation=<?= $row['idreservation'] ?>">Modifier</a> |
                    <a href="supprimer_reservation.php?idreservation=<?= $row['idreservation'] ?>">Supprimer</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<form action="purge.php" method="post">
  <button type="submit" name="purge" class="btn btn-danger">Purger les données obsolètes</button>
</form>
  <h2> Sélectionnez une date: </h2>

  <form method="POST" action="Salle.php">
    <select name="date">
      <option value="">----- Choisir une date -----</option>
      <?php
        // Date de demain
        $date = new DateTime('tomorrow');

        // Générer les options du menu déroulant pour les 7 prochains jours
        for ($i=0; $i<31; $i++) {
          $selected = ($_SESSION['date'] == $date->format('Y-m-d')) ? 'selected' : '';
          echo "<option value=\"" . $date->format('Y-m-d') . "\" $selected>" . $date->format('d/m/Y') . "</option>";
          $date->add(new DateInterval('P1D')); // Ajouter 1 jour
        }
      ?>
    </select>
    <br>
    <h2> Sélectionnez une heure : </h2>
    <select name="heure">
      <option value="">----- Choisissez une heure -----</option>
      <?php
        for ($i=8; $i<=19; $i++) {
          echo "<option value=".date("H:i:s", strtotime("$i:00:00")).">".date("H:i:s", strtotime("$i:00:00"))."</option>";
        }
      ?>
    </select>
    <br><br>
    <button type="submit">Aller à la page Salle</button>
    <br>
  <br>
  <br>
  <a href="planning.php">Consultez vos réservations</a>
  </form>

</html>
