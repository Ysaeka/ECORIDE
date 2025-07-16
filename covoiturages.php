<body>
    <?php
    session_start();

    require_once 'templates/header.html';
    require_once 'libs/bdd.php';

    if(!isset($_SESSION['users_id'])) {
        header("Location: connexion.php");
        exit;
    }


    ?>

    <section>
        <div class = "containerResult">
            <div class = "triResult">
                <h3> TRIER PAR : </h3>
                <h3> <a href = ""> Tout effacer </a>
            </div>

            <div class = "trajetResult">
                <div class = trajet>
                    <h3> Date, Ville de départ -> Ville d'arrivée </h3>
                    <h3> x trajets disponibles </h3>
                </div>
                <div class="linkResult">
                    <a href = "reservation.php">
                        <span class = "detailsTrajet">
                            <span class ="horaires"> 21:30 o----- 1h20 ----o 22:50 <br> Cannes Toulon </span>
                            <span class ="prixTrajet"> 8.99€ </span>
                        </span>
                        <hr>
                        <br>
                        <span class = "detailsTrajet">
                            <span class = "chauffeur"> GINETTE ----- * 4.7 </span>
                            <span class = "placesDispo"> Places dispo : 1 </span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php
    require_once 'templates/footer.html'
    ?>
    <script src="asset/JS/btn_login.js"></script> 
</body>