<?php
require_once '../Config/config.php';

  $nom = $_POST['nom_et_prenom'];
  $email = $_POST['email'];
  $password = $_POST['mot_de_passe'];
  $role_text = $_POST['role'];
  $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

  $verifierUtilisateur = $pdo->prepare("SELECT MOT_DE_PASSE_UTILISATEUR FROM utilisateur WHERE E_MAIL_UTILISATEUR = ?");
  $verifierUtilisateur->execute([$email]);
  $utilisateurExistant= $verifierUtilisateur->fetch(PDO::FETCH_ASSOC);

 if ($utilisateurExistant) {
    if (!password_verify($password, $utilisateurExistant['MOT_DE_PASSE_UTILISATEUR'])) {
     echo "Erreur : cet utilisateur est déjà inscrit mais avec un autre mot de passe ";
    } else {
     echo "Cet utilisateur est déjà inscrit ";
    }
}

 if ($role_text === "Propriétaire") {
    $id_role = 1;
} elseif ($role_text === "Gérant") {
    $id_role = 2;
} else {
    die("Erreur : rôle invalide ");
}
 
 try {
  $insertutilisateur = $pdo->prepare("
    INSERT INTO utilisateur 
    (NOM_UTILISATEUR, E_MAIL_UTILISATEUR, MOT_DE_PASSE_UTILISATEUR, ID_ROLE) 
    VALUES (?, ?, ?, ?)
 ");
  $insertutilisateur->execute([$nom, $email, $hashedPassword, $id_role]); 
   echo "Employé ajouté avec succès en tant que <b>$role_text</b> ";
} catch (Exception $e) {
   echo "Erreur : " . $e->getMessage();
}
?>