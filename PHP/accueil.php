<?php
require_once 'session.php';
//Connexion à la base de données
 require_once '../Config/config.php';
// calcjuler le chiffre d'affaire
 $req = $pdo->query('SELECT SUM(produit.PRIX*vendre.QUANTITE_VENDUE) AS chiffre_affaire FROM vendre JOIN produit ON vendre.ID_PRODUIT = produit.ID_PRODUIT WHERE DATE(DATE_VENTE)=CURDATE()');
 $chiffre_affaire = $req->fetch(PDO::FETCH_ASSOC)['chiffre_affaire']; //resultat sous forme de tableau associatif
  // calculer le nombre de vente dans la journée
 $req = $pdo->query('SELECT COUNT(*) AS nombre_ventes FROM vendre WHERE DATE(DATE_VENTE) = CURDATE()');
 $nombre_ventes = $req->fetch(PDO::FETCH_ASSOC)['nombre_ventes']; //resultat sous forme de tableau associatif
 // calculer le nombre total des créances en cours
 $req = $pdo->query('SELECT COUNT(*) AS total_creances FROM creance WHERE ID_STATUT = 1');
 $total_creances = $req->fetch(PDO::FETCH_ASSOC)['total_creances']; //resultat sous forme de tableau associatif
 // calculer le nombre de produit en rupture de stock
 $req = $pdo->query('SELECT COUNT(*) AS produits_rupture FROM produit WHERE STOCK  <3');
 $produits_rupture = $req->fetch(PDO::FETCH_ASSOC)['produits_rupture'];  //resultat sous forme de tableau associatif
 // Le nombre de produits apprivisionné aujourd'hui
 $req = $pdo->query('SELECT COUNT(*) AS produits_approvisionnes FROM stock WHERE DATE(DATE_ACHAT) = CURDATE()');
 $produits_approvisionnes = $req->fetch(PDO::FETCH_ASSOC)['produits_approvisionnes'];  //resultat sous forme de tableau associatif    
 ?>
 <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>page d'acceuil de la boutique</title>
    <link rel="stylesheet" href="../CSS/accueil.css">
</head>
<body>
     <div class="inclu">
      <?php include('menugerant.php');?>
    </div>
    <div class="gestion">
    <div class="bienvenue"> 
    <h2>Bienvenue dans la page d'accueil pour le gérant/propriétaire de la boutique</h2>
    <p>Vue d'ensemble des statistiques de votre commerce.</p>
    </div>
    <div class="informations">
        <div class="block3">
    <p class="affaires"> 
        <span class="valeur"><?php echo $chiffre_affaire; ?>  FCFA </span><br>
   <br> Chiffre d'affaire <br> 
    Aujourd'hui
    </p>
    <p class="ventes "><span class=" nombre">
     <?php echo $nombre_ventes; ?></span> <br>
        <br>  Ventes <br>
         Dans la journée
    </p>
    <p class="creances" > 
        <span class=" total"><?php echo $total_creances; ?> </span><br>
          <br> Créances  <br>
            En cours
    </p>
    </div>
    <div class="block2">
    <p class="produits"> <span class="rupture">
<?php echo $produits_rupture; ?> </span><br>
 <br>Produits en rupture <br>
 De stock
    </p> 
<p class="approvisionnes"> <span class="nombre_produit">
<?php echo $produits_approvisionnes; ?> </span><br>
 <br> Approvisionnement <br>
    d'aujourd'hui
    </p>
</div>
</div>
<div class="solde">
    <table class="table">
    <caption>Créances échues et non soldées</caption>
    <tr>
        <th>Nom du client</th>
        <th>Montant dû (FCFA)</th>
        <th>Date d'échéance</th>
        <th>Statut</th>
        </tr>
        <?php
        // Requête pour récupérer les créances échues et non soldées    
        $req = $pdo->query('SELECT client.NOM_CLIENT, creance.MONTANT_DU, creance.DATE_ECHEANCE, creance.ID_STATUT FROM creance JOIN facture ON creance.ID_FACTURE = facture.ID_FACTURE
JOIN client ON facture.ID_CLIENT = client.ID_CLIENT WHERE creance.ID_STATUT = 1 AND creance.DATE_ECHEANCE < CURDATE()');
        $creances = $req->fetchAll(PDO::FETCH_ASSOC); // Récupérer tous les résultats
        if (!empty($creances)) {
            foreach ($creances as $index => $creance) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($creance['NOM_CLIENT']) . "</td>";
                echo "<td>" . htmlspecialchars($creance['MONTANT_DU']) . "</td>";
                echo "<td>" . htmlspecialchars($creance['DATE_ECHEANCE']) . "</td>";
                echo "<td>" . ($creance['ID_STATUT'] == 1 ? 'En cours' : 'Soldée') . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Aucune créance échue et non soldée.</td></tr>";
        }
        ?>
</table>
</div>
<div class="produit">
    <div>
    <table class="tableau">
        <caption>Produits en rupture de stock ( seuil < 3 ) 
    </caption>
        <tr>
            <th>Nom du produit</th>
            <th>Stock actuel</th>
        </tr>
        <?php
        // Requête pour récupérer les produits en rupture de stock (seuil < 3)
        $req = $pdo->query('SELECT NOM_PRODUIT ,STOCK FROM produit WHERE STOCK < 3');
        $produits = $req->fetchAll(PDO::FETCH_ASSOC); // Récupérer tous les résultats
        if (!empty($produits)) {
            foreach ($produits as $index => $produit) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($produit['NOM_PRODUIT']) . "</td>";
                echo "<td>" . htmlspecialchars($produit['STOCK']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>Aucun produit en rupture de stock.</td></tr>";
        }
        ?>
    </table>
</div>
<table class="block">
    <caption>Les 5 Produits les plus vendus</caption>
    <tr>
        <th>Nom du produit</th>
        <th>Quantité vendue</th>
        </tr>
        <?php
        // Requête pour récupérer les produits les plus vendus
        $req = $pdo->query('SELECT produit.NOM_PRODUIT, SUM(vendre.QUANTITE_VENDUE) AS total_vendu FROM vendre JOIN produit ON vendre.ID_PRODUIT = produit.ID_PRODUIT GROUP BY vendre.ID_PRODUIT ORDER BY total_vendu DESC LIMIT 5');
        $produits_vendus = $req->fetchAll(PDO::FETCH_ASSOC); // Récupérer tous les résultats
        if (!empty($produits_vendus)) {
            foreach ($produits_vendus as $index => $produit) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($produit['NOM_PRODUIT']) . "</td>";
                echo "<td>" . htmlspecialchars($produit['total_vendu']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>Aucun produit vendu.</td></tr>";
        }
        ?>  
</table>
</div>
<div class="deux">
    <table class="recent">
        <caption>Les ventes récentes</caption>
    
    <tr>
        <th>L'heure</th>
        <th>Le montant</th>
        <th>Nbr d'article</th>
    </tr>
    <?php
    // Requête pour récupérer les 5 ventes plus récentes    
    $req = $pdo->query('SELECT HEURE_VENTE AS heure_vente, SUM(produit.PRIX * vendre.QUANTITE_VENDUE) AS MONTANT_TOTAL, SUM(vendre.QUANTITE_VENDUE) AS NOMBRE_ARTICLES FROM vendre JOIN produit ON vendre.ID_PRODUIT = produit.ID_PRODUIT JOIN facture ON vendre.ID_FACTURE = facture.ID_FACTURE
GROUP BY facture.ID_FACTURE ORDER BY vendre.QUANTITE_VENDUE DESC LIMIT 5');
    $ventes_recentes = $req->fetchAll(PDO::FETCH_ASSOC); // Récupérer tous les résultats
    if (!empty($ventes_recentes)) {
        foreach ($ventes_recentes as $index => $vente) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($vente['heure_vente']) . "</td>";
            echo "<td>" . htmlspecialchars($vente['MONTANT_TOTAL']) . "</td>";
            echo "<td>" . htmlspecialchars($vente['NOMBRE_ARTICLES']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>Aucune vente récente.</td></tr>";
    }
    ?>
    </table>
    <div class="achats">
    <table class="achete">
        <caption>Les produits achetés dans la journée</caption>
        <tr>
            <th>Nom du produit</th>
            <th>Quantité achetée</th>
        </tr>
        <?php
        // Requête pour récupérer les produits achetés dans la journée
        $req = $pdo->query('SELECT produit.NOM_PRODUIT, SUM(vendre.QUANTITE_VENDUE) AS total_vendu FROM vendre JOIN produit ON vendre.ID_PRODUIT = produit.ID_PRODUIT WHERE DATE(vendre.DATE_VENTE) = CURDATE() GROUP BY vendre.ID_PRODUIT ORDER BY vendre.ID_FACTURE DESC');
        $produits_achetes = $req->fetchAll(PDO::FETCH_ASSOC); // Récupérer tous les résultats
        if (!empty($produits_achetes)) {        
            foreach ($produits_achetes as $index => $produit) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($produit['NOM_PRODUIT']) . "</td>";
                echo "<td>" . htmlspecialchars($produit['total_vendu']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>Aucun produit acheté aujourd'hui.</td></tr>";
        }   
        ?>
    </table>
    </div>
</div>
</body>
</html>