<?php
 $message = "";
     if (isset($_GET['success']) && $_GET['success'] == 1) {
     $message = '<div class="alertsuccess"> Gérant supprimé avec succès</div>';
    }  
if (isset($_GET['error']) && $_GET['error'] == 1) {
    $message = '<div class="alerterror"> Erreur lors de la suppression du gérant</div>';
}
?>
<?php 
<<<<<<< HEAD
    require "config.php";
=======
    require "../Config/config.php";
>>>>>>> 073f4a71d3b2bc6389bc54e55ba35a494bbf26df
    if (isset($_POST['id'])) {
    $TELEPHONE_UTILISATEUR = $_POST['editPhone']; 
    $ADRESS_UTILISATEUR = $_POST['editAddress'];
    $NOM_UTILISATEUR = $_POST['editName'];
    $MOT_DE_PASSE_UTILISATEUR= trim($_POST['editPassword']);
    $hashedPassword = password_hash($MOT_DE_PASSE_UTILISATEUR, PASSWORD_ARGON2I);
    $E_MAIL_UTILISATEUR = $_POST['editEmail'];
    $ID_UTILISATEUR = $_POST['id'];
   if (!empty($MOT_DE_PASSE_UTILISATEUR)){
<<<<<<< HEAD
        $req = $bdd->prepare('UPDATE utilisateur
=======
        $req = $pdo->prepare('UPDATE utilisateur
>>>>>>> 073f4a71d3b2bc6389bc54e55ba35a494bbf26df
       SET TELEPHONE_UTILISATEUR = :nvphone, 
     NOM_UTILISATEUR = :nvname,
     ADRESS_UTILISATEUR   = :nvaddress,
    MOT_DE_PASSE_UTILISATEUR = :nvpassword 
    WHERE ID_UTILISATEUR = :id'); 
        $success= $req->execute(array(
       'nvphone' => $TELEPHONE_UTILISATEUR,
       'nvaddress' => $ADRESS_UTILISATEUR,
       'nvpassword' =>  $hashedPassword ,
       'id'     =>     $ID_UTILISATEUR,
       'nvname' =>     $NOM_UTILISATEUR
    ));
}else{
<<<<<<< HEAD
     $req = $bdd->prepare('UPDATE utilisateur
=======
     $req = $pdo->prepare('UPDATE utilisateur
>>>>>>> 073f4a71d3b2bc6389bc54e55ba35a494bbf26df
     SET TELEPHONE_UTILISATEUR = :nvphone, 
      NOM_UTILISATEUR = :nvname,
     ADRESS_UTILISATEUR   = :nvaddress
     WHERE ID_UTILISATEUR = :id');
     $successe= $req->execute(array(
       'nvphone' => $TELEPHONE_UTILISATEUR,
       'nvaddress' => $ADRESS_UTILISATEUR,
       'nvname' =>     $NOM_UTILISATEUR,
        'id'        =>  $ID_UTILISATEUR
    ));
}
 if ($successe) {
        header("Location: tableau.php?successe=1");
    } else {
        header("Location: tableau.php?errore=1");
    }
    exit();
  }
?>
<?php
$chat = "";
if (isset($_GET['successe']) && $_GET['successe'] == 1) {
    $chat = '<div class="alertsuccess"> Gérant modifier avec succès</div>';
}
if (isset($_GET['errore']) && $_GET['errore'] == 1) {
    $chat = '<div class="alerterror">❌ Erreur lors de la modification du gérant</div>';
}
?>
<!DOCTYPE html>
<html>
     <head>
         <meta charset="uft-8" />
          <link rel="stylesheet" href="../CSS/Gerant.css">
         <title>Les gerants de la boutique</title>
     </head>
   <body>
     <?php 
<<<<<<< HEAD
       require "config.php";
        $reponse = $bdd->query('SELECT  utilisateur.*, role.NOM_DU_ROLE   FROM utilisateur JOIN role ON utilisateur.ID_ROLE=ROLE_ID
=======
        $reponse = $pdo->query('SELECT  utilisateur.*, role.NOM_DU_ROLE   FROM utilisateur JOIN role ON utilisateur.ID_ROLE=ROLE_ID
>>>>>>> 073f4a71d3b2bc6389bc54e55ba35a494bbf26df
        WHERE NOM_DU_ROLE="Gerant" ');
        ?>
    <div class="All">
       <?php echo $message; ?>
       <?php echo $chat; ?>
     <div class="introduction">
         <h2> Liste des gerants de la boutique </h2>
         <a href="ajout_gerant.php" class="btn">Ajouter un gerant</a>
     </div>
         <table>
             <tr>
               <th>Nom</th>
               <th>Email</th>
               <th>Role</th>
               <th>Téléphone</th>
               <th>Adresse</th>
               <th>Action</th>
             </tr>
             <?php while($utilisateur= $reponse->fetch()) { ?>
             <tr>
                <td><?php echo $utilisateur["NOM_UTILISATEUR"] ; ?></td>
                <td><?php echo $utilisateur["E_MAIL_UTILISATEUR"] ; ?></td>
                <td><?php echo $utilisateur["NOM_DU_ROLE"] ; ?></td>
                <td><?php echo $utilisateur["TELEPHONE_UTILISATEUR"] ; ?></td>
                <td><?php echo $utilisateur["ADRESS_UTILISATEUR"] ; ?></td>
                <td>
                <a href="modifier_gerant.php?id=<?= $utilisateur['ID_UTILISATEUR'] ?>"><button>Modifier</button></a> 
                <a href="supprimer_gerant.php?id=<?php echo $utilisateur['ID_UTILISATEUR'] ?>"><button>Supprimer</button></a>
                </td>
              </tr>
             <?php }  $reponse->closeCursor(); ?>
         </table>
      </div>
   </body>
</html>