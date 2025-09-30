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
?>