<?php 
 if(!isset($_SESSION['type'])){
  header("location: ../index.html");
exit();
 }
 require_once "../Config/config.php";
 $type=$_SESSION['type'];
 ?>
<!-- <!DOCTYPE html>
<html>
  <head>
      <meta charset="UTF-8"/> -->
      <title>Menu Client</title>
      <link rel="stylesheet" href="../CSS/menuclient.css" >
  <!-- </head>
   <body> -->
    <div class="all">
    <?php if($type=='client') :?>
     <div class="menuclient">
         <div class="titre">
             <h4>Menu Client</h4>
            </div>
            <nav class="nav">
              <a href="accueilclient.php">
                  <span class="icon">🏠</span>
                  <span>Accueil</span>
                </a>
               <a href="Profilclient.php" >
                  <span class="icon">👤</span>
                  <span>Profil</span>
                </a>
               <a href="creance.php" >
                   <span class="icon">💸</span>
                   <span>Creance</span>
                </a>
                <a href="achat.php">
                  <span class="icon">🛒</span>
                  <span>Achats</span>
                </a>
                <a href="deconnexion.php" class="bom" >
                   <span class="icon">🚪</span>
                   <span>Deconnexion</span>
                </a>
            </nav>
        </div>
        <?php endif; ?>
    </div>
    <!-- </body>
</html> -->