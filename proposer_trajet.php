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
            <h2> Pour proposer un trajet nous vous invitons à vous connecter :</h2>
                <a href = "connexion.php"> Connectez-vous ici ! </a>
                <a href = "inscription.php"> Vous n'avez pas de compte ? </a>
        </section>

    <?php else : ?>

        <!-- Si le visiteur est pas connecté on affiche la page suivante -->

        <div class = "imgSlogan">
        <p>Solution économique pour les voyageurs soucieux de l’environnement.</p>
        </div>

        <div id="proposerTrajet">
            <h2> Proposer un trajet </h2>
            <form method ="POST" action="enregistrer_trajet.php" id="formTrajet" novalidate>

                <label for="ville_depart"> Ville de départ :  </label>
                <input type="text" name="ville_depart" id="ville_depart" placeholder="Entrez la ville de départ"/>

                <label for="Ville_arrivee"> Ville de d'arrivée :  </label>
                <input type="text" name="ville_arrivee" id="ville_arrivee" placeholder="Entrez la ville d'arrivée"/>

                <label for="date"> Date :  </label>
                <input type="date" name="date_depart" id="date" placeholder="Date de départ" required/>

                <label for="heure_depart"> Heure de départ :  </label>
                <input type="time" name="heure_depart" id="heure_depart"/>

                <label for="places"> Nombre de places disponibles (1-7) : </label>
                <input type="number" name="places" id="places" min="1" max="7" required>

                <label for="prix"> Prix en €/ personne :  </label>
                <input type="number" name="prix" id="prix" />

                <label for="VoyageEco"> Voyage écologique (Vehicule 100% electrique) : </label>
                <input type="checkbox" name="VoyageEco" id="voyageEco" />

                <label for="details"> Details du trajet :  </label>
                <textarea name="details" id="details" placeholder="Lieu de rendez-vous..." required></textarea>

                <button type="submit" id="valider"> SOUMETTRE </button>
            </form>
            <div id="champs"><p>*Champs obligatoires</P></div>
        </div>

    <?php endif; ?>


    <script src="asset/JS/btn_login.js"></script>

    <?php
    require_once 'templates/footer.html'
    ?>

</body>
</html>