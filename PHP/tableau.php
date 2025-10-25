<?php
require "../Config/config.php";
try {
    $recherche = $_GET['search'] ?? '';


    if (isset($_GET['reset'])) {
    $recherche = '';
    }
       $sql ="SELECT  utilisateur.*, role.NOM_DU_ROLE   FROM utilisateur JOIN role ON utilisateur.ID_ROLE=ROLE_ID
        WHERE ROLE_ID=2 AND NOM_UTILISATEUR like :recherche";
         $stmt = $pdo->prepare($sql);
         $params = [':recherche' => "%$recherche%"];
          $stmt->execute($params);
           $gerant = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . htmlspecialchars($e->getMessage()));
}
?>
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
    require "../Config/config.php";
    if (isset($_POST['id'])) {
    $TELEPHONE_UTILISATEUR = $_POST['editPhone']; 
    $ADRESS_UTILISATEUR = $_POST['editAddress'];
    $NOM_UTILISATEUR = $_POST['editName'];
    $MOT_DE_PASSE_UTILISATEUR= trim($_POST['editPassword']);
    $hashedPassword = password_hash($MOT_DE_PASSE_UTILISATEUR, PASSWORD_ARGON2I);
    $E_MAIL_UTILISATEUR = $_POST['editEmail'];
    $ID_UTILISATEUR = $_POST['id'];
   if (!empty($MOT_DE_PASSE_UTILISATEUR)){
        $req = $pdo->prepare('UPDATE utilisateur
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
     $req = $pdo->prepare('UPDATE utilisateur
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
$ghat = "";
if (isset($_GET['successe']) && $_GET['successe'] == 1) {
    $ghat = '<div class="alertsuccess"> Gérant modifier avec succès</div>';
}
if (isset($_GET['errore']) && $_GET['errore'] == 1) {
    $ghat = '<div class="alerterror">❌ Erreur lors de la modification du gérant</div>';
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
        $reponse = $pdo->query('SELECT  utilisateur.*, role.NOM_DU_ROLE   FROM utilisateur JOIN role ON utilisateur.ID_ROLE=ROLE_ID
        WHERE NOM_DU_ROLE="Gerant" ');
        ?>
    <div class="All text" >
       <?php echo $message; ?>
       <?php echo $ghat; ?>
     <div class="introduction">
         <h2> Liste des Gerants De La Boutique </h2>
         <a href="ajout_gerant.php" class="btn">Ajouter un gerant</a>

         <div class="search-container">
              <form method="GET" action="">
                    <input type="text" name="search" placeholder="Rechercher un gerant..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <button type="submit">Rechercher</button>
                    <button type="submit" name="reset" value="">Réinitialiser</button>
              </form>
           </div>
               
        </div>
        <?php if($gerant): ?>
         <table>
             <tr>
               <th>Nom</th>
               <th>Email</th>
               <th>Role</th>
               <th>Téléphone</th>
               <th>Adresse</th>
               <th>Action</th>
             </tr>
                  <?php foreach ($gerant as $g): ?>
             <tr>
                <td><?php echo htmlspecialchars ($g['NOM_UTILISATEUR']) ?></td>
                <td><?php echo htmlspecialchars ($g['E_MAIL_UTILISATEUR']) ?></td>
                <td><?php echo htmlspecialchars ($g['NOM_DU_ROLE']) ?></td>
                <td><?php echo htmlspecialchars ($g['TELEPHONE_UTILISATEUR']) ?>
                <td><?php echo htmlspecialchars ($g['ADRESS_UTILISATEUR']) ?></td>
                <td>
                <a href="modifier_gerant.php?id=<?=htmlspecialchars ($g['ID_UTILISATEUR']) ?>"><button>Modifier</button></a> 
                <a href="supprimer_gerant.php?id=<?php echo htmlspecialchars ($g['ID_UTILISATEUR']) ?>"><button>Supprimer</button></a>
                </td>
                
              </tr>        
             <?php endforeach ?>
         </table>
            <?php else: ?>
            <p>Aucun gerant trouvé</p>
            <?php endif; ?>
  </div>
   </body>
</html>