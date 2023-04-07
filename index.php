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
  </form>

</html>
