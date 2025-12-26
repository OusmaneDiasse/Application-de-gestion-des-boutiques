<?php
require_once "../config/config.php";
session_start();

// Vérifie si la session est valide
if (empty($_SESSION['email_reinit']) || empty($_SESSION['table_reinit'])) {
    header("Location: mot_de_passe_oublie.php");
    exit();
}

$email = $_SESSION['email_reinit'];
$table = $_SESSION['table_reinit'];
$message = '';

if (isset($_POST['changer'])) {
    $mdp  = trim($_POST['mdp'] ?? '');
    $conf = trim($_POST['confirmer'] ?? '');

    if ($mdp === $conf) {
        // Hachage
        $hash = password_hash($mdp, PASSWORD_ARGON2ID);
        echo $hash;

        // Mise à jour selon la table
        $sql = $table === "CLIENT"
            ? "UPDATE CLIENT SET MOT_DE_PASSE = ?, CODE_REINIT = 0, FLAG_REINIT = FALSE WHERE E_MAIL_CLIENT = ?"
            : "UPDATE UTILISATEUR SET MOT_DE_PASSE_UTILISATEUR = ?, CODE_REINIT = 0, FLAG_REINIT = FALSE WHERE E_MAIL_UTILISATEUR = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$hash, $email]);

        // Nettoyage de la session
        session_unset();
        session_destroy();
        
        header("Location:../index.php?message=" . urlencode("Mot de passe modifié avec succès !"));
        exit();
    }

    $message = "Les mots de passe ne correspondent pas.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau mot de passe</title>
    <link rel="stylesheet" href="../CSS/style_mot_de_pass_oublie.css">
</head>
<body>
    <div class="container">
    <div class="form-container">
        <h1>Modifier votre mot de passe</h1>
        <?php if (!empty($message)) echo "<p>" . htmlspecialchars($message) . "</p>"; ?>
        <form method="POST" autocomplete="off">
            <label>Nouveau mot de passe</label>
            <input type="password" name="mdp" minlength="8"required><br><br>

            <label>Confirmer le mot de passe</label>
            <input type="password" name="confirmer" minlength="8"required><br><br>
            
            <div class="button-group">
            <button type="submit" name="changer">Changer le mot de passe</button>
            <button type="button" onclick="window.location.href='verification_du_code.php'">Annuler</button>
            </div>
        </form>
    </div>
    <div class="gestion">
        
        <img src="../IMG/image.jpg" alt="image" >
         <h1 class="boutique">Gestion des boutiques</h1>
         <p>
            Application simple et fiable pour suivre vos ventes,
            vos clients et vos produits.  
            Entrez votre email pour recevoir un code de réinitialisation.
            
        </p>
      </div>
      </div>
</body>
</html>