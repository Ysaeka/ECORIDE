<?php
session_start();

require_once "libs/bdd.php";
require_once 'templates/header.html';

if(isset($_GET['users_id']) AND $_GET['users_id'] > 0){
    $getId = intval($_GET['users_id']);
    $reqUser = $bdd -> prepare('SELECT * FROM users WHERE users_id = ?');
    $reqUser-> execute([$getId]);
    $userInfo = $reqUser -> fetch();
?>

<body>
   
    <div class ="profil">
        <div class = "userInfo">
            <a href= "photo profil" id="photoProfil"><img src="asset/images/icone_photo_150.png"></a>
            <p> <?php echo $userInfo['last_name'] . " " . $userInfo['first_name']?></p>
            <p> <?php echo $userInfo['adresse'] ?></p>
            <p> <?php echo $userInfo['email']?></p>
            <p> <?php echo $userInfo['phone_number']?></p>
            <p> Crédit en cours : <?php echo $userInfo['Credit']?>
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
                <div Class="mesVoitures">
            <p> Mes vehicules : </p>

            <table class = "tableauVoiture">
                <thead>
                    <tr>
                        <th> Marque </th>
                        <th> Modèle </th>
                        <th> Couleur </th>
                        <th> Energie </th>
                        <th> Numéro de la plaque </th>
                        <th> Date de la 1ere immatriculation </th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td> Marque </td>
                        <td> Modèle </td>
                        <td> Couleur </td>
                        <td> Energie </td>
                        <td> Numéro de la plaque </td>
                        <td> Date de la 1ere immatriculation </td>
                    </tr>
                </thead>
            </table>
        </div>
        <br>
        <div class="dropdownCars">
            <button class="carsBtn" onclick="toggleCarsList()"> Ajouter un vehicule</button>

            <div class="carsForm" id="dropdownCarsList">
                <form method ="POST" action="" novalidate>

                <label for="marque"> Marque :  </label>
                <input type="text" name="marque" id="marque" placeholder="Ex: Renault" required>

                <label for="modèle"> Modèle :  </label>
                <input type="text" name="modele" id="modele" placeholder="Ex: Clio">

                <label for="couleur"> Couleur: </label>
                <input type="text" name="couleur" id="couleur" placeholder="Ex: Rouge" required>

                <label for="energie"> Energie : </label> 
                <input type="radio" name="energie" required> 100% Electrique
                <input type="radio" name="energie" value="hybride"required> Hybride
                <input type="radio" name="energie" value="essence ou diesel"required> Essence ou Diesel

                <label for="numPlaque"> Numéro de la plaque d'immatriculation : </label>
                <input type="text" name="numPlaque" id="numPlaque" placeholder="Ex: AB-123-CD">

                <label for="datePlaque"> Date de première immatriculation : </label>
                <input type="date" name="datePlaque" id="datePlaque">
            
                <button type="submit" id="btnEnregistrer" name="submit"> Enregistrer le vehicule </button>
                </form>  
            </div>
        </div>
        <br>
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

    <script src="asset/JS/btn_login.js"></script> 
    <?php
    require_once 'templates/footer.html'
    ?>
</body>


<?php
} else {
        echo "<p>Utilisateur introuvable.</p>";
}
?>

