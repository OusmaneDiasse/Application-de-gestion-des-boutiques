<?php
//Bloque le retour en arriere apres la deconnexion
require_once 'session.php';
//Connexion à la base de données
require_once "../Config/config.php";
$profil=$_SESSION['id_utilisateur'];
 $message = "";
     if (isset($_GET['success']) && $_GET['success'] == 1) {
     $message = '<div class="alertsuccess"> Profil modifié avec succes</div>';
    } 
?>
<!DOCTYPE html>
<html>
     <head>
         <meta charset="uft-8"/>
         <link rel="stylesheet" href="../CSS/Styl.css">
         <title>Profil utilisateur</title>
     </head>
     <body>
         <div class="inclu">
          <?php include('menugerant.php');?>
         </div>
     <?php
      $reponse = $pdo->prepare('SELECT utilisateur.*, role.NOM_DU_ROLE   FROM utilisateur JOIN role ON utilisateur.ID_ROLE=role.ID_ROLE WHERE ID_UTILISATEUR= :id');
          $reponse->execute(array('id' =>$profil));
          $utilisateur = $reponse->fetch();
          ?>
         <div class="Profil" id="profileView"> 
            <?php echo $message;?>
              <h1>Profil Utilisateur</h1>
              <p><strong>Login / Email :</strong> <?php  echo $utilisateur["E_MAIL_UTILISATEUR"] ; ?> <br>
              <p><strong>Nom:</strong>  <?php  echo $utilisateur["NOM_UTILISATEUR"] ; ?> <br>
              <p><strong>Role :</strong>  <?php  echo $utilisateur["NOM_DU_ROLE"] ; ?> <br>
              <p><strong>Téléphone :</strong>  <?php  echo $utilisateur["TELEPHONE_UTILISATEUR"] ; ?> <br>
              <p><strong>Adresse :</strong>  <?php  echo $utilisateur["ADRESS_UTILISATEUR"] ; ?> <br>
              <div class="butt">
                 <a href="modifier.php"><button >Modifier</button></a>
                 <a href="accueil.php"><button >Annuler</button></a>
               </div>
     </div>
     
     <script>
        // Sélectionne le message
    const message = document.querySelector('.alertsuccess');
    if (message) {
        // Après 3 secondes (3000 ms), on fait disparaître le message
        setTimeout(() => {
            message.style.opacity = '0'; // fade out
            // Optionnel : le retirer du DOM après la transition
            setTimeout(() => {
                message.remove();
            }, 500); // correspond à la durée de transition CSS
        }, 3000); // temps d’affichage du message
    }
    </script>
     </body>
</html>