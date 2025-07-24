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
                <h2><?= ucfirst($trajet['lieu_depart']) ?> → <?= ucfirst($trajet['lieu_arrivee']) ?></h2>
                    <p>Date : <?= htmlspecialchars($trajet['date_depart']) ?> | <?= $heure_depart ?> → <?= $heure_arrivee ?></p>
                    <p>Places restantes : <?= $trajet['nb_place'] ?></p>
                    <p>Prix : <?= number_format($trajet['prix_personne'], 2) ?> €</p>

                <h3>Conducteur</h3>
                    <p><?= ucfirst($trajet['first_name']) . " " . strtoupper($trajet['last_name']) ?></p>
                    <?php if ($trajet['photo']) : ?>
                        <img src="<?= $trajet['photo'] ?>" width="60" alt="Conducteur">
                    <?php endif; ?>

                <h4>Avis</h4>
                    <?php if ($recupAvis): ?>
                        <?php foreach ($recupAvis as $avis): ?>
                            <p><strong><?= htmlspecialchars($avis['first_name']) ?> :</strong> 
                            <?= str_repeat("⭐", $avis['note']) ?> - <?= htmlspecialchars($a['commentaire']) ?> (<?= $avis['date'] ?>)</p>
                        <?php endforeach; ?>
                    <?php else: ?>
                            <p>Aucun avis pour ce conducteur.</p>
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
                <a href="covoiturage.php" class="btn-retour"> Retour à la liste </a>
                <a href="participation_covoiturage.php?id=<?= $trajet['covoiturage_id'] ?>" class="btn-reserver"> Participer à ce trajet</a>
            </div>
        </section>
    <script src="asset/JS/btn_login.js"></script> 
</body>



