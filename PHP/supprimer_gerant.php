<?php
require '../Config/config.php';
if (isset($_GET['id'])) {
    $ID_UTILISATEUR = $_GET['id'] ;

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