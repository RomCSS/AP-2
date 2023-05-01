<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once('db.php');
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Date et heure de réservation</title>
</head>
<body>
	<h1>Date et heure de réservation</h1>
	<?php
	// Vérifier si le formulaire a été soumis
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Récupérer les valeurs du formulaire
		$date = $_POST['date'];
		$heure = $_POST['heure'];

		// Vérifier que la date choisie n'est pas supérieure à 1 mois après la date d'aujourd'hui
		$date_aujourdhui = new DateTime();
		$date_aujourdhui->modify('+1 month');
		$date_choisie = new DateTime($date);
		if ($date_choisie > $date_aujourdhui) {
			echo '<p>La date choisie doit être inférieure ou égale à 1 mois après la date d\'aujourd\'hui.</p>';
		} else {
			// Vérifier que l'heure choisie est dans la plage de réservation de 8h à 20h
			if ($heure < '08:00' || $heure > '20:00') {
				echo '<p>L\'heure choisie doit être entre 8h et 20h.</p>';
			} else {
				// Stocker les valeurs dans la session
				session_start();
				$_SESSION['date'] = $date;
				$_SESSION['heure'] = $heure;

				// Rediriger vers la page de choix de la salle
				header('Location: Salle.php');
				exit();
			}
		}
	}
	?>
	<form method="post">
		<label for="date">Date de réservation :</label>
		<input type="date" id="date" name="date" required><br><br>

		<label for="heure">Heure de réservation :</label>
		<select id="heure" name="heure" required>
			<option value="" disabled selected>Choisissez une heure</option>
			<?php
			// Générer les options de l'heure de réservation
			$heure_debut = new DateTime('08:00');
			$heure_fin = new DateTime('20:00');
			$interval = new DateInterval('PT1H');
			$heures = new DatePeriod($heure_debut, $interval, $heure_fin);
			foreach ($heures as $heure) {
				echo '<option value="' . $heure->format('H:i') . '">' . $heure->format('H:i') . '</option>';
			}
			?>
		</select><br><br>


		<input type="submit" value="Choisir la salle">
	</form>
</body>
</html>
