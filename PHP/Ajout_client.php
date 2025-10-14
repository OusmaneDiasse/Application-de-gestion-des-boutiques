<?php
   require "../Config/config.php";
    if ($_SERVER["REQUEST_METHOD"] === "POST"){ 
    $NOM_CLIENT = $_POST['nom'];
    $E_MAIL_CLIENT = $_POST['email'];
    $TELEPHONE= $_POST['telephone'];
    $MOT_DE_PASSE= trim($_POST['prive']);
    $hashedPassword = password_hash($MOT_DE_PASSE, PASSWORD_ARGON2I);
    // Verifier si email existe deja
    $reponse = $pdo->prepare("SELECT * FROM client WHERE E_MAIL_CLIENT = :email");
    $reponse->execute(['email' => $E_MAIL_CLIENT]);
    $sucesse = $reponse->fetch();
    if ($sucesse) {
        header("Location: Formulaire_client.php?successe=1");
    } else {
       $req = $pdo->prepare('INSERT INTO client(NOM_CLIENT,
     E_MAIL_CLIENT, MOT_DE_PASSE,TELEPHONE) VALUES(:nom, :email, :prive, :telephone)');
     $req->execute(array(
     'nom' => $NOM_CLIENT,
     'telephone' => $TELEPHONE,
     'email' => $E_MAIL_CLIENT,
     'prive' => $hashedPassword
     ));
        echo "✅ Client ajouté avec succès";
    }
  }
?>
