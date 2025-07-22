<?php
    session_start();

    require_once 'templates/header.html';
    require_once 'libs/bdd.php';
    require_once 'templates/villes.php';

    $islog = isset($_SESSION['users_id']);
?>

<body>

    <?php if (!$islog) : ?>

        <!-- Si le visiteur n'est pas connecté on affiche la page suivante -->

        <section class = "visiteur">
            <div class = "imgSlogan">
                <p>Solution économique pour les voyageurs soucieux de l’environnement.</p>
            </div>
            <h2> Nous vous invitons à vous connecter à votre espace pour proposer un trajet :</h2>
            <div class ="linkVisiteur">
                    <a class="visiteurConn" href = "connexion.php"> Connectez-vous ici ! </a>
                    <br>
                    <a href = "inscription.php"> Vous n'avez pas de compte ? </a>
            </div>
        </section>

    <?php else : ?>

        <!-- Si le visiteur est pas connecté on affiche la page suivante -->

        <div class = "imgSlogan">
        <p>Solution économique pour les voyageurs soucieux de l’environnement.</p>
        </div>

        <div id="proposerTrajet">
            <h2> Proposer un trajet </h2>
            <form method ="POST" action="enregistrer_trajet.php" id="formTrajet" novalidate>

                <label for="ville_depart"> Ville de départ* :  </label>
                <input type="text" name="ville_depart" id="ville_depart" placeholder="Entrez la ville de départ"/>

                <label for="Ville_arrivee"> Ville de d'arrivée* :  </label>
                <input type="text" name="ville_arrivee" id="ville_arrivee" placeholder="Entrez la ville d'arrivée"/>

                <label for="date"> Date* :  </label>
                <input type="date" name="date_depart" id="date" placeholder="Date de départ" required/>

                <label for="heure_depart"> Heure de départ* :  </label>
                <input type="time" name="heure_depart" id="heure_depart"/>

                <label for="heure_depart"> Heure de d'arrivée* :  </label>
                <input type="time" name="heure_arrivee" id="heure_arrivee"/>

                <label for="places"> Nombre de places disponibles (1-7)* : </label>
                <input type="number" name="places" id="places" min="1" max="7" required>

                <label for="prix"> Prix en €/ personne* :  </label>
                <input type="number" name="prix" id="prix" />

                <label for="VoyageEco"> Voyage écologique (Vehicule 100% electrique)* : </label>
                <input type="checkbox" name="VoyageEco" id="voyageEco" />

                <label for="details"> Details du trajet* :  </label>
                <textarea name="details" id="details" placeholder="Lieu de rendez-vous..." required></textarea>

                <label for="voiture_id"> Choisir un vehicule* </label>
                <select name= "voiture_id" id="voiture_id" required>
                    <?php 
                        $userId = $_SESSION['users_id'];
                        $stmt = $bdd->prepare("SELECT voiture.voiture_id, voiture.modele, voiture.immatriculation, marque.libelle AS marque FROM voiture JOIN marque ON voiture.marque_id = marque.marque_id WHERE voiture.users_id = :userId");
                        $stmt->execute(['userId' => $userId]);

                        $voitures = $stmt-> fetchAll();

                        if (count($voitures) > 0) {
                            foreach ($voitures as $voiture) {
                                echo '<option value = "' . ($voiture['voiture_id']) . '"> '.($voiture['marque']) .' '.($voiture['modele']) . '</option>';
                            }
                            }else{
                                echo '<option value =""> Aucun véhicule disponible </option>';
                            }
                    ?>
                </select>

                <button type="submit" id="valider"> SOUMETTRE </button>
                <div id="champs"><p>*Champs obligatoires</P></div>
            </form>
        </div>
    <?php endif; ?>

    <script src="asset/JS/btn_login.js"></script>

<?php
require_once 'templates/footer.html'
?>

</body>
</html>