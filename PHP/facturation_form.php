<?php
require_once '../Config/config.php';

$recupereProduit = $pdo->query("SELECT * FROM produit");
$produits = $recupereProduit->fetchAll(PDO::FETCH_ASSOC);

$recupereClient = $pdo->query("SELECT * FROM client");
$clients = $recupereClient->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Facturation</title>
  <link rel="stylesheet" href="../CSS/style_facturation_form.css">
</head>
<body>
           <div class="container">
  <form method="POST" action="../PHP/facturation.php">
            <h1>Facturation</h1>

<button type="button" id="ajouterProduit" class="button">+ Ajouter un produit</button>
    
  <div class="ligne-client">
   <div class="bloc-client">
    <label for="client">Client :</label>
    <input list="listeClients" id="client" name="client" placeholder="Rechercher un client...">
    <datalist id="listeClients">
    <?php foreach ($clients as $client): ?>
    <option data-id="<?= $client['ID_CLIENT'] ?>" data-telephone="<?= $client['TELEPHONE'] ?>" value="<?= htmlspecialchars($client['NOM_CLIENT']) ?>"></option>
    <?php endforeach; ?>
    </datalist>
    </div>
          <input type="hidden" name="id_client" id="id_client">

  <div class="bloc-tel">
   <label for="telephone">Téléphone :</label>
   <input type="text" id="telephone" name="telephone" placeholder="numéro de téléphone..." readonly>
  </div>

  <div class="bloc-nouveau-client">
   <input type="checkbox" id="nouveauClient">
   <label for="nouveauClient">Nouveau client</label>
   <a href="../Client/Ajout_client.php" id="ajouterClient" class="button" style="display: none;">Ajouter un client</a>
  </div>
</div>

<table>
 <thead>
  <tr>
    <th>Nom Produit</th>
    <th>Prix unitaire (FCFA)</th>
    <th>Quantité</th>
    <th>Montant total (FCFA)</th>
    <th>Date de vente</th>
    <th>Heure de vente</th>
  </tr>
 </thead>

<tbody>
  <tr>
    <td>
     <select name="id_produit[]" onchange="mettrePrix(this)" required>
      <option value="">Choisir un produit</option>
       <?php foreach ($produits as $p) { ?>
      <option value="<?= $p['ID_PRODUIT'] ?>"
        data-prix="<?= $p['PRIX'] ?>"
        data-stock="<?= $p['STOCK'] ?>">
        <?= htmlspecialchars($p['NOM_PRODUIT']) ?>
      </option>
       <?php } ?>
     </select>
    </td>

    <td><input type="text" name="prix_unitaire[]" class="prix_unitaire" readonly></td>
    <td><input type="number" name="quantite[]" class="quantite" min="1" oninput="calculerTotal(this)" required></td>
    <td><input type="text" name="montant_total[]" class="montant_total" readonly></td>
    <td><input type="text" name="date_vente[]" value="<?= date('Y-m-d') ?>" readonly></td>
    <td><input type="text" name="heure_vente[]" value="<?= date('H:i:s') ?>" readonly></td>
  </tr>
</tbody>
</table>

  <div class="total-facture">
    <label for="total_facture">Total facture (FCFA) :</label>
    <input type="text" id="total_facture" name="total_facture" readonly>
  </div>

  <div class="creance-container">
   <div class="creance">
     <input type="checkbox" id="creance" name="creance">
     <label for="creance">Ma créance</label>
   </div>

   <div class="montant-container" id="champMontantPaye" style="display: none;">
      <label for="montant_paye">Montant payé :</label>
      <input type="number" id="montant_paye" name="montant_paye" min="0">
   </div>

   <div class="enregistrer-container">
      <button type="submit" class="button">Enregistrer</button>
   </div>
  </div>
</form>
</div>

  <script>
    let produits = <?php echo json_encode($produits); ?>;
  </script>

  <script>
  
    let nomClient = document.getElementById("client");
    let nomTelephone = document.getElementById("telephone");
    let idClient = document.getElementById("id_client");

    nomClient.addEventListener("input", function() {
      let value = this.value.trim();
      let option = Array.from(document.querySelectorAll("#listeClients option")).find(opt => opt.value === value);

      if (option) {
        nomTelephone.value = option.dataset.telephone;
        idClient.value = option.dataset.id;
      } else {
        nomTelephone.value = "";
        idClient.value = "";
      }
    });

    let checkboxNouveauClient = document.getElementById("nouveauClient");
    let lienAjoutClient = document.getElementById("ajouterClient");

    checkboxNouveauClient.addEventListener("change", function() {
      lienAjoutClient.style.display = this.checked ? "inline-block" : "none";
    });

    let boutonAjouter = document.getElementById("ajouterProduit");
    boutonAjouter.addEventListener("click", function() {
      ajouterLigne();
    });

    function ajouterLigne() {
      let tbody = document.querySelector("tbody");
      let nouvelleLigne = document.createElement("tr");
      let optionsHTML = '<option value="">Choisir un produit</option>';
      produits.forEach(p => {
      optionsHTML += `<option value="${p.ID_PRODUIT}" data-prix="${p.PRIX}" data-stock="${p.STOCK}">
      ${p.NOM_PRODUIT}
      </option>`;
   });

      nouvelleLigne.innerHTML = `
        <td>
         <select name="id_produit[]" onchange="mettrePrix(this)" required>
         ${optionsHTML}
         </select>
        </td>
        <td><input type="text" name="prix_unitaire[]" class="prix_unitaire" readonly></td>
        <td><input type="number" name="quantite[]" class="quantite" oninput="calculerTotal(this)" min="1" required></td>
        <td><input type="text" name="montant_total[]" class="montant_total" readonly></td>
        <td><input type="text" name="date_vente[]" value="<?= date('Y-m-d') ?>" readonly></td>
        <td><input type="text" name="heure_vente[]" value="<?= date('H:i:s') ?>" readonly></td>
      `;
      tbody.appendChild(nouvelleLigne);
    }

    function mettrePrix(select) {
      let prixInput = select.parentElement.nextElementSibling.querySelector(".prix_unitaire");
      let selectedOption = select.options[select.selectedIndex];
      let prix = selectedOption.dataset.prix || 0;
      prixInput.value = prix;
      calculerTotal(select);
    }

    function calculerTotal(element) {
      let ligne = element.closest("tr");
      let prix = parseFloat(ligne.querySelector(".prix_unitaire").value) || 0;
      let quantite = parseInt(ligne.querySelector(".quantite").value) || 0;
      let total = prix * quantite;
      ligne.querySelector(".montant_total").value = total;

      calculerTotalFacture();
    }

    function calculerTotalFacture() {
      let totaux = document.querySelectorAll(".montant_total");
      let somme = 0;
      totaux.forEach(input => {
        somme += parseFloat(input.value) || 0;
      });
      document.getElementById("total_facture").value = somme;
    }

    let creanceCheckbox = document.getElementById("creance");
    let champMontantPaye = document.getElementById("champMontantPaye");
    creanceCheckbox.addEventListener("change", function() {
    champMontantPaye.style.display = this.checked ? "inline-block" : "none";
});

</script>
</body>
</html>