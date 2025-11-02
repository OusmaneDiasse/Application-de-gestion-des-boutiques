
<?php
require 'Config/config.php';
  $sms = "";
if (isset($_GET['error']) && $_GET['error'] == 1) {
    $sms = '<div class="alerterror"> Email ou mot de passe incorrecte</div>';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>                 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de connexion</title>
    <link rel="stylesheet" href="CSS/connexion.css">
</head>
<body>
     <div class="container">
     <form action="../PHP/connexion.php" method="post">
          <h1>Bienvenue</h1>
           <?php echo $sms ; ?>
       <p>Connectez-vous à votre compte pour accéder au tableau de bord</p> 
        <label for="email">Adresse mail:</label>
        <input type="email" name="email" id="email" placeholder="Entrez votre adresse email" required>
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" id="password" placeholder="Entrez votre mot de passe" minlength="8"required>
        <a href="PHP/mot_de_pass_oublie.php" class="oublié">Mot de passe oublié ? </a>
       <!-- <div>
       <input type="checkbox" name="checkbox" id="checkbox">
        <label for="checkbox">Se souvenir de moi</label></div>  -->
        <div class="bouton">
        <input type="submit" value="Se connecter" class="connexion">
        
         <button class="annuler">Annuler</button></div> 
         <!--  <p >Vous n'avez pas de compte ?<a href="" class="lien">Créer mon compte</a></p> -->
    </form>  
    <div class="gestion">
        <h1>Gestion des boutiques</h1>
        <p>Système complet pour suivre vos ventes,<br>
        gérer vos produits, enregistrer vos dettes<br>
        et paiements clients, et analyser vos <br>
        performances commerciales en temps réel.</p>
        <img src="IMG/image.jpg" alt="image" >
    </div>
    </div>
    <script>
    // Sélectionne le sms
    const sms = document.querySelector('.alerterror');
    if (sms) {
        // Après 3 secondes (3000 ms), on fait disparaître le sms
        setTimeout(() => {
            sms.style.opacity = '0'; // fade out
            // Optionnel : le retirer du DOM après la transition
            setTimeout(() => {
                sms.remove();
            }, 500); // correspond à la durée de transition CSS
        }, 3000); // temps d’affichage du sms
    }
</script>
</body>
</html>
