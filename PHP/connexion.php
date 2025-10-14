<?php
  session_start();
    require_once '../Config/config.php';
 try{
 $email=$_POST['email']; //email saisi
 $password=$_POST['password']; //mot de passe saisi
 //vérifier si la longueur du mot de passe est supérieur ou égale à 8
 if (strlen($password) < 8) {
    echo 'Le mot de passe doit contenir au moins 8 caractères.';
    exit();
 }
 //Vérifier si l'email et le mot de passe sont enregistrés dans la table client 
 $req = $pdo->prepare('SELECT E_MAIL_CLIENT,MOT_DE_PASSE FROM client WHERE E_MAIL_CLIENT = :email LIMIT 1');
 $req->execute(['email' => $email]);
  $client = $req->fetch(PDO::FETCH_ASSOC);
  if ($client && $client["E_MAIL_CLIENT"]) {
    if (password_verify($password, $client['MOT_DE_PASSE'])) {
        echo 'Connexion réussie. Bienvenue !';
        // Enregistrer les infos dans la session
         $_SESSION['email'] = $client['E_MAIL_CLIENT'];
         $_SESSION['id_role'] = 3; // Rôle client
      // Redirection vers la page d'accueil
      header('Location: accueilclient.php'); // Redirection vers la page d'accueil pour le client
      exit();
    } else {
        echo 'Mot de passe incorrect.';
    }
    
  }
  else{
    echo "L'email n'est pas enregistré. Veuillez vous inscrire.";
  }
   exit();
  //Vérifier si l'email et le mot de passe sont enregistrés dans la table utilisateur
$req = $pdo->prepare('SELECT E_MAIL_UTILISATEUR,MOT_DE_PASSE_UTILISATEUR FROM utilisateur WHERE E_MAIL_UTILISATEUR = :email LIMIT 1'); 
 //excute la requete qui a était préparer
$req->execute(['email' => $email]);
 // recupérer la premiére ligne sous forme de table
$resultat = $req->fetch(PDO::FETCH_ASSOC);

    if ($resultat["E_MAIL_UTILISATEUR"]) {
      if (password_verify($password, $resultat['MOT_DE_PASSE_UTILISATEUR'])) {
           echo 'Connexion réussie. Bienvenue !';
           // Enregistrer les infos dans la session
            $_SESSION['email'] = $resultat['E_MAIL_UTILISATEUR'];
            $_SESSION['id_role'] = $resultat['ID_ROLE'];
                header('Location: accueil.php'); // Redirection vers la page d'accueil pour le client
                exit();
       } else {
           echo 'Mot de passe incorrect.';
       }
    } else {
        echo "L'email n'est pas enregistré. Veuillez vous inscrire.";
    }
 } catch (Exception $e) {
     echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
?> 
