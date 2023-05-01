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

// Vérification de la soumission du formulaire
if (isset($_POST['submit'])) {
    $idreservation = (int)$_POST['idreservation'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $idsalle = (int)$_POST['idsalle'];

// Vérification de la soumission du formulaire
if (isset($_POST['submit'])) {
    $idreservation = (int)$_POST['idreservation'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $idsalle = (int)$_POST['idsalle'];

    // Vérification de la plage horaire
    if ($heure < '08:00:00' || $heure > '20:00:00') {
        $message = "L'heure de modification doit être entre 8h et 20h.";
    } else {
        // Mise à jour de la réservation
        $stmt = $pdo->prepare("UPDATE reservation SET date = :date, heure = :heure, idsalle = :idsalle WHERE idreservation = :idreservation");
        $stmt->execute(array(':date' => $date, ':heure' => $heure, ':idsalle' => $idsalle, ':idreservation' => $idreservation));

        // Redirection vers la page de planning
        header('Location: planning.php');
        exit();
    }
}  
}
// Récupération des salles depuis la base de données
$stmt = $pdo->query("SELECT idsalle, nomsalle FROM salle");
$salles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération de la réservation à modifier
$idreservation = (int)$_GET['idreservation'];
$stmt = $pdo->prepare("SELECT r.idreservation , r.date, r.heure, s.nomsalle AS salle, u.login AS utilisateur
                       FROM reservation r
                       JOIN salle s ON r.idsalle = s.idsalle
                       JOIN user u ON r.iduser = u.iduser
                       WHERE r.idreservation = :idreservation
                       AND r.iduser = :user_id");
$stmt->execute(array(':idreservation' => $idreservation, ':user_id' => $_SESSION['iduser']));
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérification que la réservation appartient bien à l'utilisateur connecté
if (!$reservation) {
    header('Location: planning.php');
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $idsalle = (int)$_POST['idsalle'];

    $stmt = $pdo->prepare("UPDATE reservation SET date = :date, heure = :heure, idsalle = :idsalle WHERE idreservation = :idreservation");
    $stmt->execute(array(':date' => $date, ':heure' => $heure, ':idsalle' => $idsalle, ':idreservation' => $idreservation));

    header('Location: planning.php');
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Modifier une réservation</title>
</head>
<body>
    <h1>Modifier une réservation</h1>
    <form method="post">
    <label for="date">Date :</label>
    <input type="date" id="date" name="date" value="<?= $reservation['date'] ?>" required><br><br>
    <label for="heure">Heure :</label>
    <select id="heure" name="heure" required>
        <?php for ($i=8; $i<=20; $i++) {
            $hour = sprintf("%02d:00:00", $i); ?>
            <option value="<?= $hour ?>" <?= $hour == $reservation['heure'] ? 'selected' : '' ?>>
                <?= $hour ?>
            </option>
        <?php } ?>
    </select><br><br>
    <label for="salle">Salle :</label>
<select id="salle" name="idsalle" required>
    <?php foreach ($salles as $salle): ?>
        <?php if (array_key_exists('idsalle', $salle)): ?>
            <option value="<?= $salle['idsalle'] ?>" <?= $salle['idsalle'] == $reservation['idsalle'] ? 'selected' : '' ?>>
                <?= $salle['nomsalle'] ?>
            </option>
        <?php endif; ?>
    <?php endforeach; ?>
</select><br><br>
    <input type="hidden" name="idreservation" value="<?= $reservation['idreservation'] ?>">
    <button type="submit" name="submit">Modifier</button>
    </form>
    <?php if (isset($message)) echo '<p>' . $message . '</p>'; ?>
</body>
</html>
