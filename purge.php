<?php

// Supprimer les réservations obsolètes (plus de 2 jours)
$stmt = $pdo->prepare("DELETE FROM reservation WHERE date < DATE_SUB(CURDATE(), INTERVAL 2 DAY)");
$stmt->execute();

echo "Données obsolètes supprimées.";
?>
<br>
<br> 
<a href="planning.php" class="btn btn-primary">Retour</a>
