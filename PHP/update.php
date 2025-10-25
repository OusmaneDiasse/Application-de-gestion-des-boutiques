<?php 
    require "../Config/config.php";
    $TELEPHONE_UTILISATEUR = $_POST['editPhone']; 
    $ADRESS_UTILISATEUR = $_POST['editAddress'];
    $NOM_UTILISATEUR = $_POST['editName'];
    $MOT_DE_PASSE_UTILISATEUR= trim($_POST['editPassword']);
    $hashedPassword = password_hash($MOT_DE_PASSE_UTILISATEUR, PASSWORD_ARGON2I);
    $E_MAIL_UTILISATEUR = $_POST['editEmail'];
    $ID_UTILISATEUR = $_POST['id'];
   if (!empty($MOT_DE_PASSE_UTILISATEUR)){
        $req = $pdo->prepare('UPDATE utilisateur
       SET TELEPHONE_UTILISATEUR = :nvphone, 
     NOM_UTILISATEUR = :nvname,
     ADRESS_UTILISATEUR   = :nvaddress,
    MOT_DE_PASSE_UTILISATEUR = :nvpassword 
    WHERE ID_UTILISATEUR = :id'); 
        $success= $req->execute(array(
       'nvphone' => $TELEPHONE_UTILISATEUR,
       'nvaddress' => $ADRESS_UTILISATEUR,
       'nvpassword' =>  $hashedPassword ,
       'id'     =>    $ID_UTILISATEUR,
       'nvname' =>     $NOM_UTILISATEUR
    ));
}else{
     $req = $pdo->prepare('UPDATE utilisateur
     SET TELEPHONE_UTILISATEUR = :nvphone, 
      NOM_UTILISATEUR = :nvname,
     ADRESS_UTILISATEUR   = :nvaddress
     WHERE ID_UTILISATEUR = :id');
     $success= $req->execute(array(
       'nvphone' => $TELEPHONE_UTILISATEUR,
       'nvaddress' => $ADRESS_UTILISATEUR,
       'nvname' =>     $NOM_UTILISATEUR,
        'id'        =>  $ID_UTILISATEUR
    ));
}
    if ($success) {
    echo "Profil modifié avec succes!";
} else {
    echo "Echec de la mise a jour.";
}
?>