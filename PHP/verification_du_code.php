<?php
require_once "../config/config.php";

if (isset($_POST['verifier'])) {
    $code = trim($_POST['code']);

    // Rechercher le code dans CLIENT
    $stmt = $pdo->prepare("SELECT * FROM CLIENT WHERE CODE_REINIT = ? AND FLAG_REINIT = TRUE");
    $stmt->execute([$code]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    //chercher dans UTILISATEUR
    if (!$client) {
        $stmt = $pdo->prepare("SELECT * FROM UTILISATEUR WHERE CODE_REINIT = ? AND FLAG_REINIT = TRUE");
        $stmt->execute([$code]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($client || $user) {
        // Stocker dans une session pour la prochaine etape
        session_start();
        $_SESSION['email_reinit'] = $client['E_MAIL_CLIENT'] ?? $user['E_MAIL_UTILISATEUR'];
        $_SESSION['table_reinit'] = $client ? 'CLIENT' : 'UTILISATEUR';

        header("Location: nouveau_mot_de_pass.php");
        exit();
    } else {
        $message = "Code invalide.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verification du code</title>
    <link rel="stylesheet" href="../CSS/style_mot_de_pass_oublie.css">
</head>
<body>
    <div class="container">
    <div class="form-container">
        <h1>Verifiez votre code</h1>
        <?php if (!empty($message)) echo "<p>$message</p>"; ?>
        <form method="POST">
            <label>Entrez le code reçu</label>
            <input type="number" name="code" required>
            
            <div class="button-group">
            <button type="submit" name="verifier">Vérifier</button>
            <button type="button" onclick="window.location.href='mot_de_pass_oublie.php'">Annuler</button>
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