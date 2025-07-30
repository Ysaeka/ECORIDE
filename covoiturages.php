    <?php
    session_start();

    require_once 'templates/header.php';
    require_once 'libs/bdd.php';
    require_once 'libs/notes_conducteur.php';

    $ville_depart = $_GET ['lieu_depart'] ?? '';
    $ville_arrivee = $_GET ['lieu_arrivee'] ?? '';
    $date = $_GET ['date'] ??'';

    $tri =$_GET['tri'] ?? '';
    $note_min = $_GET['note_min'] ?? '';
    $prix_max = $_GET['prix_max'] ?? '';
    $duree_max = $_GET['duree_max'] ?? '';

    $results = [];

    if ($ville_depart && $ville_arrivee && $date) {
        $recup_covoiturage = $bdd->prepare("SELECT c.*, u.first_name, u.last_name, u.photo FROM covoiturage c JOIN users u ON c.conducteur_id = u.users_id WHERE c.lieu_depart LIKE :lieu_depart AND c.lieu_arrivee LIKE :lieu_arrivee AND c.date_depart = :date AND c.nb_place > 0");
        $recup_covoiturage->execute(['lieu_depart' => "%$ville_depart%", 'lieu_arrivee' => "%$ville_arrivee%", 'date' => $date]);

        $results = $recup_covoiturage-> fetchALL(PDO::FETCH_ASSOC);

        if ($prix_max != '') {
            $results = array_filter($results, fn($trajet) => $trajet['prix_personne'] <= $prix_max);
        }

        if ($note_min != '') {
            $results = array_filter($results, function($trajet) use ($note_min, $bdd) {
            return getNoteConducteur($trajet['conducteur_id'], $bdd) >= $note_min;
            });
        }

        if ($duree_max != '') {
            $results = array_filter($results, function($trajet) use ($duree_max) {
                $depart = new DateTime($trajet['heure_depart']);
                $arrivee = new DateTime($trajet['heure_arrivee']);
                $dureeHeures = $depart->diff($arrivee)->h + ($depart->diff($arrivee)->i / 60);
                return $dureeHeures <= $duree_max;
            });
        }

        if ($tri == 'depart') {
            usort($results, fn($a, $b) => strtotime($a['heure_depart']) - strtotime($b['heure_depart']));
        } elseif ($tri == 'prix') {
            usort($results, fn($a, $b) => $a['prix_personne'] <=> $b['prix_personne']);
        } elseif ($tri == 'eco') {
            usort($results, fn($a, $b) => $b['trajet_Ecologique'] <=> $a['trajet_Ecologique']);
        }
    }
    ?>

<body>
        <section class = "containerResult">
                <div class = "triResult">
                    <form id="filtreForm">
                        <input type="hidden" name="lieu_depart" value="<?= htmlspecialchars($ville_depart) ?>">
                        <input type="hidden" name="lieu_arrivee" value="<?= htmlspecialchars($ville_arrivee) ?>">
                        <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">

                        <div class="filtreBlock">
                            <h3>Trier par :</h3>
                            <label><input type="radio" name="tri" value="depart"> Départ le + tôt</label><br>
                            <label><input type="radio" name="tri" value="prix"> Prix le + bas</label><br>
                            <label><input type="radio" name="tri" value="eco"> Voyage écologique</label>
                        </div>

                        <div class="filtreBlock">
                            <h4>Note du conducteur :</h4>
                            <label><input type="radio" name="note_min" value="5"> ⭐⭐⭐⭐⭐</label><br>
                            <label><input type="radio" name="note_min" value="4"> ⭐⭐⭐⭐ et +</label><br>
                            <label><input type="radio" name="note_min" value="3"> ⭐⭐⭐ et +</label>
                        </div><br>

                        <div class="filtreBlock">
                            <label>Prix max (€) :</label>
                            <input type="number" name="prix_max" min="0">
                        </div>

                        <div class="filtreBlock">
                            <label>Durée max du trajet (h) : </label>
                            <input type="number" name="duree_max" min="0">
                        </div>
                        <br>
                        <button type="submit" id="btnFiltre"> Appliquer </button>
                    </form>
                </div>

                <div class = "trajetResult">
                    <?php if(!empty($results)): ?>
                        <div class = trajet>
                            <h3> <?= htmlspecialchars(date('d/m/Y', strtotime($date))) ?>, <?= ucfirst(htmlspecialchars($ville_depart))?> -> <?= ucfirst(htmlspecialchars($ville_arrivee))?></h3>
                            <h3> <?= count($results)?> trajets disponibles </h3>
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
                                <a href = "reservation.php?id=<?= $trajet['covoiturage_id'] ?>">
                                    <span class = "detailsTrajet">
                                        <span class ="horaires"><?= $h_depart ?> o----- <?= $duree ?> ----o <?= $h_arrivee ?> </span>
                                        <span class = "placesDispo"> Places restantes : <?= $trajet['nb_place'] ?> </span>
                                        <span class ="prixTrajet"><?=number_format($trajet['prix_personne'], 2) ?> € </span>
                                    </span>
                                    <br><hr>
                                    <span class = "detailsTrajet">
                                        <span class = "chauffeur"> 
                                            <?= $trajet['photo'] ? "<img src='{$trajet['photo']}' class='photoChauffeur' alt='photo'>" : '' ?>
                                            <?= $nom_conducteur ?> <br> <?= afficherNote($trajet['conducteur_id']) ?> </span>
                                        <?php if ($trajet['trajet_Ecologique']) : ?>
                                            <span class =  "voyageEco"> 🌳 Voyage écologique </span>
                                        <?php endif; ?>
                                        <span class = "details"> Details ---> </span>
                                    </span>
                                </a>
                            </div>
                        <?php endforeach ?>
                    <?php elseif ($ville_depart || $ville_arrivee || $date): ?>
                        <p>Aucun trajet trouvé pour cette recherche.</p>
                    <?php else: ?>
                            <div class="searchBar">
                                <form method="GET" id="formSearch" action="covoiturages.php">
                                    <input type="text" name="lieu_depart" placeholder="Ville de départ">
                                    <input type="text" name="lieu_arrivee"placeholder="Ville d'arrivée">
                                    <input type="date" name="date"placeholder="Date ?">
                                    <button type ="submit">Rechercher</button>
                                </form>
                            </div>
                    <?php endif; ?>
                </div>
        </section>
    <?php
    require_once 'templates/footer.html'
    ?>
    <script src="asset/JS/btn_login.js"></script> 
</body>