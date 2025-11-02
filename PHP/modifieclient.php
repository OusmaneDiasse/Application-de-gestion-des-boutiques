<?php
require_once 'session.php';
require_once "../Config/config.php";
$profil=$_SESSION['ID_CLIENT']
?>
<!DOCTYPE html>
<html>
     <head>
         <meta charset="uft-8" />
         <link rel="stylesheet" href="../CSS/Styl.css">
         <title>Profil CLIENT</title>
     </head>
     <body>
      <div class="incluU">
      <?php include('menu_client.php'); ?>
    </div>
      <?php
          $reponse = $pdo->prepare('SELECT * FROM client WHERE ID_CLIENT=:id');
          $reponse->execute(array('id' =>$profil));
          $client= $reponse->fetch();
        ?>
    <div class="containerr">
       <h1>Modifier Profil</h1>
       <form action="updateclient.php" id="profileEdit" method="post">
          <input type="hidden" name="id" value="<?php echo $client['ID_CLIENT']; ?>">
          <label>Email / Login </label>
          <input type="email" name="editEmail" readonly value="<?php echo $client["E_MAIL_CLIENT"] ; ?>"><br><br>
          <input type="text" name="editName" value="<?php echo $client["NOM_CLIENT"] ; ?>"> <br><br>
          <label>Nouveau mot de passe </label>
          <input type="password" name="editPassword" placeholder="Laisser vide si inchange" minlength="8" require><br><br>
          <label>Telephone </label>
          <input type="text" name="editPhone" value="<?php echo $client["TELEPHONE"] ; ?>"> <br><br>
          <div class="but">
             <button type="submit">Enregistrer</button>
             <a href="Profil.php"><button type="button" >Annuler</button></a>
           </div>
        </form>
     </div>
     </body>
</html>