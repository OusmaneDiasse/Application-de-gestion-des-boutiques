<?php
require_once 'session.php';
require_once "../config/config.php";

try {
    $recherche = $_GET['search'] ?? '';
    $id_statut = $_GET['id_statut'] ?? '';

    if (isset($_GET['reset'])) {
    $recherche = '';
    $id_statut = '';
    }

    $sql = "SELECT c.ID_CREANCE, c.MONTANT_DU, c.DATE_ECHEANCE, c.ID_STATUT,
                   f.MONTANT_TOTAL, cli.NOM_CLIENT
            FROM creance c
            JOIN facture f ON c.ID_FACTURE = f.ID_FACTURE
            JOIN client cli ON f.ID_CLIENT = cli.ID_CLIENT
            WHERE cli.NOM_CLIENT LIKE :recherche";
    if ($id_statut !== '') {
        $sql .= " AND c.ID_STATUT = :statut";
    }

    $sql .= " ORDER BY c.DATE_ECHEANCE ASC";

    $stmt = $pdo->prepare($sql);

    $params = [':recherche' => "%$recherche%"];
    if ($id_statut !== '') {
        $params[':statut'] = $id_statut;
    }

    $stmt->execute($params);
    $creances = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur : " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Liste des Créances</title>
  <link rel="stylesheet" href="../CSS/style_liste_creance.css">
</head>
<body>
<div class="inclu">
      <?php include('menugerant.php'); ?>
    </div>
<div class=boutique>
<div class="form-container">
  <h1>Liste des Créances De La Boutique</h1>

  <!-- Barre de recherche client -->
  <div class="search-container">
      <form method="GET" action="">
          <input type="text" name="search" placeholder="Rechercher un client..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
          <button type="submit">Rechercher</button>
          <button type="submit" name="reset" value="">Réinitialiser</button>
      </form>
  </div>
  <?php if (!empty($_GET['message'])): ?>
  <p class="message <?= strpos($_GET['message'], 'Erreur') ? 'error' : 'success' ?>">
    <?= htmlspecialchars($_GET['message']) ?>
  </p>
<?php endif; ?>
</div>
<?php if ($creances): ?>
  <form method="GET" action="">
  <table>
    <tr>
      <th>Client</th>
      <th>Montant du (FCFA)</th>
      <th>Date d'échéance</th>
      <th>
        Statut <br>
        <select name="id_statut" onchange="this.form.submit()">
            <option value="">Tous</option>
            <option value="1" <?= ($id_statut === "1") ? 'selected' : '' ?>>En cours</option>
            <option value="2" <?= ($id_statut === "2") ? 'selected' : '' ?>>Terminé</option>
        </select>
      </th>
      <th>Action</th>
    </tr>
    <?php foreach ($creances as $c): ?>
      <tr>
        <td><?= htmlspecialchars($c['NOM_CLIENT']) ?></td>
        <td><?= htmlspecialchars($c['MONTANT_DU']) ?></td>
        <td><?= htmlspecialchars($c['DATE_ECHEANCE']) ?></td>
        <td><?= $c['ID_STATUT'] == 1 ? "En cours" : "Terminé"; ?></td>
        <td>
          <?php if ($c['ID_STATUT'] == 1): ?>
            <a href="paiement.php?id=<?= $c['ID_CREANCE'] ?>"class="buttonpaie">Ajouter un nouveau paiement</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
  </form>
<?php else: ?>
  <p>Aucune créance trouvée.</p>
<?php endif; ?>
</div>
</div>
<script>
    // Sélectionne le message
    const msg = document.querySelector('.message');

    if (msg) {
        // Après 3 secondes (3000 ms), on fait disparaître le message
        setTimeout(() => {
            msg.style.transition = 'opacity 0.5s ease' ;
            msg.style.opacity = '0'; // fade out
            // Optionnel : le retirer du DOM après la transition
            setTimeout(() => msg.remove(), 500); // correspond à la durée de transition CSS
        }, 3000); // temps d’affichage du message
    }
</script>
</body>
</html>
