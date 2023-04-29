<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once('db.php');
session_start();

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['iduser'])) {
    header('Location: login.php');
    exit();
}

// Récupération des salles
$stmt = $pdo->query("SELECT idsalle, nomsalle FROM salle");
$salles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Purge des données obsolètes (plus de 2 jours)
$today = date('Y-m-d');
$two_days_ago = date('Y-m-d', strtotime('-2 days'));
$stmt = $pdo->prepare("DELETE FROM reservation WHERE date < :two_days_ago");
$stmt->execute(array(':two_days_ago' => $two_days_ago));

// Récupération des réservations de l'utilisateur connecté pour toutes les salles par défaut
$user_id = $_SESSION['iduser'];
$salle_id = isset($_GET['salle']) ? (int)$_GET['salle'] : 0;
$stmt = $pdo->prepare("SELECT r.idreservation , r.date, r.heure, s.nomsalle AS salle, u.login AS utilisateur
                       FROM reservation r
                       JOIN salle s ON r.idsalle = s.idsalle
                       JOIN user u ON r.iduser = u.iduser
                       WHERE r.iduser = :user_id
                       AND (:salle_id = 0 OR r.idsalle = :salle_id)
                       ORDER BY r.date ASC");
$stmt->execute(array(':user_id' => $user_id, ':salle_id' => $salle_id));
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="Crud.css">
    <title>Planning des réservations</title>
</head>
<body>
    <h1>Planning des réservations</h1>
    
    <form method="get">
        <label for="salle">Filtrer par salle :</label>
        <select id="salle" name="salle">
            <option value="0">Toutes les salles</option>
            <?php foreach ($salles as $salle): ?>
                <option value="<?= $salle['idsalle'] ?>" <?= $salle['idsalle'] == $salle_id ? 'selected' : '' ?>>
                    <?= $salle['nomsalle'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrer</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Heure</th>
                <th>Salle</th>
                <th>Utilisateur</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?= $reservation['date'] ?></td>
                    <td><?= $reservation['heure'] ?></td>
                    <td><?= $reservation['salle'] ?></td>
                    <td><?= $reservation['utilisateur'] ?></td>
                    <td>
                        <?php if ($_SESSION['iduser'] == $user_id): ?>
                            <a href="modifier_reservation.php?idreservation=<?= $reservation['idreservation'] ?>">Modifier</a> |
                            <a href="supprimer_reservation.php?idreservation=<?= $reservation['idreservation'] ?>">Supprimer</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <br>
    <form method="post">
        <button type="submit" name="purge">Purger</button>
    </form>
</body>
</html>
