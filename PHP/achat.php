<?php
//evite qu’un utilisateur puisse revenir sur la page après déconnexion via le bouton “Retour” du navigateur.
require_once 'session.php';
// Connexion à la base de données
require_once '../Config/config.php';
$id_client=$_SESSION['id_client']
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/achat.css">
    <title>Page achat</title>
</head>
<body>
     <div class="inclu">
      <?php include('menu_client.php'); ?>
    </div>
    <div class="container">
    <h2>Voici l'historique de mes achats</h2>
    <table>
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
        $stmt = $pdo->prepare("SELECT produit.NOM_PRODUIT, produit.PRIX, vendre.QUANTITE_VENDUE, vendre.DATE_VENTE FROM vendre JOIN produit ON vendre.ID_PRODUIT = produit.ID_PRODUIT JOIN facture ON vendre.ID_FACTURE = facture.ID_FACTURE WHERE facture.ID_CLIENT = ? ORDER BY vendre.DATE_VENTE DESC ");
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
            echo "<tr><td colspan='6'>Aucun achat trouvé.</td></tr>";
        }
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
    ?>
</table>
</div>
</body>
</html>