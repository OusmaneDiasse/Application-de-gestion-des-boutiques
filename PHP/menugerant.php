<?php 
 if(!isset($_SESSION['role'])){
header("location: ../index.html");
exit();
 }
 require_once "../Config/config.php";
 $role=$_SESSION['role'];
 ?>
<!-- <!DOCTYPE html>
<html>
  <head>
      <meta charset="UTF-8"/>
      <title>Menu Gerant</title> -->
      <link rel="stylesheet" href="../CSS/employe.css" >
  <!-- </head>
   <body> -->
    <?php if($role=='2') :?>
     <div class="menuclient">
         <div class="titre">
             <h4>Menu Gerant</h4>
            </div>
            <nav class="nav">
                <a href="accueil.php">
                  <span class="icon">🏠</span>
                  <span>Accueil</span>
                </a>
                <a href="Profil.php">
                  <span class="icon">👤</span>
                  <span>Profil</span>
                </a>
                <a href="tableau.php">
                  <span class="icon">👨‍🔧</span>
                  <span>Employe</span>
                </a>
                <a href="listeclient.php">
                  <span class="icon">🧍‍♂️</span>
                  <span>Client</span>
                </a>
                 <a href="facturation_form.php">
                  <span class="icon">💰</span>
                  <span>Vente</span>
                </a>
                <a href="afficher_produit.php">
                  <span class="icon">📦</span>
                  <span>Stock</span>
                </a>
               <a href="liste_creance.php">
                   <span class="icon">💸</span>
                   <span>Creance</span>
                </a>
                <a href="deconnexion.php" class=buttt>
                  <span class="icon">🚪</span>
                  <span>Deconnexion</span>
                </a>
            </nav>
        </div>
        <?php elseif($role==1) :?>
     <div class="menuclient">
         <div class="titre">
             <h4>Menu Employé</h4>
            </div>
            <nav class="nav">
                <a href="accueil.php">
                  <span class="icon">🏠</span>
                  <span>Accueil</span>
                </a>
                <a href="Profil.php">
                  <span class="icon">👤</span>
                  <span>Profil</span>
                </a>
                <a href="listeclient.php">
                  <span class="icon">🧍‍♂️</span>
                  <span>Client</span>
                </a>
                 <a href="facturation_form.php">
                  <span class="icon">💰</span>
                  <span>Vente</span>
                </a>
                <a href="afficher_produit.php">
                  <span class="icon">📦</span>
                  <span>Stock</span>
                </a>
               <a href="liste_creance.php">
                   <span class="icon">💸</span>
                   <span>Creance</span>
                </a>
                <a href="deconnexion.php" class=butto>
                  <span class="icon">🚪</span>
                  <span>Deconnexion</span>
                </a>
            </nav>
        </div>
        <?php endif; ?>

    <!-- </body>
</html> -->