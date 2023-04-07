<html>
 <head>
 <meta charset="utf-8">
 <!-- importer le fichier de style -->
 <link rel="stylesheet" href="Style.css" media="screen" type="text/css" />
 </head>
 <body>
    <img  class="image" src="./Images/M2L.png">
 <div id="container">
 <!-- zone de connexion -->
 
 <form action="connexion.php" method="POST">
 <h1>Connexion</h1>
 
 <label><b>Login :</b></label>
 <input type="text" placeholder="Entrer votre nom d'utilisateur" name="login" required>

 <label><b>Mot de passe :</b></label>
 <input type="password" placeholder="Entrer votre mot de passe" name="password" required>

 <input type="submit" id='submit' value='LOGIN' >

 <?php

 error_reporting(E_ALL);
 ini_set("display_errors", 1);
 if(isset($_GET['erreur'])){
    $err = $_GET['erreur'];
 if($err==1 || $err==2)
 echo ("Utilisateur ou mot de passe incorrect");
 }
 ?>
 </form>
 </div>
 </body>
</html>