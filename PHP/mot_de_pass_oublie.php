<?php
require_once "../config/config.php";
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['envoyer'])) {
    $email = trim($_POST['email']);
    $code = rand(100000, 999999);

    // Verifier si l'email existe dans CLIENT ou UTILISATEUR
    $sources = [
        ['table' => 'CLIENT', 'col' => 'e_mail_client'],
        ['table' => 'UTILISATEUR', 'col' => 'e_mail_utilisateur']
    ];

    $table = $colEmail = null;
    foreach ($sources as $src) {
        $stmt = $pdo->prepare("select 1 FROM {$src['table']} WHERE {$src['col']} = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $table = $src['table'];
            $colEmail = $src['col'];
            break;
        }
    }

    if ($table) {
        // mise a jour code_reinit et flag_reinit
        $pdo->prepare("UPDATE $table SET CODE_REINIT = ?, FLAG_REINIT = TRUE WHERE $colEmail = ?")
            ->execute([$code, $email]);

        // Envoi du mail avec PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'gestion.boutique.app@gmail.com';
            $mail->Password = 'oipi bjfm cxmx rovl'; // mot de passe d’application
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('gestion.boutique.app@gmail.com', 'Gestion Boutique');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Code de reinitialisation de mot de passe";
            $mail->Body = "Bonjour,<br><br>Voici votre code de reinitialisation : <b>$code</b><br><br>Merci.";

            $mail->send();

            // Sauvegarde en session et redirection
            session_start();
            $_SESSION['email_reinit'] = $email;
            $_SESSION['table_reinit'] = $table;

            header("Location: verification_du_code.php");
            exit();
        } catch (Exception $e) {
            $message = "Erreur lors de l'envoi du mail : " . htmlspecialchars($mail->ErrorInfo);
        }
    } else {
        $message = "Adresse e-mail introuvable dans le système.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="../CSS/style_mot_de_pass_oublie.css">
</head>
<body>
    <div class="container">
    <div class="form-container">
        <h1>Mot de passe oublié</h1>
        <?php if (!empty($message)) echo "<p>$message</p>"; ?>
        <form method="POST">
            <label>Entrez votre e-mail </label>
            <input type="email" name="email" placeholder="exemple@gmail.com" required>
            
            <div class="button-group">
            <button type="submit" name="envoyer">Envoyer le code</button>
            <button type="button" onclick="window.location.href='../index.html'">Annuler</button>
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