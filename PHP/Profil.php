<!DOCTYPE html>
<html>
     <head>
         <meta charset="uft-8"/>
         <link rel="stylesheet" href="../CSS/Styl.css">
         <title>Profil utilisateur</title>
     </head>
     <body>
     <?php
           require_once "../Config/config.php";
      $reponse = $pdo->query('SELECT utilisateur.*, role.NOM_DU_ROLE   FROM utilisateur JOIN role ON utilisateur.ID_ROLE=role.ID_ROLE WHERE ID_UTILISATEUR=1');
          $utilisateur = $reponse->fetch();
          ?>
         <div class="Profil" id="profileView"> 
              <h1>Profil Utilisateur</h1>
              <p><strong>Login / Email :</strong> <?php  echo $utilisateur["E_MAIL_UTILISATEUR"] ; ?> <br>
              <p><strong>Nom:</strong>  <?php  echo $utilisateur["NOM_UTILISATEUR"] ; ?> <br>
              <p><strong>Role :</strong>  <?php  echo $utilisateur["NOM_DU_ROLE"] ; ?> <br>
              <p><strong>Téléphone :</strong>  <?php  echo $utilisateur["TELEPHONE_UTILISATEUR"] ; ?> <br>
              <p><strong>Adresse :</strong>  <?php  echo $utilisateur["ADRESS_UTILISATEUR"] ; ?> <br>
              <div class="butt">
                 <a href="modifier.php?id=1"><button >Modifier</button></a>
                 <button>Annuler</button>
               </div>
     </div>
     </body>
</html>