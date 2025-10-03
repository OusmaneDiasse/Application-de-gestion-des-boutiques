<?php
require_once "../config/config.php";

// Vérifier si l’ID de la facture est bien transmis
$id_facture = $_GET['id_facture'] ?? null;

if (!$id_facture) {
    die("Aucune facture sélectionnée. <a href='Facturation_form.php'>Retour</a>");
}

// Récupérer les infos de la facture
$stmt = $pdo->prepare("SELECT MONTANT_TOTAL FROM facture WHERE ID_FACTURE = :id");
$stmt->execute([':id' => $id_facture]);
$facture = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$facture) {
    die("Facture introuvable. <a href='Facturation_form.php'>Retour</a>");
}

$montant_paye = $_GET['montant_paye'] ?? 0;
$montant_du = $facture['MONTANT_TOTAL'] - $montant_paye;

// Gestion POST ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        ':montant'   => $montant_du,
        ':date'      => $_POST['DATE_CREANCE'],
        ':statut'    => 0, 
        ':id_facture'=> $id_facture
    ];

    $stmt = $pdo->prepare("INSERT INTO creance (MONTANT_DU, DATE_ECHEANCE, STATUT, FAC_ID_FACTURE) 
                                 VALUES (:montant, :date, :statut, :id_facture)");
    $stmt->execute($data);

    // Redirection vers la liste des créances
    header("Location: liste_creance.php?message=Créance ajoutée avec succès");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajouter Créance</title>
    <link rel="stylesheet" href="../CSS/style_ajout_creance.css">
</head>
<body>
<div class="form-container">
    <h1>Ajouter Créance</h1>
    <form method="POST" action="">

        <!-- ID Facture caché -->
        <input type="hidden" name="FAC_ID_FACTURE" value="<?= htmlspecialchars($id_facture) ?>">

        <p>
        <label>Montant de la créance</label>
        <input type="number" name="MONTANT_CREANCE" value="<?= htmlspecialchars($montant_du) ?>" readonly></p>

        <p>
        <label>Date échéance</label>
        <input type="date" name="DATE_CREANCE" required></p>

        <p>
        <label>Statut</label>
        <input type="text" value="En cours" readonly>
        </p>

        <div class="button-group">
            <button type="submit">Ajouter</button>
            <button type="button" onclick="window.location.href='Facturation_form.php'">Annuler</button>
        </div>
    </form>
</div>
</body>
</html>