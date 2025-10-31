<?php
require_once '../Config/config.php';

$message = "";

if (isset($_GET['id'])) {
    $id_creance = (int) $_GET['id'];
} else {
    die("Aucune créance sélectionnée.");
}

$creance = $pdo->prepare("SELECT ID_FACTURE, MONTANT_DU, ID_STATUT FROM creance WHERE ID_CREANCE = ?");
$creance->execute([$id_creance]);
$creance = $creance->fetch(PDO::FETCH_ASSOC);

if (!$creance) {
    die("La créance n'existe pas.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date_paiement = $_POST['date_paiement'];
    $montant_paye = $_POST['montant_paye']; 
    $mode_paiement = $_POST['mode_paiement'];

    $ajoutPaiement = $pdo->prepare("
        INSERT INTO paiement (ID_CREANCE, DATE_PAIEMENT, MONTANT_PAYE, MODE_PAIEMENT)
        VALUES(?, ?, ?, ?)
    ");
    $ajoutPaiement->execute([$id_creance, $date_paiement, $montant_paye, $mode_paiement]);

    $montant = $pdo->prepare("SELECT SUM(MONTANT_PAYE) AS total_paye FROM paiement WHERE ID_CREANCE = ?");
    $montant->execute([$id_creance]);
    $total = $montant->fetch(PDO::FETCH_ASSOC);

    $montant_du = $creance['MONTANT_DU'];
    $total_paye = $total['total_paye'];

    if ($total_paye >= $montant_du) {
        $updateStatut = $pdo->prepare("UPDATE creance SET ID_STATUT = 2 WHERE ID_CREANCE = ?");
        $updateStatut->execute([$id_creance]);
        $message = "Paiement réussi, créance soldée.";
    } else {
        $statut = $pdo->prepare("UPDATE creance SET ID_STATUT = 1 WHERE ID_CREANCE = ?");
        $statut->execute([$id_creance]);
        $message = "Paiement enregistré, créance toujours en cours.";
    }

    $creance = $pdo->prepare("SELECT ID_FACTURE, MONTANT_DU, ID_STATUT FROM creance WHERE ID_CREANCE = ?");
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
        <?php if (!empty($message)): ?>
           <p><?= $message ?></p>
        <?php endif; ?>
            <div class="form-container">
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
    </div>
    <a href="liste_creance.php">← Retour à la liste des créances</a>
</body>
</html>