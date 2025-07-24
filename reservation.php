<body>
    <?php
    session_start();
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

    $id = $_GET['id'] ?? null;

    if(!$id) {
        echo "<p> Trajet non trouvé </p>";
        exit();
    }

    $recupTrajet = $bdd->prepare("SELECT c.*, u.first_name, u.last_name, u.photo, m.libelle AS marque, v.modele, v.energie, p.animaux, p.fumeur FROM covoiturage c JOIN users u ON c.conducteur_id = u.users_id LEFT JOIN voiture v ON v.voiture_id = c.voiture_id LEFT JOIN marque m ON v.marque_id = m.marque_id LEFT JOIN preferences p ON p.pref_id = u.users_id WHERE c.covoiturage_id = :id");
    $recupTrajet->execute(['id' => $id]);
    $trajet = $recupTrajet->fetch(PDO::FETCH_ASSOC);

    if (!$trajet) {
        echo "<p>Ce trajet n'existe pas.</p>";
        exit;
    }

    $recupAvis = $bdd->prepare("SELECT a.note, a.commentaire, a.created_at, u.first_name FROM avis a JOIN users u ON a.reviewer_id = u.users_id WHERE a.reviewed_user_id = :id ORDER BY a.created_at DESC");
    $recupAvis->execute(['id' => $trajet['conducteur_id']]);
    $recupAvis->fetchAll(PDO::FETCH_ASSOC);

    $heure_depart = (new DateTime($trajet['heure_depart']))->format('H:i');
    $heure_arrivee = (new DateTime($trajet['heure_arrivee']))->format('H:i');
    ?>


    <body>
        <section class = "containerResa">
           
                <div class = "trajetResult">
                    <?php if(!empty($results)): ?>
                        <div class = trajet>
                            <h3> <?= htmlspecialchars(date('d/m/Y', strtotime($date))) ?>, <?= ucfirst(htmlspecialchars($ville_depart))?> -> <?= ucfirst(htmlspecialchars($ville_arrivee))?></h3>
                        </div>
                        
                        <?php foreach ($results as $trajet):
                            $heure_depart = (new DateTime($trajet['heure_depart']))->format('H:i');
                            $heure_arrivee = (new DateTime($trajet['heure_arrivee']))->format('H:i');
                            $duree = $heure_depart->diff($heure_arrivee)->format('%h h %i');
                            $nom_conducteur = ucfirst($trajet['first_name']) .' ' . strtoupper($trajet['last_name']);
                        ?>
                            <div class="linkResult">
                                    <span class = "detailsTrajet">
                                        <span class ="horaires"><?= $heure_depart ?> o----- <?= $duree ?> ----o <?= $heure_arrivee ?> </span>
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



