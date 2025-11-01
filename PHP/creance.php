<?php
require_once 'session.php';
// Connexion à la base de données
require_once '../Config/config.php';
// Récupérer l'ID_CLIENT à partir de la session
$id_client = $_SESSION['ID_CLIENT'];
// Récupérer les créances du client
try {
    // Requête pour récupérer les 5 créances du client les plus récentes
    $stmt = $pdo->prepare(" SELECT * FROM creance JOIN facture ON creance.ID_FACTURE = facture.ID_FACTURE WHERE facture.ID_CLIENT = ? ORDER BY creance.DATE_ECHEANCE DESC LIMIT 5");
    $stmt->execute([ $id_client]);
    $creances = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/achat.css">
    <title>Page créances</title>
</head>
<body>
     <div class="inclu">
      <?php include('menu_client.php'); ?>
    </div>
    <div class="container">
    <h2>Voici l'historique de mes créances</h2>
    <table>
           <tr>
                    <th>#</th>
                    <th>Numéro Facture</th>
                    <th>Montant dû</th>
                    <th>Date échéance</th>
                    <th>Statut</th>
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
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Aucune créance trouvée.</td>
                    </tr>
                <?php endif; ?>
            </table>
</div>
</body>
</html>