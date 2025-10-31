<?php
session_start();
require_once '../Config/config.php';
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Génération mot de passe aléatoire
function generatePassword($length = 10) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+[]{}';
    $pass = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $pass .= $chars[random_int(0, $max)];
    }
    return $pass;
}

// Traitement du formulaire
$message = ""; // variable locale pour le message

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST['nom_et_prenom']);
    $email = trim($_POST['email']);
    $role_text = $_POST['role'];
    $telephone = trim($_POST['telephone_utilisateur']);
    $adresse = trim($_POST['adress_utilisateur']);

    $plainPassword = generatePassword(10);
    $hashedPassword = password_hash($plainPassword, PASSWORD_ARGON2ID);

    // Vérifie si l'utilisateur existe déjà
    $verifierUtilisateur = $pdo->prepare("SELECT ID_UTILISATEUR FROM utilisateur WHERE E_MAIL_UTILISATEUR = ?");
    $verifierUtilisateur->execute([$email]);
    $utilisateurExistant = $verifierUtilisateur->fetch(PDO::FETCH_ASSOC);

    if ($utilisateurExistant) {
        $message = "<div class='message-erreur'>Cet utilisateur est déjà inscrit!</div>";
    } else {
        // Attribution du rôle
        if ($role_text === "Employé") {
            $id_role = 1;
        } elseif ($role_text === "Gérant") {
            $id_role = 2;
        } else {
            $message = "<div class='message-erreur'>Rôle invalide.</div>";
        }

        try {
            // Insertion dans la base
            $insert = $pdo->prepare("
                INSERT INTO utilisateur 
                (NOM_UTILISATEUR, E_MAIL_UTILISATEUR, MOT_DE_PASSE_UTILISATEUR, ID_ROLE, TELEPHONE_UTILISATEUR, ADRESS_UTILISATEUR) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $insert->execute([$nom, $email, $hashedPassword, $id_role, $telephone, $adresse]);

            // Envoi d'email
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'gestion.boutique.app@gmail.com';
            $mail->Password = 'oipi bjfm cxmx rovl';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('gestion.boutique.app@gmail.com', 'Gestion Boutique');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Création de votre compte - Gestion Boutique";
            $mail->Body = "
                Bonjour $nom,<br><br>
                Votre compte a été créé avec succès.<br><br>
                <b>Email :</b> $email<br>
                <b>Mot de passe :</b> $plainPassword<br><br>
                Merci de vous connecter dès que possible.
            ";
            $mail->send();

            $message = "<div class='message-succes'>✅ Utilisateur ajouté avec succès en tant que <b>$role_text</b> !</div>";

        } catch (Exception $e) {
            $message = "<div class='message-erreur'>❌ Une erreur est survenue lors de l’ajout de l’employé.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Ajout Employé</title>
<link rel="stylesheet" href="../CSS/style_utilisateur.css">
</head>
<body>

          <div class="container">
     <?php if (!empty($message)) echo $message; ?>
          <form method="post">
             <h1>Employé</h1>

        <p>
           <label for="role">Rôle</label>
           <select name="role" id="role" required>
             <option value="Employé">Employé</option>
             <option value="Gérant">Gérant</option>
           </select>
        </p>

        <p>
            <label for="nom_et_prenom">Nom et Prénom</label>
            <input type="text" name="nom_et_prenom" id="nom_et_prenom" required>
        </p>

        <p>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </p>

        <p>
            <label for="telephone_utilisateur">Téléphone</label>
            <input type="text" name="telephone_utilisateur" id="telephone_utilisateur" required>
        </p>

        <p>
            <label for="adress_utilisateur">Adresse</label>
            <input type="text" name="adress_utilisateur" id="adress_utilisateur" required>
        </p>

            <div class="buttons">
                <input type="submit" value="Ajouter" class="button-ajouter">
                <input type="reset" value="Annuler" class="button-annuler">
           </div>
      </form>
</div>

      <script>
         // Sélectionne le message
    const message = document.querySelector('.message-succes');
    const messageErreur = document.querySelector('.message-erreur');

    if (message) {
        // Après 3 secondes (3000 ms), on fait disparaître le message
        setTimeout(() => {
            message.style.opacity = '0'; // fade out
            // Optionnel : le retirer du DOM après la transition
            setTimeout(() => {
                message.remove();
            }, 500); // correspond à la durée de transition CSS
        }, 3000); // temps d’affichage du message

    } else {
        // Après 3 secondes (3000 ms), on fait disparaître le message
        setTimeout(() => {
            messageErreur.style.opacity = '0'; // fade out
            // Optionnel : le retirer du DOM après la transition
            setTimeout(() => {
                messageErreur.remove();
            }, 500); // correspond à la durée de transition CSS
        }, 3000); // temps d’affichage du message
    }
      </script>
</body>
</html>

