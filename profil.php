<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');

if(isset($_GET['users_id']) AND $_GET['users_id'] > 0){
    $getId = intval($_GET['users_id']);
    $reqUser = $bdd -> prepare('SELECT * FROM users WHERE users_id = ?');
    $reqUser-> execute([$getId]);
    $userInfo = $reqUser -> fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/CSS/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Happy+Monkey&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <title>Mon profil</title>
</head>
<body>
    <?php
    include 'html/header.html'
    ?>
   
    <div class ="profil">
        <div class = "userInfo">
            <a href= "photo profil" id="photoProfil"><img src="asset/images/icone_photo_150.png"></a>
            <p> <?php echo $userInfo['last_name'] . " " . $userInfo['first_name']?></p>
            <p> <?php echo $userInfo['adresse'] ?></p>
            <p> <?php echo $userInfo['email']?></p>
            <p> <?php echo $userInfo['phone_number']?></p>
            <p> Crédit en cours : <!--</p> <?php echo $userInfo['credit']?></p>-->
        </div>
        <hr>
    
        <div class="trajets"> 
            <a href="mes_trajets.php"> Mes trajets </a>
        </div></br>
   
        <div class="compte">
            <a href = "#"> Crediter mon compte </a></br></br>
            <a href ="modif_profil.php"> Modifier mes informations personnelles </a>

        </div>

        <div class ="type">
                <p> Je suis :<p>
                <label for="chauffeur"> Chauffeur </label>
                <input type="radio" id="chauffeur" name="type" checked />
                <label for="passager"> Passager </label>
                <input type="radio" id="passager" name="type" checked />
                <label for="deux"> Les deux </label>
                <input type="radio" id="deux" name="type" checked />
        </div>

        <div class="dropdownCars">
            <button class="carsBtn" onclick="toggleCarsList()"> Ajouter un vehicule</button>

            <div class="carsForm" id="dropdownCarsList">
                <form method ="POST" action="" novalidate>

                <label for="marque"> Marque :  </label>
                <input type="text" name="marque" id="marque" placeholder="Ex: Renault" required>

                <label for="modèle"> Modèle :  </label>
                <input type="text" name="modele" id="modele" placeholder="Ex: Clio">

                <label for="couleur"> Couleur*: </label>
                <input type="text" name="couleur" id="couleur" placeholder="Ex: Rouge" required>

                <label for="energie"> 100% Electrique </label> <!--A changer-->
                <input type="radio" name="carburant" id="energie"required>
                <label for="hybride"> Hybride </label>
                <input type="radio" name="carburant" id="carburant"required>

                <label for="numPlaque"> Numéro de la plaque d'immatriculation : </label>
                <input type="text" name="numPlaque" id="numPlaque" placeholder="Ex: AB-123-CD">

                <label for="datePlaque"> Date de première immatriculation : </label>
                <input type="date" name="datePlaque" id="datePlaque">
            
                <button type="submit" id="btnEnregistrer"> Enregistrer le vehicule </button>
                </form>  
            </div>
        </div>
        </br>
        <div class="preferVoyage">
            <fieldset>
                <legend>Préférence de voyage :</legend>
    
            <div>
                <label for="Cigarette">Cigarette</label>
                <input type="checkbox" id="cigarette" name="cigarette" />
            </div>

            <div>
                <label for="Animaux">Animaux</label>
                <input type="checkbox" id="animaux" name="animaux" />
            </div>
            </fieldset>
        </div>
   
    </div>

    <script src="asset/JS/btn_login.js"></script> <!--Faire un bouton deconnexion-->
    <?php
    include 'html/footer.html'
    ?>
</body>


<?php
} else {
        echo "<p>Utilisateur introuvable.</p>";
}
?>

