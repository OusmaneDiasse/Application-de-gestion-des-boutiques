<?php
require_once 'session.php';
require_once '../Config/config.php';
$sms = "";

if (isset($_GET['id'])) {
    $id_creance = (int) $_GET['id'];
} else {
    die("Aucune créance sélectionnée.");
}

// Récupérer la créance
$creance = $pdo->prepare("
    SELECT ID_FACTURE, MONTANT_DU, ID_STATUT 
    FROM creance 
    WHERE ID_CREANCE = ?
");
$creance->execute([$id_creance]);
$creance = $creance->fetch(PDO::FETCH_ASSOC);

if (!$creance) {
    die("La créance n'existe pas.");
}

// Lors de l'enregistrement
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date_paiement = $_POST['date_paiement'];
    $montant_paye = (float) $_POST['montant_paye'];
    $mode_paiement = $_POST['mode_paiement'];

    // Insérer le paiement
    $ajoutPaiement = $pdo->prepare("
        INSERT INTO paiement (ID_CREANCE, DATE_PAIEMENT, MONTANT_PAYE, MODE_PAIEMENT)
        VALUES(?, ?, ?, ?)
    ");
    $ajoutPaiement->execute([$id_creance, $date_paiement, $montant_paye, $mode_paiement]);

    // Récupérer solde actuel
    $montant_du_actuel = (float) $creance['MONTANT_DU'];

    // Calcul du nouveau solde
    $nouveau_solde = max(0, $montant_du_actuel - $montant_paye);

    // Mise à jour du solde dans la base
    $updateMontant = $pdo->prepare("
        UPDATE creance SET MONTANT_DU = ? 
        WHERE ID_CREANCE = ?
    ");
    $updateMontant->execute([$nouveau_solde, $id_creance]);

    // Mettre à jour le statut
    if ($nouveau_solde == 0) {
        // payé entièrement
        $updateStatut = $pdo->prepare("
            UPDATE creance SET ID_STATUT = 2 
            WHERE ID_CREANCE = ?
        ");
        $updateStatut->execute([$id_creance]);
        $sms = "Paiement réussi, créance soldée.";
    } else {
        // encore un reste
        $updateStatut = $pdo->prepare("
            UPDATE creance SET ID_STATUT = 1 
            WHERE ID_CREANCE = ?
        ");
        $updateStatut->execute([$id_creance]);
        $sms = "Paiement enregistré, créance toujours en cours.";
    }

    // Rafraîchir les infos créance
    $creance = $pdo->prepare("
        SELECT ID_FACTURE, MONTANT_DU, ID_STATUT 
        FROM creance 
        WHERE ID_CREANCE = ?
    ");
    $creance->execute([$id_creance]);
    $creance = $creance->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Enregistrer un paiement</title>
    <link rel="stylesheet" href="../CSS/style_paiement.css">
</head>
<body>
    <div class="inclu">
        <?php include('menugerant.php'); ?>
    </div>

    <div class="form-container">
        <?php if (!empty($sms)): ?>
            <p class="alerterror"><?= $sms ?></p>
        <?php endif; ?>

        <form method="POST">
            <h2>Enregistrer un paiement</h2>

            <label>Montant dû :</label>
            <input type="text" value="<?= $creance['MONTANT_DU'] ?>" readonly>

            <label>Date du paiement :</label>
            <input type="date" name="date_paiement" value="<?= date("Y-m-d") ?>" required>

            <label>Montant payé :</label>
            <input type="number" name="montant_paye" required>

            <label>Mode de paiement :</label>
            <select name="mode_paiement" required>
                <option value="espèces">Espèces</option>
                <option value="wave">Wave</option>
                <option value="orange_money">Orange_Money</option>
            </select>
            
            <div class="button">
                <input type="submit" value="Enregistrer" class="button-enregistrer">
            </div>
        </form>
        <br><br>

        <a href="liste_creance.php" class="cle">← Retour à la liste des créances</a>
    </div>

<script>
    // Sélectionne l’alerte sms
    const sms = document.querySelector('.alerterror');

    if (sms) {
        setTimeout(() => {
            sms.style.opacity = '0';
            setTimeout(() => sms.remove(), 500);
        }, 3000);
    }
</script>

</body>
</html>