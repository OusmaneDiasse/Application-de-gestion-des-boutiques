<?php
//Bloque le retour en arriere apres la deconnexion
require_once 'session.php';
//Connexion à la base de données
require_once "../Config/config.php";

try {
    // Supprimer si demandé
    if ($_GET['action'] ?? '' === 'supprimer' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("DELETE FROM produit WHERE ID_PRODUIT = :id");
        $stmt->execute([':id' => (int)$_GET['id']]);
        header("Location: afficher_produit.php?message=Produit supprimé avec succès");
        exit;
    }

 // recherche
    $produits = $pdo->query("SELECT * FROM produit")->fetchAll(PDO::FETCH_ASSOC);
    
    $recherche = $_GET['search'] ?? '';
    if (isset($_GET['reset'])) {
        $recherche = '';
    }
    
    if (!empty($recherche)) {
        $stmt = $pdo->prepare("SELECT * FROM produit WHERE NOM_PRODUIT LIKE :recherche");
        $stmt->execute([':recherche' => "%$recherche%"]);
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $produits = $pdo->query("SELECT * FROM produit")->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    die("Erreur : " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../CSS/style_afficher_produit.css">
  <title>liste Produits</title>
</head>
<body>
      <div class="inclu">
        <?php include('menugerant.php');?>
      </div>
 <div class="all">
    <div class="form-container">
  <h1>Liste Des Produits De La Boutique</h1>
  <div class="button-group"><a href="ajout_produit.php"><button>Ajouter un produit</button></a></div>
  <!-- Barre de recherche -->
    <div class="search-container">
      <form method="GET" action="">
          <input type="text" name="search" placeholder="Rechercher un produit..." value="<?= htmlspecialchars($recherche) ?>">
          <button type="submit" class="button">Rechercher</button>
          <button type="submit" name="reset" value="" class="button">Reinitialiser</button>
      </form>
    </div>
  
  <?php if (!empty($_GET['message'])): ?>
    <p class="<?= strpos($_GET['message'], 'Erreur') ? 'error' : 'success' ?>">
      <?= htmlspecialchars($_GET['message']) ?>
    </p>
  <?php endif; ?>
   </div>
  <?php if ($produits): ?>
    <table>
      <tr>
        <th>Nom</th><th>Prix</th><th>Stock</th>
        <th>Date Fabrication</th><th>Date Péremption</th><th>Action</th>
      </tr>
      <?php foreach ($produits as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['NOM_PRODUIT']) ?></td>
          <td><?= htmlspecialchars($p['PRIX']) ?> FCFA</td>
          <td><?= htmlspecialchars($p['STOCK']) ?></td>
          <td><?= htmlspecialchars($p['DATE_DE_FABRICATION']) ?></td>
          <td><?= htmlspecialchars($p['DATE_DE_PEREMPTION']) ?></td>
          <td>
            <a href="ajout_produit.php?id=<?= $p['ID_PRODUIT'] ?>">Modifier</a> 
            <a href="?action=supprimer&id=<?= $p['ID_PRODUIT'] ?>"
               onclick="return confirm('Voulez-vous supprimer ce produit ?');">Supprimer</a> <br><br>
               <a href="stock.php?id_produit=<?= urlencode($p['ID_PRODUIT']) ?>" class="btn-appro">Approvisionner</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>Aucun produit trouvé.</p>
  <?php endif; ?>
</div>
<script>
    // Sélectionne le message
    const message = document.querySelector('.message');
    const sms = document.querySelector('.error');

    if (message) {
        // Après 3 secondes (3000 ms), on fait disparaître le message
        setTimeout(() => {
            message.style.opacity = '0'; // fade out
            // Optionnel : le retirer du DOM après la transition
            setTimeout(() => {
                message.remove();
            }, 500); // correspond à la durée de transition CSS
        }, 3000); // temps d’affichage du message
    }else{
        // Après 3 secondes (3000 ms), on fait disparaître le message
        setTimeout(() => {
            sms.style.opacity = '0'; // fade out
            // Optionnel : le retirer du DOM après la transition
            setTimeout(() => {
                sms.remove();
            }, 500); // correspond à la durée de transition CSS
        }, 3000); // temps d’affichage du message
    }
</script>
   </body>
</html>
