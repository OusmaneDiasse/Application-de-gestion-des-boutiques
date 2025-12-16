<?php
require_once 'session.php';
require_once "../Config/config.php";

$id = $_GET['id'] ?? null;
$produit = null;

// Récupérer produit si modification
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM produit WHERE ID_PRODUIT=:id");
    $stmt->execute([':id' => $id]);
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$produit) die("Produit non trouvé. <a href='afficher_produit.php'>Retour</a>");
}

// Gestion POST (ajout ou modification)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        ':nom' => $_POST['NOM_PRODUIT'],
        ':prix' => $_POST['PRIX'],
        ':stock' => $_POST['STOCK'],
        ':fab' => $_POST['DATE_DE_FABRICATION'],
        ':per' => $_POST['DATE_DE_PEREMPTION']
    ];

    if (!empty($_POST['ID_PRODUIT'])) {
        // Modifier
        $data[':id'] = $_POST['ID_PRODUIT'];
        $stmt = $pdo->prepare("UPDATE produit 
            SET NOM_PRODUIT=:nom, PRIX=:prix, STOCK=:stock, DATE_DE_FABRICATION=:fab, DATE_DE_PEREMPTION=:per 
            WHERE ID_PRODUIT=:id");
        $stmt->execute($data);
        header("Location: afficher_produit.php?message=Produit modifié avec succès");
    } else {
        // Ajouter
        $stmt = $pdo->prepare("INSERT INTO produit (NOM_PRODUIT, PRIX, STOCK, DATE_DE_FABRICATION, DATE_DE_PEREMPTION) 
            VALUES (:nom, :prix, :stock, :fab, :per)");
        $stmt->execute($data);
        header("Location: afficher_produit.php?message=Produit ajouté avec succès");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $produit ? "Modifier Produit" : "Ajouter Produit" ?></title>
    <link rel="stylesheet" href="../CSS/style_ajout_produit.css">
</head>
<body>
    <div class="inclu">
      <?php include('menugerant.php'); ?>
    </div>
<div class="form-container">
     <p>
    <h1><?= $produit ? "Modifier Produit" : "Ajouter Produit" ?></h1>
    <form method="POST" action="">
        <?php if ($produit): ?>
            <input type="hidden" name="ID_PRODUIT" value="<?= $produit['ID_PRODUIT'] ?>">
        <?php endif; ?></p>
        <p>
            <label>Nom du produit</label>
            <input type="text" name="NOM_PRODUIT" placeholder="@nom_produit" required minlength="2" value="<?= $produit['NOM_PRODUIT'] ?? '' ?>">
        </p>
        <p>
            <label>Prix</label>
            <input type="number" name="PRIX" min="1" step="0.01" placeholder="#123" required value="<?= $produit['PRIX'] ?? '' ?>">
        </p>
        <p>
            <label>Date de fabrication</label>
            <input type="date" name="DATE_DE_FABRICATION" required value="<?= $produit['DATE_DE_FABRICATION'] ?? '' ?>">
        </p>
        <p>
            <label>Date de péremption</label>
            <input type="date" name="DATE_DE_PEREMPTION" required value="<?= $produit['DATE_DE_PEREMPTION'] ?? '' ?>">
        </p>
        <p>
            <div class="button-group">
                <button type="submit"><?= $produit ? "Modifier" : "Ajouter" ?></button>
                <button type="button" onclick="window.location.href='afficher_produit.php'">Annuler</button>
            </div>
        </p>
    </form>
</div>
</body>
</html>