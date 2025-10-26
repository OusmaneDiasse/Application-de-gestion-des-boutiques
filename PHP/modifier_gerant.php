<!DOCTYPE html>
<html>
     <head>
         <meta charset="uft-8" />
         <link rel="stylesheet" href="../CSS/Styl.css">
         <title>Profil utilisateur</title>
     </head>
     <body>
      <?php
         require "../Config/config.php";
          $ID_UTILISATEUR=$_GET['id'];
          $reponse = $pdo->prepare('SELECT  utilisateur.*, role.NOM_DU_ROLE   FROM utilisateur JOIN role ON utilisateur.ID_ROLE=role.ID_ROLE WHERE ID_UTILISATEUR=:id');
          $reponse->execute(array('id' =>$ID_UTILISATEUR));
          $utilisateur = $reponse->fetch();
        ?>
    <div class="container">
       <h1>Modifier gerant</h1>
       <form action="tableau.php" id="profileEdit" method="post">
          <input type="hidden" name="id" value="<?php echo $utilisateur['ID_UTILISATEUR']; ?>">
          <label>Email / Login </label>
          <input type="email" name="editEmail" readonly value="<?php echo $utilisateur["E_MAIL_UTILISATEUR"] ; ?>"><br><br>
          <label>Role </label>
          <input type="text" name="editRole" readonly value="<?php echo $utilisateur["NOM_DU_ROLE"] ; ?>"> <br><br>
          <label>Nom </label>
          <input type="text" name="editName" value="<?php echo $utilisateur["NOM_UTILISATEUR"] ; ?>"> <br><br>
          <label>Nouveau mot de passe </label>
          <input type="password" name="editPassword" placeholder="Laisser vide si inchange"><br><br>
          <label>Telephone </label>
          <input type="text" name="editPhone" value="<?php echo $utilisateur["TELEPHONE_UTILISATEUR"] ; ?>"> <br><br>
          <label>Adresse </label>
          <input type="text" name="editAddress" value="<?php echo $utilisateur["ADRESS_UTILISATEUR"] ; ?>"> <br><br>

          <div class="but">
             <button type="submit">Enregistrer</button>
             <a href="tableau.php"><button type="button" >Annuler</button></a>
           </div>
        </form>
     </div>
     </body>
</html>