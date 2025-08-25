<?php
require_once "libs/auth_users.php";
require_once "libs/bdd.php";
require_once "templates/header.php";

$users_id = $_SESSION['users_id'];

$user_trajets = [];
try {
    $recuptrajet = $bdd->prepare("SELECT c.covoiturage_id, c.date_depart, c.heure_depart, c.lieu_depart, c.lieu_arrivee, c.prix_personne, c.statut FROM covoiturage c 
                           JOIN voiture v ON c.voiture_id = v.voiture_id WHERE c.conducteur_id = ? ORDER BY c.date_depart DESC, c.heure_depart DESC");
    $recuptrajet->execute([$users_id]);
    $user_trajets = $recuptrajet->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e){
    echo "Erreur lors du chargement des données" .$e->getMessage();
}

$user_reservations = [];
try {
    $recupResa = $bdd->prepare("SELECT r.reservation_id, c.covoiturage_id, c.date_depart, c.heure_depart, c.lieu_depart, c.lieu_arrivee, c.prix_personne, c.statut, u.users_id AS conducteur_id, u.first_name, u.last_name, a.avis_id FROM reservation r JOIN covoiturage c ON r.covoiturage_id = c.covoiturage_id 
        JOIN users u ON c.conducteur_id = u.users_id WHERE r.passager_id = ? ORDER BY c.date_depart DESC, c.heure_depart DESC");
    $recupResa->execute([$users_id]);
    $user_reservations = $recupResa->fetchAll(PDO::FETCH_ASSOC);   
} catch (PDOException $e){
    echo "Erreur lors du chargement des réservations : " .$e->getMessage();
}
?>
    <section class="mesTrajets">
        <div class = "containerTrajet">
            <div class = "trajetResult">
                <h2> Mes Trajets proposés </h2>
                <hr>
                <div class = "trajet">
                    <h3> DATE </h3>
                    <h3> <?=count($user_trajets); ?> trajets </h3>
                </div>

                <?php if(empty($user_trajets)) : ?>
                    <p> Vous n'avez pas encore proposé de trajet. </p>
                    <p><a href="proposer_trajet.php"> Proposer un nouveau trajet </a></p>
                <?php else: ?>
                    <?php foreach ($user_trajets as $trajet) : ?>
                        <div class="historique">
                            <span class = "detailsTrajet">
                                    <?php 
                                        $date = new DateTime($trajet['date_depart']);
                                        $heure = new DateTime($trajet['heure_depart']);
                                    ?>
                                    <span class ="date"> <?= $date->format('d/m/Y')?></span>
                                    <span class ="lieu depart_arrivee"> <?= ($trajet['lieu_depart'])?> ---> <?=($trajet ['lieu_arrivee'])?></span>
                                    <span class ="prixTrajet"><?= ($trajet['prix_personne']) ?> € </span>

                                <?php if ($trajet['statut'] === 'terminé') : ?>
                                    <span class="statutTermine"> ✅ Trajet terminé </span>
                                <?php elseif ($trajet['statut'] === 'annulé') : ?>
                                    <span class="statutAnnule"> 🚫 Trajet annulé </span>
                                <?php else: ?>
                                    <form method="POST" action="statut_covoiturage.php" class="formStatut">
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
                                    <form class="formAnnule" method="POST" action="annuler_covoiturage.php" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler le trajet ?');">
                                        <input type="hidden" name="trajet_id" value="<?= $trajet['covoiturage_id'] ?>">
                                        <button type="submit" class="btnAnnuler"> ❌ Annuler le trajet </button>
                                    </form>
                                <?php endif; ?>       
                        </div>
                    <?php endforeach ?>
                <?php endif; ?>
            </div>

            <div class = "trajetResult">
                <h2> Mes réservations passager </h2>
                <hr>
                <div class = resa>
                    <h3> DATE </h3>
                    <h3> <?=count($user_reservations); ?> trajets </h3>
                </div>

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
                                <span class ="date"> <?= $date->format('d/m/Y')?></span>
                                <span class ="lieu depart_arrivee"> <?= ($resa['lieu_depart'])?> ---> <?=($resa ['lieu_arrivee'])?></span>
                                <span class ="prixTrajet"><?= ($resa['prix_personne']) ?> € </span>
                                <span class ="chauffeur"> Conducteur : <?=ucfirst($resa['first_name'])?> <?=strtoupper($resa['last_name']) ?> </span>
                            </span>
                            <br>
                            <?php if ($resa['statut'] !== 'terminé') : ?>
                                <form method="POST" class="formAnnule" action="annuler_covoiturage.php" onsubmit="return confirm('Êtes-vous sûr d\'annuler votre participation ?');">
                                    <input type="hidden" name="reservation_id" value="<?= $resa['reservation_id'] ?>">
                                    <button type="submit" class="btnAnnuler"> Annuler ma participation</button>
                                </form>
                            <?php else: ?>
                                <span class="statutTermine"> ✅ Trajet terminé </span>
                                <?php if (empty($resa['avis_id'])): ?>
                                    <form method="POST" action="avis_trajet.php" class="formAvis">
                                        <input type="hidden" name="covoiturage_id" value="<?= $resa['covoiturage_id'] ?>">
                                        <input type="hidden" name="reviewed_user_id" value="<?= $resa['conducteur_id'] ?>">
                                        <input type="hidden" name="statut" value="en attente">

                                        <label>Le trajet s'est-il bien passé ?</label>
                                        <label><input type="radio" name="bien_passe" value="oui" required> Oui</label>
                                        <label><input type="radio" name="bien_passe" value="non" required> Non</label>

                                        <div id="raisonContainer" style="display:none;">
                                            <label>Sinon, pourquoi ?</label><br>
                                            <textarea name="raison" placeholder="Expliquez nous ce qu'il c'est passé..." rows="3"></textarea>
                                        </div>

                                        <label>Note :</label>
                                        <div class ="notation" > 
                                            <?php for ($i=1; $i<=5; $i++): ?>
                                            <input type="radio" id="star<?= $i ?>" name="note" value="<?= $i ?>" required>
                                            <label for="star<?= $i ?>"></label>
                                            <?php endfor; ?>
                                        </div>

                                    <label>Commentaire :</label>
                                        <textarea name="commentaire" placeholder="Votre avis sur ce trajet..." rows="3"></textarea>

                                        <br><button type="submit" class="btnAvis">Envoyer</button>
                                    </form>
                                <?php endif; ?>

                                <script>
                                    document.querySelectorAll('input[name="bien_passe"]').forEach(el => {
                                        el.addEventListener('change', function() {
                                            document.getElementById('raisonContainer').style.display =
                                                (this.value === 'non') ? 'block' : 'none';
                                        });
                                    });
                                </script>
                            <?php endif; ?>                         
                        </div>
                    <?php endforeach ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?php 
require_once 'templates/footer.html';
?>
<script src="asset/JS/btn_login.js"></script> 
</body>