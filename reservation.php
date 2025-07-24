<body>
    <?php
    require_once 'templates/header.html';
    require_once 'libs/bdd.php';
    /* US 5 : vue détaillée d’un covoiturage
    Utilisateur concerné : Visiteur, Utilisateur
    Un visiteur, peut au clic sur le bouton « détail » d’un covoiturage, accéder à une page web qui
    détailles les éléments du voyage. Il doit pouvoir visionner tous les éléments présents dans la
    page précédente, mais, également :
     Les avis du conducteur
     Le modèle ainsi que la marque de véhicule (également l’énergie utilisé)
     Visionner les préférences du conducteur */
    $ville_depart = $_SESSION ['lieu_depart'] ?? '';
    $ville_arrivee = $_GET ['lieu_arrivee'] ?? '';
    $date = $_GET ['date'] ??'';

    $results = [];

    if ($ville_depart && $ville_arrivee && $date) {
        $recup_covoiturage = $bdd->prepare("SELECT c.*, u.first_name, u.last_name, u.photo FROM covoiturage c JOIN users u ON c.conducteur_id = u.users_id WHERE c.lieu_depart LIKE :lieu_depart AND c.lieu_arrivee LIKE :lieu_arrivee AND c.date_depart = :date AND c.nb_place > 0");
        $recup_covoiturage->execute(['lieu_depart' => "%$ville_depart%", 'lieu_arrivee' => "%$ville_arrivee%", 'date' => $date]);

        $results = $recup_covoiturage-> fetchALL(PDO::FETCH_ASSOC);
    }
    ?>


    <body>
        <section class = "containerResa">
           
                <div class = "trajetResult">
                    <?php if(!empty($results)): ?>
                        <div class = trajet>
                            <h3> <?= htmlspecialchars(date('d/m/Y', strtotime($date))) ?>, <?= ucfirst(htmlspecialchars($ville_depart))?> -> <?= ucfirst(htmlspecialchars($ville_arrivee))?></h3>
                        </div>
                        
                        <?php foreach ($results as $trajet):
                            $heure_depart = new DateTime($trajet['heure_depart']);
                            $heure_arrivee = new DateTime($trajet['heure_arrivee']);
                            $duree = $heure_depart->diff($heure_arrivee)->format('%h h %i');
                            $h_depart =$heure_depart->format('H:i');
                            $h_arrivee =$heure_arrivee->format('H:i');
                            $nom_conducteur = ucfirst($trajet['first_name']) .' ' . strtoupper($trajet['last_name']);
                        ?>
                            <div class="linkResult">
                                    <span class = "detailsTrajet">
                                        <span class ="horaires"><?= $h_depart ?> o----- <?= $duree ?> ----o <?= $h_arrivee ?> </span>
                                        <span class = "placesDispo"> Places restantes : <?= $trajet['nb_place'] ?> </span>
                                        <span class ="prixTrajet"><?=number_format($trajet['prix_personne'], 2) ?> € </span>
                                    </span>
                                    <br><hr><br>
                                    <span class = "detailsTrajet">
                                        <span class = "chauffeur"> 
                                            <?= $trajet['photo'] ? "<img src='{$trajet['photo']}' alt='photo' width='30'>" : '' ?>
                                            <?= $nom_conducteur ?> ----- * 4.7 </span>
                                        <?php if ($trajet['trajet_Ecologique']) : ?>
                                            <span class =  "voyageEco"> 🌳 Voyage écologique </span>
                                        <?php endif; ?>
                                        <span class = "details"> Details ---> </span>
                                        </span>
                            </div>
                        <?php endforeach ?>
                    <?php endif; ?>
                </div>
        </section>



    <script src="asset/JS/btn_login.js"></script> 
</body>



