<?php
  session_start();
  session_start();
    require_once '../Config/config.php';
 try{
 $email=$_POST['email']; //email saisi
 $password=$_POST['password']; //mot de passe saisi
 //vérifier la longueur du mot de passe
   // if (strlen($password)<8) {
   //    die('Le mot de passe doit contenir au moins 8 caractères.');
   // }
//préparer la requéte sql
$req = $bdd->prepare('SELECT E_MAIL_UTILISATEUR,MOT_DE_PASSE_UTILISATEUR FROM UTILISATEUR WHERE E_MAIL_UTILISATEUR = :email LIMIT 1'); 
 //excute la requete qui a était préparer
$req->execute(['email' => $email]);
 // recupérer la premiére ligne sous forme de table
$resultat = $req->fetch(PDO::FETCH_ASSOC);
    if ($resultat) {
       if (password_verify($password, $resultat['MOT_DE_PASSE_UTILISATEUR'])) {
          echo 'Connexion réussiee.';
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
