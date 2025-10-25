<?php
require_once "env.php";

try {
    $pdo = new PDO("mysql:host=$serveur;dbname=$base_de_donnees", $utilisateur, $motdepasse);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('<div class="message error">Erreur de connexion : ' . htmlspecialchars($e->getMessage()) . '</div>');
}

global $pdo;
//hachage du mot de  passe
// $motdepasse = "12345678";
//   $hachage = password_hash($motdepasse, PASSWORD_ARGON2ID);
//  echo $hachage;
// $motdepasse = "123456789";
//   $hachage = password_hash($motdepasse, PASSWORD_ARGON2ID);
//  echo $hachage
// $motdepasse = "1234567890";
//   $hachage = password_hash($motdepasse, PASSWORD_ARGON2ID);
//  echo $hachage
// $motdepasse = "1234567890A";
//   $hachage = password_hash($motdepasse, PASSWORD_ARGON2ID);
//  echo $hachage
// $motdepasse = "1234567890AB";
//   $hachage = password_hash($motdepasse, PASSWORD_ARGON2ID);
//  echo $hachage
//  $motdepasse = "1234567890ABCDEFGH";
// $hachage = password_hash($motdepasse, PASSWORD_ARGON2ID);
// echo $hachage
?>