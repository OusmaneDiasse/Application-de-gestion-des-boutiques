<?php
// Connexion à la base de données
require_once '../Config/config.php';
// Récupérer le nom du client et son ID
session_start();
$email =$_SESSION['email'];
// Récupérer l'ID_CLIENT à partir de l'email
try {
    $stmt = $pdo->prepare("SELECT ID_CLIENT, NOM_CLIENT FROM client WHERE E_MAIL_CLIENT = ?");
    $stmt->execute([$email]);
    $resultats = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($resultats) {
        $id_client = $resultats['ID_CLIENT'];
    } else {
        die("Client non trouvé.");
    }
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
// Récupérer les créances du client
try {
    // Requête pour récupérer les créances du client 
    $stmt = $pdo->prepare(" SELECT * FROM creance JOIN facture ON creance.ID_FACTURE = facture.ID_FACTURE WHERE facture.ID_CLIENT = ?");
    $stmt->execute([ $id_client]);
    $creances = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil pour le client</title>
    <link rel="stylesheet" href="../CSS/accueilclient.css">
</head>
<body>
    <div class="BLOCK">
        <div class="bienvenue">
            <h2>Bienvenue, <span><?php echo htmlspecialchars($resultats['NOM_CLIENT']); ?></span>👋</h2>
            <p>Voici un résumé de vos créances.</p>
        </div>
        <div class="tableau">
            <table>
                
       <caption>Mes créances</caption>     
           <tr>
                    <th>#</th>
                    <th>Numéro Facture</th>
                    <th>Montant dû</th>
                    <th>Date échéance</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
                <?php if (!empty($creances)): ?>
                    <?php foreach ($creances as $c): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($c['ID_CREANCE']) ; ?></td>
                            <td><?php echo htmlspecialchars( $c['ID_FACTURE']); ?></td>
                            <td><?php echo htmlspecialchars($c['MONTANT_DU']); ?> FCFA</td>
                            <td><?php echo htmlspecialchars($c['DATE_ECHEANCE']); ?></td>
                            <td><?php
                             if( $c['ID_STATUT'] == 1){
                                echo "En cours";
                                } else {
                                    echo "Soldée";
                                }
                                ?></td>
                            <td><a href="">Voir les détails</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Aucune créance trouvée.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
    <div class="details">
    <div class="total_creances">
        <!-- Total des creances dues -->
        <h3>Total des créances dues </h3>
        <div class="montant">
      <?php
        try {
            $stmt = $pdo->prepare("SELECT SUM(MONTANT_DU) AS total_creances FROM creance JOIN facture ON creance.ID_FACTURE = facture.ID_FACTURE WHERE facture.ID_CLIENT = ? AND creance.ID_STATUT = 1");
            $stmt->execute([$id_client]);
            $total = $stmt->fetch(PDO::FETCH_ASSOC);
            $total_creances = $total['total_creances'] ?? 0;
            echo "<p>" . htmlspecialchars($total_creances) . " FCFA</p>";
        } catch (PDOException $e) {
            die("Erreur SQL : " . $e->getMessage());
        }
        ?> </div>
</div>
<!-- Liste des produits achetés par le client -->
 <div class="tableau_achats">
<table>
    <caption class="achats">Mes achats</caption>
    <tr>
        <th>#</th>
        <th>Produit</th>
        <th>Quantité</th>
        <th>Prix unitaire</th>
        <th>Total</th>
        <th>Date </th>
    </tr>
    <?php
    try {
        $stmt = $pdo->prepare("SELECT produit.NOM_PRODUIT, produit.PRIX, vendre.QUANTITE_VENDUE, vendre.DATE_VENTE FROM vendre JOIN produit ON vendre.ID_PRODUIT = produit.ID_PRODUIT JOIN facture ON vendre.ID_FACTURE = facture.ID_FACTURE WHERE facture.ID_CLIENT = ?");
        $stmt->execute([$id_client]);
        $achats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($achats)) {
            foreach ($achats as $index => $achat) {
                echo "<tr>";
                echo "<td>" . ($index + 1) . "</td>";
                echo "<td>" . htmlspecialchars($achat['NOM_PRODUIT']) . "</td>";
                echo "<td>" . htmlspecialchars($achat['QUANTITE_VENDUE']) . "</td>";
                echo "<td>" . htmlspecialchars($achat['PRIX']) . " FCFA</td>";
                echo "<td>" . htmlspecialchars($achat['PRIX'] * $achat['QUANTITE_VENDUE']) . " FCFA</td>";
                echo "<td>" . htmlspecialchars($achat['DATE_VENTE']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Aucun achat trouvé.</td></tr>";
        }
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
    ?>
</table>
</div>
</div>

</body>
</html>
