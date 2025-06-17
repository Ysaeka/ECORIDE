<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ecoride</title>
  <link rel="stylesheet" href="asset/CSS/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Happy+Monkey&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>

<body>
  <?php
  include 'html/header.html'
  ?>

  <main> 
    <div class = "imgSlogan">
      <p>Solution économique pour les voyageurs soucieux de l’environnement.</p>
    </div>

    <div class="searchBar">
      <form method="GET" id="formSearch" action="resultat.php">
        <input type="text" name="depart" placeholder="Départ">
        <input type="text" name="arrivee"placeholder="Arrivée">
        <input type="date" name="date"placeholder="Date ?">
        <button type ="submit">Rechercher</button>
      </form>
    </div>

    <div class="presentation">
      <h2>Qui sommes-nous ?</h2>
      <p>"EcoRide" fraichement crée en France, souhaite proposer des solutions de covoiturage 100% écologique.<br>
          Notre objectif ! Réduire l'impact environnemental des déplacements en encourageant le covoiturage.</p>
    </div>

    <div class ="icones">
      <div class ="imgIcone">
        <img src = "asset/images/ri_leaf-fill.png">
        <img src="asset/images/tabler_hand-click.png">
        <img src="asset/images/pepicons-pop_euro-circle-off.png">
      </div>

      <div class ="accroche">
      <p>Voyagez en 100% électrique</p>
      <p>Trouvez votre covoiturage en 1 clic</p>
      <p>Aucune commissions pour les passagers</p>
      </div>
    </div>

   </main>
    <?php
    include 'html/footer.html'
    ?>

    <script src="asset/JS/btn_login.js"></script>

</body>

</html>
