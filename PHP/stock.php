<?php 
require_once "../config/config.php";

// RECUPERER LES INFOS DU PRODUIT A AJOUTER DANS LE STOCK
if (!isset($_GET['id_produit'])) {
    die("Aucun produit sélectionné");
}

$id_produit = (int) $_GET['id_produit'];

$stmt = $pdo->prepare("SELECT NOM_PRODUIT FROM produit WHERE ID_PRODUIT = ?");
$stmt->execute([$id_produit]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    die("Produit introuvable");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantite = (int) $_POST['quantite'];
    $prix_achat = (int) $_POST['prix_achat'];
    $prix_total = (int) $_POST['prix_total'];
    $date_achat = $_POST['date_achat'];
    $fournisseur = $_POST['fournisseur'] ?? '';
    $observation = $_POST['observation'] ?? '';

    // INSERTION
    $stmt = $pdo->prepare("INSERT INTO stock (ID_PRODUIT, QUANTITE_ACHETEE, PRIX_ACHAT, PRIX_ACHAT_TOTALE, DATE_ACHAT, FOURNISSEUR, OBSERVATION)
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_produit, $quantite, $prix_achat, $prix_total, $date_achat, $fournisseur, $observation]);

    // Mise jour du stock de produit
    $pdo->prepare("UPDATE produit SET STOCK = STOCK + ? WHERE ID_PRODUIT = ?")->execute([$quantite, $id_produit]);

    header("Location: afficher_produit.php?message=Stock ajouté avec succès");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajout de Stock</title>
    <link rel="stylesheet" href="../CSS/style_stock.css">
</head>
<body>
    <div class="form-container">
        <h1>Ajouter Un Stock</h1>
        <form method="POST">
         <p>
         <label>Nom du produit </label>
         <input type="text" value="<?= htmlspecialchars($produit['NOM_PRODUIT']) ?>" readonly>
         </p>

         <p>
         <label>Quantité achetée</label>
         <input type="number" id="quantite" name="quantite" min="1" required oninput="calculerPrixTotal()">
         </p>

         <p>
         <label>Prix d'achat unitaire </label>
         <input type="number" id="prix_achat" name="prix_achat"  min="1" required oninput="calculerPrixTotal()">
         </p>

         <p>
         <label>Prix d'achat total </label>
         <input type="text" id="prix_total" name="prix_total" readonly>
         </p>

         <p>
         <label>Date d'achat </label>
         <input type="date" name="date_achat" required>
         </p>

         <p>
         <label>Fournisseur </label>
         <input type="text" name="fournisseur">
         </p>

         <p>
         <label>Observation </label>
         <textarea name="observation"></textarea>
         </p>

        <div class="button-group">
        <button type="submit" class="btn-valider">Ajouter</button>
        <button type="button" onclick="window.location.href='afficher_produit.php'">Annuler</button>
        </div>
        </form>
    </div>

    <script>
        function calculerPrixTotal() {
            let prixAchat = parseInt(document.getElementById("prix_achat").value) || 0;
            let quantite = parseInt(document.getElementById("quantite").value) || 0;
            let total = prixAchat * quantite;
            document.getElementById("prix_total").value = total.toFixed();
        }
    </script>
</body>
</html>