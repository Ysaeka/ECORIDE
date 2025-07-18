<body>
  <?php
  require_once 'templates/header.html';
  require_once 'libs/bdd.php';
  require_once 'templates/villes.php';
  ?>

  <main> 
    <div class = "imgSlogan">
        <p>Solution économique pour les voyageurs soucieux de l’environnement.</p>
    </div>

    <div class="searchBar">
      <form method="GET" id="formSearch" action="covoiturage.php">
        <input type="text" name="lieu_depart" placeholder="Ville de départ">
        <input type="text" name="lieu_arrivee"placeholder="Ville d'arrivée">
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
    require_once 'templates/footer.html'
    ?>

    <script src="asset/JS/btn_login.js"></script>

</body>

</html>
