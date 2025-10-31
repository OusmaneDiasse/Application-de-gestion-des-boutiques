<?php
session_start(); // Ajouté pour gérer les sessions
require_once '../Config/config.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    die("Vous devez être connecté pour effectuer cette action.");
}

if (empty($_POST['id_produit']) || empty($_POST['quantite']) || empty($_POST['id_client'])) {
    die("Données sont manquantes.");
}

$id_client = (int)$_POST['id_client'];
$id_produits = $_POST['id_produit'];
$quantites = $_POST['quantite'];
$prix_unitaires = $_POST['prix_unitaire'];
$totaux = $_POST['montant_total'];
$date_vente = date("Y-m-d");
$heure_vente = date("H:i:s");

$total_facture = 0;
foreach ($totaux as $t) {
    $total_facture += (float)$t;
}

$creance = isset($_POST['creance']);
$montant_paye = isset($_POST['montant_paye']) ? (float)$_POST['montant_paye'] : 0;

if ($creance && $montant_paye >= $total_facture) {
    die("Le montant payé doit être inférieur au total de la facture pour une créance.");
}

$id_utilisateur = $_SESSION['id_utilisateur'];
$facture = $pdo->prepare("
    INSERT INTO facture (ID_CLIENT, ID_UTILISATEUR, TYPE_DE_PAYMENT, MONTANT_TOTAL)
    VALUES (?, ?, ?, ?)
");
$facture->execute([$id_client, $id_utilisateur, $creance ? "Crédit" : "Espèces", $total_facture]);
$id_facture = $pdo->lastInsertId();

for ($i = 0; $i < count($id_produits); $i++) {
    $id_produit = (int)$id_produits[$i];
    $quantite = (int)$quantites[$i];

    $recupere = $pdo->prepare("SELECT STOCK FROM produit WHERE ID_PRODUIT = ?");
    $recupere->execute([$id_produit]);
    $produit = $recupere->fetch(PDO::FETCH_ASSOC);
 
    if (!$produit) {
        die("Produit introuvable !");
    }
    if ($quantite > $produit['STOCK']) {
        die("Stock insuffisant pour le produit : $id_produit");
    }

    $vente = $pdo->prepare("
        INSERT INTO vendre (ID_PRODUIT, ID_FACTURE, DATE_VENTE, QUANTITE_VENDUE, HEURE_VENTE)
        VALUES (?, ?, ?, ?, ?)
    ");
    $vente->execute([$id_produit, $id_facture, $date_vente, $quantite, $heure_vente]);

    $update_stock = $pdo->prepare("UPDATE produit SET STOCK = STOCK - ? WHERE ID_PRODUIT = ?");
    $update_stock->execute([$quantite, $id_produit]);
}

if ($creance) {
    echo "<script>
        // Ouvre le ticket dans un nouvel onglet
        window.open('ticket_caisse.php?id_facture=$id_facture', '_blank');
        
        // Redirige ensuite la fenêtre principale vers la page d’ajout de créance
        window.location.href = 'ajout_creance.php?id_facture=$id_facture&montant_paye=$montant_paye';
    </script>";
} else {
    echo "<script>
        window.location.href = 'ticket_caisse.php?id_facture=$id_facture';
    </script>";
}
exit;
?>