<?php
require_once __DIR__ . '/libs/bdd.php';
require_once __DIR__ . '/templates/header.php';
/* Depuis son espace, l’administrateur doit pouvoir concevoir les comptes des employés et
visionner deux graphiques :

 Un graphique affichant le nombre de covoiturage par jour
 Un graphique affichant combien la plateforme gagne de crédit en fonction des jours
Il doit être également visible, le nombre total de crédit gagné par la plateforme, puis, être
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
            <h2> Nombres de covoiturage par jour </h2>
                <canvas id="chartCovoit"></canvas>
            <h2> Revenus de la plateforme par jour </h2>
                <canvas id="chartRevenus"></canvas>
        </div>
    </div>

<script src ="asset/JS/tableau_bord.js" defer></script>
</body>

