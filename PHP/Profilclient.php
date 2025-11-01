<?php
require_once "../Config/config.php";
session_start();
$profil=$_SESSION['ID_CLIENT']
?>
<!DOCTYPE html>
<html>
     <head>
         <meta charset="uft-8"/>
         <link rel="stylesheet" href="../CSS/Styl.css">
         <title>Profil client</title>
     </head>
     <body>
          <div class="incluU">
      <?php include('menu_client.php'); ?>
    </div>
     <?php
      $reponse = $pdo->prepare('SELECT * FROM client WHERE ID_CLIENT= :id');
          $reponse->execute(array('id' =>$profil));
          $client= $reponse->fetch();
          ?>
         <div class="Profill" id="profileView"> 
              <h1>Profil client</h1>
              <p><strong>Login / Email :</strong> <?php  echo $client["E_MAIL_CLIENT"] ; ?> <br>
              <p><strong>Nom:</strong>  <?php  echo $client["NOM_CLIENT"] ; ?> <br>
              <p><strong>Téléphone :</strong>  <?php  echo $client["TELEPHONE"] ; ?> <br>
              <div class="butt">
                 <a href="modifieclient.php"><button >Modifier</button></a>
                 <button>Annuler</button>
               </div>
     </div>
     </body>
</html>