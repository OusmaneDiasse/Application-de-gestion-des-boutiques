<?php
//Connexion à la base de données
 require_once '../Config/config.php';
// Récupérer l'ID de la créance depuis l'URL
    if (!isset($_GET['id_creance']) || !isset($_GET['id_client'])) {
        die("ID de créance ou ID de client manquant.");
    }
    $id = (int)$_GET['id_creance'];
    $id_client = (int)$_GET['id_client'];
    try {
        // Récupérer les détails des paiements pour la créance donnée
        $stmt = $pdo->prepare("SELECT paiement.*, creance.ID_CREANCE FROM paiement JOIN creance ON paiement.ID_CREANCE = creance.ID_CREANCE WHERE creance.ID_CREANCE = :id AND creance.FAC_ID_FACTURE IN (SELECT ID_FACTURE FROM facture WHERE ID_CLIENT = :id_client)");
        $stmt->execute([':id' => $id , ':id_client' => $id_client]);
        $paiements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Total a payer pour cette créance
        $stmt2 = $pdo->prepare("SELECT MONTANT_DU FROM creance WHERE ID_CREANCE = :id");
        $stmt2->execute([':id' => $id]);
        $montant_du = $stmt2->fetch(PDO::FETCH_ASSOC);
        //Total des paiements effectués pour cette créance
        $stmt3 = $pdo->prepare("SELECT SUM(MONTANT_PAYE) AS total_paye FROM paiement WHERE ID_CREANCE = :id");
        $stmt3->execute([':id' => $id]);
        $total_paye = $stmt3->fetch(PDO::FETCH_ASSOC);
        //Reste à payer
        $stmt4 = $pdo->prepare("SELECT MONTANT_DU - IFNULL((SELECT SUM(MONTANT_PAYE) FROM paiement WHERE ID_CREANCE = :id), 0) AS TRANCHE_RESTANTE FROM creance WHERE ID_CREANCE = :id");
        $stmt4->execute([':id' => $id]);
        $reste = $stmt4->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/detail.css">
</head>
<body>
<h1>Détails de ma créance</h1>
<div class="summary">
<p>Tranche dû : <?php echo htmlspecialchars($montant_du['MONTANT_DU']); ?> FCFA</p>
<p>Total payé : <?php echo htmlspecialchars($total_paye['total_paye'] ?? 0); ?> FCFA</p>
<p>Reste à payé : <?php echo htmlspecialchars($reste['TRANCHE_RESTANTE']); ?> FCFA</p>
</div>
<table>
    <caption> Paiements éffectués</caption>
    <tr>
        <th>ID Paiement</th>
        <th>ID Créance</th>
        <th>Montant Payé</th>
        <th>Date de Paiement</th>
        <th>Méthode de Paiement</th>
 </tr>
 <?php if (!empty($paiements)){
     foreach ($paiements as $paiement){
        echo "<tr>";
        echo "<td>" . htmlspecialchars($paiement['ID_PAIEMENT']) . "</td>";
        echo "<td>" . htmlspecialchars($paiement['ID_CREANCE']) . "</td>";  
        echo "<td>" . htmlspecialchars($paiement['MONTANT_PAYE']) . "</td>";
        echo "<td>" . htmlspecialchars($paiement['DATE_PAIEMENT']) . "</td>";
        echo "<td>" . htmlspecialchars($paiement['MODE_PAIEMENT']) . "</td>";
        echo "</tr>";
    } 

}else {
        echo "<tr><td colspan='5'>Aucun paiement trouvé pour cette créance.</td></tr>";
    }

    ?>
</table>
</body>
</html>