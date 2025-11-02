<?php
  session_start();
    require_once '../Config/config.php';
 try{
 $email=$_POST['email']; //email saisi
 $password=$_POST['password']; //mot de passe saisi
 //Vérification si l'utilisateur est dans la table utilisateur
  $stmt = $pdo->prepare("SELECT  utilisateur.*, role.NOM_DU_ROLE FROM utilisateur JOIN role ON utilisateur.ID_ROLE=role.ID_ROLE WHERE E_MAIL_UTILISATEUR = :email LIMIT 1");
  $stmt->bindParam(':email', $email);
  $stmt->execute();
   $user = $stmt->fetch(PDO::FETCH_ASSOC);
   //Vérification si l'email est correct et si le mot de passe correspond
   if ($user && password_verify($password, $user['MOT_DE_PASSE_UTILISATEUR'])) {
      // Authentification réussie
      $_SESSION['id_utilisateur']= $user['ID_UTILISATEUR'];
      $_SESSION['email'] = $email;
      $_SESSION['role'] = $user['ID_ROLE'];
      // Redirection vers la page d'accueil du gérant ou de l'employé
      header("Location: accueil.php");
      exit();
   }else {
        header("Location: ../index.php?error=1");
    } 
   //Vérifier si l'utilisateur est dans la table client
    $stmt = $pdo->prepare("SELECT * FROM client WHERE E_MAIL_CLIENT = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
   $client = $stmt->fetch(PDO::FETCH_ASSOC);
   //Vérification si l'email est correct et si le mot de passe correspond
   if ($client && password_verify($password, $client['MOT_DE_PASSE'])) {
      // Authentification réussie
      $_SESSION['email'] = $email;
       $_SESSION['type'] = 'client';
        $_SESSION['id_client'] =$client['ID_CLIENT'];
      // Redirection vers la page d'accueil du client
      header("Location: accueilclient.php");
      exit();
   }else {
        header("Location: ../index.php?error=1");
    }
}catch (PDOException $e) {
      echo "Erreur de connexion à la base de données: " . $e->getMessage();
   }
?>
 
 



 
