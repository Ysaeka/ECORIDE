<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/CSS/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Happy+Monkey&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <title>Proposer votre trajet</title>
</head>
<body>
    <?php
    include 'html/header.html'
    ?>

    <div class = "imgSlogan">
      <p>Solution économique pour les voyageurs soucieux de l’environnement.</p>
    </div>

    <div id="proposerTrajet">
        <h2> Proposer un trajet </h2>
        <form method ="POST" action="" id="formTrajet" novalidate>

            <label for="ville_depart"> Ville de départ :  </label>
            <input type="text" name="ville_depart" id="ville_depart" placeholder="Entrez la ville de départ"/>

            <label for="Ville_arrivee"> Ville de d'arrivée :  </label>
            <input type="text" name="ville_arrivee" id="ville_arrivee" placeholder="Entrez la ville d'arrivée"/>

            <label for="date"> Date :  </label>
            <input type="date" name="date" id="date" placeholder="Date de départ" required/>

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

            <button type="submit"> SOUMETTRE </button>
        </form>
        <div id="champs"><p>*Champs obligatoires</P></div>
    </div>


    <script src="asset/JS/btn_login.js"></script>

       <?php
    include 'html/footer.html'
    ?>
    
</body>

</html>