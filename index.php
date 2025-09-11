<body>
    <?php
        if (file_exists('BDD_ECORIDE.sql') || file_exists('avis_test.json')){
        header ("Location: set_up.php");
        }
        require_once 'config.php';
        require_once './libs/bdd.php';
        require_once './templates/header.php';
        require_once './templates/villes.php';

        $pages = [
            "covoiturages"      => "pages/covoiturages.php",
            "contact"           => "pages/contact.php",
            "inscription"       => "pages/inscription.php",
            "connexion"         => "pages/connexion.php",
            "profil"            => "pages/profil.php",
            "mes_trajets"       => "pages/mes_trajets.php",
            "proposer_trajet"   => "pages/proposer_trajet.php",
            "admin"             => "pages/acceuil_admin.php",
            "employe"           => "pages/acceuil_employe.php",
            "reservation"       => "pages/reservation.php",
            "creation_employe"  => "pages/creation_employe.php",
            "suspendre_compte"  => "pages/suspendre_compte.php",
            "modif_profil"      => "pages/modif_profil.php",

            "ajouter_vehicule"  => "script/ajouter_vehicule.php",
            "annuler_covoiturage"=>"script/annuler_covoiturage.php",
            "avis_trajet"       => "script/avis_trajet.php",
            "dashboard_data"    => "script/dashboard_data.php",
            "envoi_mail_contact"=> "script/envoi_mail_contact.php",
            "mail_annulation"   => "script/mail_annulation.php",
            "mail_avis"         => "script/mail_avis.php",
            "mail_reservation"  => "script/mail_reservation.php",
            "modif_voiture"     => "script/modif_voiture.php",
            "notes_conducteur"  => "script/notes_conducteur.php",
            "refuser_avis"      => "script/refuser_avis.php",
            "statut_covoiturage"=> "script/statut_covoiturage.php",
            "supprimer_voiture" => "script/supprimer_voiture.php",
            "update_type"       => "script/update_type.php",
            "valider_avis"      => "script/valider_avis.php",
            "deconnexion"       => "script/deconnexion.php",
            "enregistrer_trajet"=>"script/enregistrer_trajet.php",
            "upload_photo"      => "script/upload_photo.php",
            "participation_covoiturage" => "script/participation_covoiturage.php"
        ];
    ?>
    <main> 

    <?php
        if (!isset($_GET['page'])) {
    ?>

    <div class = "imgSlogan">
        <p>Solution économique pour les voyageurs soucieux de l’environnement.</p>
    </div>

    <div class="searchBar">
        <form method="GET" id="formSearch" action="index.php">
            <input type = "hidden" name="page" value="covoiturages">
            <input type="text" name="lieu_depart" placeholder="Ville de départ">
            <input type="text" name="lieu_arrivee"placeholder="Ville d'arrivée">
            <input type="date" name="date"placeholder="Date ?">
            <button type ="submit">Rechercher<i class="fa-solid fa-magnifying-glass fa-sm" style="color: #ffffff;"></i></button>
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
    <?php
        } else {
            $page = $_GET['page'];
            if (array_key_exists($page, $pages)) {
                include $pages[$page];
            } else {
                echo "<h2> Page introuvable</h2>";
            }
        }
    ?>
    </main>

    <?php
    require_once 'templates/footer.html'
    ?>

    <script src="asset/JS/btn_login.js"></script>
    <script src="asset/JS/menu.js" defer></script>

</body>

</html>
