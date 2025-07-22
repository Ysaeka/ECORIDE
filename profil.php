<?php
require_once 'libs/auth_users.php';
require_once 'libs/bdd.php';
require_once 'templates/header.html';
?>

<body>
    <div class ="profil">
        <div class = "userInfo">
            <a href= "photo profil" id="photoProfil"><img src="asset/images/icone_photo_150.png"></a>
            <p> <?php echo $userInfo['last_name'] . " " . $userInfo['first_name']?></p>
            <p> <a href="modif_profil.php"> Adresse : </a> <?php echo $userInfo['adresse'] ?></p>
            <p> <?php echo $userInfo['email']?></p>
            <p> <a href="modif_profil.php"> Téléphone : </a><?php echo $userInfo['phone_number']?></p>
            <p> <?php include 'notes_conducteur.php';?> </P>
            <p> Crédit en cours : <?php echo $userInfo['Credit']?> € </p>
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
        <div class = "soumettreTrajet">
            <a href = "proposer_trajet.php"> Proposer un trajet </a>
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
                        <th> Modifier/supprimer le vehicule </th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($voitures): ?>
                    <?php foreach ($voitures as $voiture): ?>
                        <tr data-id ="<?= $voiture['voiture_id'] ?>">
                            <td class="marque"> <?php echo ($voiture['marque']); ?></td>
                            <td class="modele"> <?php echo ($voiture['modele']); ?></td>
                            <td class="couleur"> <?php echo ($voiture['couleur']); ?> </td>
                            <td class="energie"> <?php echo ($voiture['energie']); ?> </td>
                            <td class="immatriculation"> <?php echo ($voiture['immatriculation']); ?> </td>
                            <td class="datePlaque"> <?php echo ($voiture['date_premiere_immatriculation']); ?> </td>
                            <td> 
                                <button class="btnModifier"> Modifier </button> <button class="btnSupprimer" data-id="<?= $voiture['voiture_id'] ?>"> Supprimer </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </thead>
            </table>
        </div>
        <br>
        <div class="dropdownCars">
            <button class="carsBtn" onclick="toggleCarsList()"> Ajouter un vehicule</button>

            <div class="carsForm" id="dropdownCarsList">
                <form method ="POST" action="ajouter_vehicule.php" novalidate>

                <label for="marque"> Marque :  </label>
                <input type="text" name="marque" id="marque" placeholder="Ex: Renault" required>

                <label for="modele"> Modèle :  </label>
                <input type="text" name="modele" id="modele" placeholder="Ex: Clio">

                <label for="couleur"> Couleur: </label>
                <input type="text" name="couleur" id="couleur" placeholder="Ex: Rouge" required>

                <label>
                <input type="radio" name="energie" value="Electrique"> 100% Électrique
                </label>
                <label>
                <input type="radio" name="energie" value="Hybride"> Hybride
                </label>
                <label>
                    <input type="radio" name="energie" value="Diesel"> Essence
                </label>
                  <label>
                    <input type="radio" name="energie" value="Essence"> Diesel
                </label>

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
                <legend>J'accepte de voyager avec  :</legend>
    
            <div>
                <label for="Cigarette">Fumeur</label>
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
    <script src="asset/JS/btn_modif_voiture.js"></script>
    <?php
    require_once 'templates/footer.html'
    ?>
</body>

