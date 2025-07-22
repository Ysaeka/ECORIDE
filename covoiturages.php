    <?php
    session_start();

    require_once 'templates/header.html';
    require_once 'libs/bdd.php';

    $ville_depart = $_GET ['lieu_depart'] ?? '';
    $ville_arrivee = $_GET ['lieu_arrivee'] ?? '';
    $date = $_GET ['date_depart'] ??'';

    $result = [];

    if ($ville_depart && $ville_arrivee && $date) {
        $recup_covoiturage = $bdd->prepare("SELECT c.*, u.first_name, u.last_name, u.photo FROM covoiturage c JOIN users u ON c.conducteur_id = u.users_id WHERE c.lieu_depart LIKE :lieu_depart AND c.lieu_arrivee LIKE :lieu_arrivee AND c.date_depart = :date");
        $recup_covoiturage->execute(['lieu_depart' => "%$ville_depart", 'lieu_arrivee' => "%$ville_arrivee", 'date' => $date]);

        $result = $recup_covoiturage-> fetchALL(PDO::FETCH_ASSOC);
    }
    ?>

<body>
        <section class = "containerResult">
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
                                <span class ="horaires"> 21:30 o----- 1h20 ----o 22:50</span>
                                <span class = "placesDispo"> Places restantes : 1 </span>
                                <span class ="prixTrajet"> 8.99€ </span>
                            </span>
                            <hr>
                            <br>
                            <span class = "detailsTrajet">
                                <span class = "chauffeur"> Photo/ GINETTE ----- * 4.7 </span>
                                <span class =  "voyageEco"> Voyage écologique </span>
                                <span class = "details"> Details ---> </span>
                            </span>
                        </a>
                    </div>
                </div>
        </section>
    
    
    <?php
    require_once 'templates/footer.html'
    ?>
    <script src="asset/JS/btn_login.js"></script> 
</body>