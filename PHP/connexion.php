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
 //Vérification si l'utilisateur est dans la table utilisateur
  $stmt = $pdo->prepare("SELECT E_MAIL_UTILISATEUR , MOT_DE_PASSE_UTILISATEUR FROM utilisateur WHERE E_MAIL_UTILISATEUR = :email LIMIT 1");
  $stmt->bindParam(':email', $email);
  $stmt->execute();
   $user = $stmt->fetch(PDO::FETCH_ASSOC);
   //Vérification si l'email est correct et si le mot de passe correspond
   if ($user && password_verify($password, $user['MOT_DE_PASSE_UTILISATEUR'])) {
      // Authentification réussie
      $_SESSION['email'] = $email;
      // Redirection vers la page d'accueil du gérant ou de l'employé
      header("Location: accueil.php");
      exit();
   } 
   //Vérifier si l'utilisateur est dans la table client
    $stmt = $pdo->prepare("SELECT E_MAIL_CLIENT , MOT_DE_PASSE FROM client WHERE E_MAIL_CLIENT = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
   $client = $stmt->fetch(PDO::FETCH_ASSOC);
   //Vérification si l'email est correct et si le mot de passe correspond
   if ($client && password_verify($password, $client['MOT_DE_PASSE'])) {
      // Authentification réussie
      $_SESSION['email'] = $email;
      // Redirection vers la page d'accueil du client
      header("Location: accueilclient.php");
      exit();
   } 
      // Si l'authentification échoue
   echo "Email ou mot de passe incorrect.";
}catch (PDOException $e) {
      echo "Erreur de connexion à la base de données: " . $e->getMessage();
   }
?>
 
 



 
