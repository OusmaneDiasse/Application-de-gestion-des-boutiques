<?php
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
            JOIN facture f ON c.FAC_ID_FACTURE = f.ID_FACTURE
            JOIN client cli ON f.ID_CLIENT = cli.ID_CLIENT
            WHERE cli.NOM_CLIENT LIKE :recherche";

    if ($id_statut !== '') {
        $sql .= " AND c.ID_STATUT = :id_statut";
    }

    $sql .= " ORDER BY c.DATE_ECHEANCE ASC";

    $stmt = $pdo->prepare($sql);

    $params = [':recherche' => "%$recherche%"];
    if ($id_statut !== '') {
        $params[':id_statut'] = $id_statut;
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
<div>
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
        <td><?= $c['ID_STATUT'] == 2 ? "Terminé" : "En cours"; ?></td>
        <td>
          <?php if ($c['ID_STATUT'] == 1): ?>
            <a href="#?id=<?= $c['ID_CREANCE'] ?>">Ajouter un nouveau paiement</a>
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
</body>
</html>
