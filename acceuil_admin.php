<?php
require_once __DIR__ . '/libs/bdd.php';
require_once __DIR__ . '/templates/header.php';
/* Depuis son espace, l’administrateur doit pouvoir concevoir les comptes des employés:
capable de suspendre un compte, aussi bien utilisateur qu’employé */
?>

<body>
    <div class= "containerCard">
        <div class="cards">
            <div class= "card" id="cardUsers"> Utilisateurs <br><strong>...</strong></div>
            <div class="card" id="cardCovoits"> Covoiturage terminés <br><strong>...</strong></div>
            <div class="card" id="cardRevenus"> Revenus <br><strong>...</strong></div>
        </div>

        <div class="charts">
            <div class="chartCvoit">
                <h2> Nombres de covoiturage par jour </h2>
                    <canvas id="chartCovoit"></canvas>
            </div>
            <div class="charRevenus">
                <h2> Revenus de la plateforme par jour </h2>
                    <canvas id="chartRevenus"></canvas>
            </div>
        </div>
        <div class="gestionUsers">
            <h2> Gestion des utilisateurs </h2>
            <ul>
                <li> <a href=""> Créer un compte employé </a> </li>
                <li> <a href=""> Suspendre un compte </a> </li>
            </ul>
        </div>
    </div>

    <script src="asset/JS/btn_login.js"></script>
    <script src ="asset/JS/tableau_bord.js" defer></script>
   <?php
    require_once 'templates/footer.html'
    ?>
</body>

