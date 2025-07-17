<?php
require_once "libs/auth_users.php";
require_once "libs/bdd.php";
require_once "templates/header.html";

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

?>

<section class="mesTrajets">
    <div class = "containerTrajet">
        <div class = "trajetResult">
            <h2> Mes Trajets </h2>
            <div class = trajet>
                <h3> Historique des trajets </h3>
                <h3> <?=count($user_trajets); ?> trajets </h3>
            </div>

            <?php if(empty($user_trajets)) : ?>
                <p> Vous n'avez pas encore proposé de trajet. </p>
                <p><a href="proposer_trajet.php"> Proposer un nouveau trajet </a>
            <?php else: ?>
                <?php foreach ($user_trajets as $trajet) : ?>
                    <div class="historique">
                        <span class = "detailsTrajet">
                            <?php 
                                $date = new DateTime($trajet['date_depart']);
                                $heure = new DateTime($trajet['heure_depart']);
                            ?>
                            <span class ="date"> Date : <?= $date->format('d/m/Y')?></span>
                            <span class ="horaires"> <?= $heure->format('H\hi') ?></span>
                            <span class ="lieu depart_arrivee"> <?= ($trajet['lieu_depart'])?> ---> <?=($trajet ['lieu_arrivee'])?></span>
                            <span class ="prixTrajet"><?= ($trajet['prix_personne']) ?> € </span>
                        </span>
                        
                        <?php if ($trajet['statut'] !== 'terminé') : ?>
                            <form method="POST" action="statut_covoiturage.php" class="formStatut">
                                <input type="hidden" name="trajet_id" value="<?= $trajet['covoiturage_id'] ?>">

                                <?php
                                    $prochaine_action = 'demarrer';
                                    if ($trajet['statut'] === 'en_cours'){
                                        $prochaine_action = "terminer";
                                    }
                                ?>
                                <input type="hidden" name ="action" value="<?= $prochaine_action ?>">
                                <label class="switch">
                                    <input type ="checkbox" onchange="this.form.submit()" <?=$trajet['statut'] === 'en_cours' ? 'checked' : '' ?>>
                                    <span class="slider"></span>
                                </label>
                                    <span><?= $prochaine_action === 'demarrer' ? 'Démarrer' : 'Terminer'?></span>
                            </form>
                        <?php else: ?>
                            <span class="statutTermine"> ✅ Trajet terminé </span>
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