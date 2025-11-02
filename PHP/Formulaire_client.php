<?php
require_once 'session.php';
require "../Config/config.php";
 $message = "";
     if (isset($_GET['successe']) && $_GET['successe'] == 1) {
     $message = '<div class="alertsuccess">Ce client existe déja.</div>';
    }  
    ?>
<!DOCTYPE html>
<html>
     <head>
         <meta charset="uft-8" />
         <link rel="stylesheet" href="../CSS/styl.css">
         <title>Ajout d'un client</title>
     </head>
    <body>
        <div class="inclu">
      <?php include('menugerant.php'); ?>
    </div>
            <!-- Formulaire -->
     <div class="All">
        <div class="container" id="profileView">
            <h1>Ajouter un client</h1>
            <?php echo $message;?>
            <form action="../PHP/Ajout_client.php" method="POST">
                <label>Email :</label>
                <input type="email" name="email" required placeholder="Entrer le mail du client"><br><br>
                <label>Nom :</label>
                <input type="text" name="nom" required placeholder="Entrer le nom du client"><br><br>
                <label>Telephone :</label>
                <input type="text" name="telephone" required placeholder="Entrez le numero de telephone "><br><br>
                <label>Mot de passe:</label>
                <input type="password" name="prive" placeholder="Creez un mot de passe" minlength="8" required><br><br>
                <div class="but">
                 <button type="submit">Ajouter</button>
                 <button type="reset" >Annuler</button>
                </div>
           </form>
        </div>
    </div>
   </body>
</html>