<?php 
    require "../Config/config.php";
    $TELEPHONE_CLIENT = $_POST['editPhone']; 
    $NOM_CLIENT = $_POST['editName'];
    $MOT_DE_PASSE_CLIENT= trim($_POST['editPassword']);
    $hashedPassword = password_hash($MOT_DE_PASSE_CLIENT, PASSWORD_ARGON2I);
    $E_MAIL_CLIENT = $_POST['editEmail'];
    $ID_CLIENT = $_POST['id'];
   if (!empty($MOT_DE_PASSE_CLIENT)){
        $req = $pdo->prepare('UPDATE client
       SET TELEPHONE = :nvphone, 
     NOM_CLIENT = :nvname,
    MOT_DE_PASSE = :nvpassword 
    WHERE ID_CLIENT = :id'); 
        $success= $req->execute(array(
       'nvphone' => $TELEPHONE_CLIENT,
       'nvpassword' =>  $hashedPassword ,
       'id'     =>    $ID_CLIENT,
       'nvname' =>     $NOM_CLIENT
    ));
}else{
     $req = $pdo->prepare('UPDATE client
     SET TELEPHONE= :nvphone, 
      NOM_CLIENT = :nvname
     WHERE ID_CLIENT = :id');
     $success= $req->execute(array(
       'nvphone' => $TELEPHONE_CLIENT,
       'nvname' =>     $NOM_CLIENT,
        'id'        =>  $ID_CLIENT
    ));
}
    if ($success) {
    echo "Profil modifié avec succes!";
} else {
    echo "Echec de la mise a jour.";
}
?>