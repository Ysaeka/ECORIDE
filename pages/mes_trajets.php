<?php
require_once __DIR__ . '/../libs/auth_users.php';
require_once __DIR__ . '/../libs/bdd.php';
require_once __DIR__ . '/../templates/header.php';

$users_id = $_SESSION['users_id'];

$user_trajets = [];
try {
    $recuptrajet = $bdd->prepare("SELECT c.covoiturage_id, c.date_depart, c.heure_depart, c.lieu_depart, c.lieu_arrivee, c.nb_place, c.statut FROM covoiturage c 
                           JOIN voiture v ON c.voiture_id = v.voiture_id WHERE c.conducteur_id = ? ORDER BY c.date_depart DESC, c.heure_depart DESC");
    $recuptrajet->execute([$users_id]);
    $user_trajets = $recuptrajet->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e){
    echo "Erreur lors du chargement des données" .$e->getMessage();
}

$user_reservations = [];
try {
    $recupResa = $bdd->prepare("SELECT r.reservation_id, c.covoiturage_id, c.date_depart, c.heure_depart, c.lieu_depart, c.lieu_arrivee, c.prix_personne, c.statut, u.users_id AS conducteur_id, u.first_name, u.last_name, a.avis_id FROM reservation r JOIN covoiturage c ON r.covoiturage_id = c.covoiturage_id 
        JOIN users u ON c.conducteur_id = u.users_id LEFT JOIN avis a ON a.covoiturage_id = c.covoiturage_id AND reviewer_id = r.passager_id WHERE r.passager_id = ? ORDER BY c.date_depart DESC, c.heure_depart DESC");
    $recupResa->execute([$users_id]);
    $user_reservations = $recupResa->fetchAll(PDO::FETCH_ASSOC);   
} catch (PDOException $e){
    echo "Erreur lors du chargement des réservations : " .$e->getMessage();
}
?>
<body>
    <main>
        <section class="mesTrajets">
            <div class = "containerTrajet">
                <div class = "trajetResult">
                    <h2 class = titreH2> <i class="fa-solid fa-car fa-sm" style="color: #148b4b;"></i> Mes Trajets proposés </h2>
                    <hr>
                    <div class = "trajet">
                        <h3> <?=count($user_trajets); ?> trajets </h3>
                    </div>
                    <div class = "listeTrajets">
                        <?php if(empty($user_trajets)) : ?>
                            <p> Vous n'avez pas encore proposé de trajet. </p>
                            <p><a href="index.php?page=proposer_trajet"><i class="fa-solid fa-plus fa-sm" style="color: #ffffff;"></i> Proposer un nouveau trajet </a></p>
                        <?php else: ?>
                            <?php foreach ($user_trajets as $trajet) : ?>
                                <div class="historique">
                                    <span class = "detailsTrajet">
                                            <?php 
                                                $date = new DateTime($trajet['date_depart']);
                                                $heure = new DateTime($trajet['heure_depart']);
                                            ?>
                                            <span class ="date"> <?= $date->format('d/m/Y')?></span>
                                            <span class ="lieu depart_arrivee"><i class="fa-solid fa-car fa-lg" style="color: #148b4b;"></i> <?= ($trajet['lieu_depart'])?> ---> <?=($trajet ['lieu_arrivee'])?></span>
                                            <span><strong>Places restantes :</strong> <?= $trajet['nb_place'] ?> <i class="fa-solid fa-user fa-sm" style="color: #148b4b;"></i></span>

                                        <?php if ($trajet['statut'] === 'terminé') : ?>
                                            <span class="statutTermine"> <i class="fa-solid fa-check fa-lg" style="color: #148b4b;"></i> Trajet terminé </span>
                                        <?php elseif ($trajet['statut'] === 'annulé') : ?>
                                            <span class="statutAnnule"> <i class="fa-solid fa-ban" style="color: #cc1919;"></i> Trajet annulé </span>
                                        <?php else: ?>
                                            <form method="POST" action="index.php?page=statut_covoiturage" class="formStatut">
                                                <input type="hidden" name="trajet_id" value="<?= $trajet['covoiturage_id'] ?>">
                                                <?php
                                                    $prochaine_action = 'demarrer';
                                                    if ($trajet['statut'] === 'en_cours') {
                                                        $prochaine_action = 'terminer';
                                                    }
                                                ?>
                                                <input type="hidden" name="action" value="<?= $prochaine_action ?>">
                                                <label class="switch">
                                                    <input type="checkbox" onchange="this.form.submit()" <?= $trajet['statut'] === 'en_cours' ? 'checked' : '' ?>>
                                                    <span class="slider"></span>
                                                </label>
                                                <span><?= $prochaine_action === 'demarrer' ? 'Démarrer le covoiturage' : 'Arrivée à destination' ?></span>
                                            </form>
                                    </span>
                                            <form class="formAnnule" method="POST" action="index.php?page=annuler_covoiturage" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler le trajet ?');">
                                                <input type="hidden" name="trajet_id" value="<?= $trajet['covoiturage_id'] ?>">
                                                <button type="submit" class="btnAnnuler"> ❌ Annuler le trajet </button>
                                            </form>
                                        <?php endif; ?>       
                                </div>
                            <?php endforeach ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class = "trajetResult">
                    <h2 class="titreH2"><i class="fa-solid fa-calendar" style="color: #278b4b;"></i> Mes réservations passager </h2>
                    <hr>
                    <div class = trajet>
                        <h3> <?=count($user_reservations); ?> trajets </h3>
                    </div>
                    
                    <div class = "listeTrajets">
                        <?php if(empty($user_reservations)) : ?>
                            <p> Vous n'avez pas encore participé à un trajet. </p>
                        <?php else: ?>
                            <?php foreach ($user_reservations as $resa) : ?>
                                <div class="historique">
                                    <span class = "detailsTrajet">
                                        <?php 
                                            $date = new DateTime($resa['date_depart']);
                                            $heure = new DateTime($resa['heure_depart']);
                                        ?>
                                        <span class ="date"><?= $date->format('d/m/Y')?></span>
                                        <span class ="lieu depart_arrivee"><i class="fa-solid fa-car fa-lg" style="color: #148b4b;"></i> <?= ($resa['lieu_depart'])?> ---> <?=($resa ['lieu_arrivee'])?></span>
                                        <span class ="prixTrajet"><?= ($resa['prix_personne']) ?> € </span>
                                        <span class ="chauffeur"><i class="fa-regular fa-user fa-sm" style="color: #148b41;"></i><strong>Conducteur :</strong><?=ucfirst($resa['first_name'])?> <?=strtoupper($resa['last_name']) ?> </span>
                                    </span>
                                    <br>
                                    <?php if ($resa['statut'] !== 'terminé') : ?>
                                        <form method="POST" class="formAnnule" action="index.php?page=annuler_covoiturage" onsubmit="return confirm('Êtes-vous sûr d\'annuler votre participation ?');">
                                            <input type="hidden" name="reservation_id" value="<?= $resa['reservation_id'] ?>">
                                            <button type="submit" class="btnAnnuler"> Annuler ma participation</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="statutTermine"> <i class="fa-solid fa-check fa-lg" style="color: #148b4b;"></i> Trajet terminé </span>
    
                                        <?php if (empty($resa['avis_id'])): ?>
                                            <form method="POST" action="index.php?page=avis_trajet" class="formAvis">
                                                <input type="hidden" name="covoiturage_id" value="<?= $resa['covoiturage_id'] ?>">
                                                <input type="hidden" name="reviewed_user_id" value="<?= $resa['conducteur_id'] ?>">
                                                <input type="hidden" name="statut" value="en attente">

                                                <label>Le trajet s'est-il bien passé ?</label>
                                                <label><input type="radio" name="bien_passe" value="oui" required> Oui</label>
                                                <label><input type="radio" name="bien_passe" value="non" required> Non</label>

                                                <label>Note :</label>
                                                <div class ="notation" > 
                                                    <?php for ($i=5; $i>=1; $i--): ?>
                                                    <input type="radio" id="star<?= $i ?>" name="note" value="<?= $i ?>" required>
                                                    <label for="star<?= $i ?>"></label>
                                                    <?php endfor; ?>
                                                </div>

                                            <label>Commentaire :</label>
                                                <textarea name="commentaire" placeholder="Votre avis sur ce trajet..." rows="3"></textarea>

                                                <br><button type="submit" class="btnAvis">Envoyer</button>
                                            </form>
                                        <?php endif; ?>

                                    <?php endif; ?>                         
                                </div>
                            <?php endforeach ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php 
    require_once 'templates/footer.html';
    ?>
    <script src="asset/JS/btn_login.js"></script> 
</body>