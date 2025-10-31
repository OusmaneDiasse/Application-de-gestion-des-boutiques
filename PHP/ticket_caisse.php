<?php
require_once '../Config/config.php';

if (!isset($_GET['id_facture'])) {
    die("Facture introuvable !");
}

$id_facture = (int)$_GET['id_facture'];

$facture = $pdo->prepare("
    SELECT f.*, c.NOM_CLIENT, u.NOM_UTILISATEUR
    FROM facture f
    LEFT JOIN client c ON f.ID_CLIENT = c.ID_CLIENT
    LEFT JOIN utilisateur u ON f.ID_UTILISATEUR = u.ID_UTILISATEUR
    WHERE f.ID_FACTURE = ?
");
$facture->execute([$id_facture]);
$infos_facture = $facture->fetch(PDO::FETCH_ASSOC);

$ventes = $pdo->prepare("
    SELECT p.NOM_PRODUIT, v.QUANTITE_VENDUE, p.PRIX
    FROM vendre v
    JOIN produit p ON v.ID_PRODUIT = p.ID_PRODUIT
    WHERE v.ID_FACTURE = ?
");
$ventes->execute([$id_facture]);
$produits = $ventes->fetchAll(PDO::FETCH_ASSOC);

$total_general = 0;
foreach($produits as $p){
    $total_general += $p['PRIX'] * $p['QUANTITE_VENDUE'];
}

$montant_paye = $infos_facture['MONTANT_PAYE'] ?? $total_general;
$reste_a_payer = $total_general - $montant_paye;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticket de caisse</title>
    <link rel="stylesheet" href="../CSS/style_ticket.css">
</head>
<body onload="window.print()">

       <div class="ticket-container">
          <div class="ticket-header">
            <h2>BOUTIQUE ADJA SHOP</h2>
            <p>Adresse : Mamlles, Dakar</p>
            <p>Tel : +221 77 123 45 67</p>
       </div>

    <div class="ticket-details">
        <p>Date : <?= date('Y-m-d') ?></p>
        <p>Heure : <?= date('H:i:s') ?></p>
        <p>Facture N° : <?= $infos_facture['ID_FACTURE'] ?></p>
        <hr>
        <p>Client : <?= htmlspecialchars($infos_facture['NOM_CLIENT']) ?></p>
        <p>Mode de paiement : <?= $infos_facture['TYPE_DE_PAYMENT'] ?></p>
        <p>Vendeur : <?= htmlspecialchars($infos_facture['NOM_UTILISATEUR'] ) ?></p>
    </div>

    <table class="ticket-table">
        <tr>
            <th>Nom du produit</th>
            <th>Quantité</th>
            <th>Prix unitaire(FCFA)</th>
            <th>Total ligne(FCFA)</th>
        </tr>
        <?php foreach($produits as $p):
            $total_ligne = $p['PRIX'] * $p['QUANTITE_VENDUE'];
        ?>
        <tr>
            <td><?= htmlspecialchars($p['NOM_PRODUIT']) ?></td>
            <td><?= $p['QUANTITE_VENDUE'] ?></td>
            <td><?= number_format($p['PRIX'],0,',',' ') ?></td>
            <td><?= number_format($total_ligne,0,',',' ') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="ticket-total">
        Total général : <?= number_format($total_general,0,',',' ') ?> FCFA<br>
        Montant payé : <?= number_format($montant_paye,0,',',' ') ?> FCFA<br>
        Reste à payer : <?= number_format($reste_a_payer,0,',',' ') ?> FCFA
    </div>
<hr>
    <div class="ticket-footer">
        <p>Merci pour votre achat 😊!</p>
    </div>
</div>
</body>
</html>