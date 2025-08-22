<body>
    <?php
    session_start();
    require_once 'templates/header.php';
    require_once 'libs/bdd.php';
    require_once 'libs/notes_conducteur.php';

    $id = $_GET['id'] ?? null;

    if(!$id) {
        echo "<p> Trajet non trouvé </p>";
        exit();
    }

    $recupTrajet = $bdd->prepare("SELECT c.*, u.first_name, u.last_name, u.photo, m.libelle AS marque, v.modele, v.energie, p.animaux, p.fumeur FROM covoiturage c JOIN users u ON c.conducteur_id = u.users_id LEFT JOIN voiture v ON v.voiture_id = c.voiture_id LEFT JOIN marque m ON v.marque_id = m.marque_id LEFT JOIN preferences p ON p.pref_id = u.users_id WHERE c.covoiturage_id = :id");
    $recupTrajet->execute(['id' => $id]);
    $trajet = $recupTrajet->fetch(PDO::FETCH_ASSOC);

    if (isset($_GET['erreur']) && $_GET['erreur'] === 'credit_insuffisant') {
    echo "<script>alert('Vous n\\'avez pas assez de crédits pour effectuer cette réservation.');</script>";
    }
    
    if (!$trajet) {
        echo "<p>Ce trajet n'existe pas.</p>";
        exit;
    }

    $recupAvis = $bdd->prepare("SELECT a.note, a.commentaire, a.created_at, u.first_name FROM avis a JOIN users u ON a.reviewer_id = u.users_id WHERE a.reviewed_user_id = :id ORDER BY a.created_at DESC");
    $recupAvis->execute(['id' => $trajet['conducteur_id']]);
    $avisConducteur = $recupAvis->fetchAll(PDO::FETCH_ASSOC);

    $heure_depart = (new DateTime($trajet['heure_depart']))->format('H:i');
    $heure_arrivee = (new DateTime($trajet['heure_arrivee']))->format('H:i');
    $date_depart = (new DateTime ($trajet['date_depart']))->format('d/m/Y');
    ?>

        <section class = "containerResa">
            <div class = "resaResult">
                <h2>Réservation du trajet : <?= ucfirst($trajet['lieu_depart']) ?> → <?= ucfirst($trajet['lieu_arrivee']) ?></h2>
                    <span class = "detailsTrajet">
                        <p>Date : <?= htmlspecialchars($date_depart)?> | <?= $heure_depart ?> → <?= $heure_arrivee ?></p>
                        <p>Places restantes : <?= $trajet['nb_place'] ?></p>
                        <p>Prix par personne : <?= number_format($trajet['prix_personne'], 2) ?> €</p>
                    </span>

                <h3>Conducteur</h3>
                    <span class = "chauffeur">
                        <?php if ($trajet['photo']) : ?>
                            <img class="photoChauffeur" src="<?= $trajet['photo'] ?>" width="60" alt="Conducteur">
                        <?php endif; ?>
                        <?= ucfirst($trajet['first_name']) . " " . strtoupper($trajet['last_name']) ?>
                    </span>

                <h4>Avis reçus :</h4>
                    <?php if ($avisConducteur): ?>
                        <?php foreach ($avisConducteur as $avis): ?>
                            <p><strong><?= htmlspecialchars($avis['first_name']) ?> :</strong> 
                            <?= str_repeat("⭐", $avis['note']) ?> - <?= htmlspecialchars($avis['commentaire']) ?> (<?= $avis['created_at'] ?>)</p>
                        <?php endforeach; ?>
                    <?php else: ?>
                            <p>Aucun avis pour ce conducteur</p>
                    <?php endif; ?>

                <h4>Véhicule</h4>
                    <p><?= $trajet['marque'] ?> <?= $trajet['modele'] ?> (<?= $trajet['energie'] ?>)</p>

                <h4>Préférences conducteur</h4>
                    <ul>
                        <li>Animaux : <?= $trajet['animaux'] ? "Oui" : "Non" ?></li>
                        <li>Fumeur : <?= $trajet['fumeur'] ? "Oui" : "Non" ?></li>
                    </ul>
            </div>

            <div class="btnActions">
                <a href="covoiturages.php" class="btnRetour"> Retour à la liste </a>

                <?php if($trajet['nb_place'] > 0): ?>
                    <form method="POST" action="participation_covoiturage.php" onsubmit="return confirmerReservation();">
                        <input type="hidden" name="covoiturage_id" value="<?= $trajet['covoiturage_id'] ?>">
                        <button type="submit" class="btnReserver"> Participer à ce trajet</button>
                    </form>
                <?php else: ?>
                    <button class="btnReserver" disabled> Complet </button>
                <?php endif; ?>
            </div>
        </section>
    <script src="asset/JS/btn_login.js"></script> 
    <script>
        function confirmerReservation(){
            return confirm("Vous serez débité de <?=number_format($trajet['prix_personne'], 2)?> € pour participer à ce covoituragen, voulez-vous continuer ? Si oui vous recevrez un mail avec les informations du covoiturage.");
        }
    </script>
    
    <?php
    require_once 'templates/footer.html'
    ?>
</body>



