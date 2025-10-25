<?php
require '../Config/config.php';
if (isset($_GET['id'])) {
<<<<<<< HEAD
    $ID_UTILISATEUR = $_GET['id'];
=======
    $ID_UTILISATEUR = $_GET['id'] ;
>>>>>>> 045bf5958e3956dc1644d6ac964c3cfd43ac0f0d

    $req = $pdo->prepare("DELETE FROM utilisateur WHERE ID_UTILISATEUR = :id");
    $success = $req->execute(['id' => $ID_UTILISATEUR]);

    if ($success) {
        header("Location: tableau.php?success=1");
    } else {
        header("Location: tableau.php?error=1");
    }
    exit();
}
?>