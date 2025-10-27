<?php
// Connexion à la base de données
require_once '../Config/config.php';
//liste des clients
try {
    $stmt = $pdo->prepare("SELECT ID_CLIENT,NOM_CLIENT,E_MAIL_CLIENT,TELEPHONE FROM client");
    $stmt->execute(); // Exécuter la requête
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupérer tous les résultats
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
// Gestion de la recherche
$recherche = $_GET['search'] ?? '';
if (isset($_GET['reset'])) {
    $recherche = '';
}
if ($recherche !== '') {
    $clients = array_filter($clients, function ($client) use ($recherche) {
        return stripos($client['NOM_CLIENT'], $recherche) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/listeclient.css">
    <title>Liste des clients</title>
</head>
<body>
   <div class="tableau" >
    <div class="block">
    <h1 class="caption">Liste des clients de la boutique</h1>
    <a href="Formulaire_client.php" class="client">Ajouter un client</a>
   <form method="GET" action="">
          <input type="text" name="search" placeholder="Rechercher un client..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
          <button type="submit" class="recherche">Rechercher</button>
          <button type="submit" name="reset" value="" class="réinitialiser">Réinitialiser</button>
      </form>
      </div>
   <table class="BLOCK"> 
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>E-mail</th>
            <th>Téléphone</th>
        </tr>
        <?php if (!empty($clients)): 
            foreach ($clients as $client): ?>
                <tr>
                    <td><?php echo htmlspecialchars($client['ID_CLIENT']); ?></td>
                    <td><?php echo htmlspecialchars($client['NOM_CLIENT']); ?></td>
                    <td><?php echo htmlspecialchars($client['E_MAIL_CLIENT']); ?></td>
                    <td><?php echo htmlspecialchars($client['TELEPHONE']); ?></td>
                </tr>
            <?php endforeach; 
        else: ?>    
            <tr>
                <td colspan="4">Aucun client trouvé.</td>
            </tr>
        <?php endif; ?> 
   </table>
</div>
    
</body>
</html>