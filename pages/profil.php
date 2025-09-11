<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../libs/bdd.php';
require_once __DIR__ . '/../script/notes_conducteur.php';
require_once __DIR__ . '/../libs/auth_users.php';

?>

<body>
    <main>
        <div class="profil">
            <div class="userInfo">
                <!-- Formulaire upload photo -->
                <form id="uploadForm" action="/TESTECF/script/upload_photo.php" method="POST" enctype="multipart/form-data">
                    <label for="photoInput">
                        <img src="<?= htmlspecialchars($userInfo['photo'] ?? 'asset/images/icone_photo_150.png') ?>" alt="Photo de profil" id="photoPreview">
                    </label>
                    <input type="file" name="photo" id="photoInput" accept="image/*" style="display:none">
                </form>

                <script>
                    document.getElementById('photoInput').addEventListener('change', function() {
                        document.getElementById('uploadForm').submit();
                    });
                </script>

                <p><strong><?= htmlspecialchars($userInfo['last_name'] . " " . $userInfo['first_name']) ?></strong></p>
                <p><a href="index.php?page=modif_profil"><strong>Adresse :</strong></a> <?= htmlspecialchars($userInfo['adresse']) ?></p>
                <p><a href="index.php?page=modif_profil"><strong>E-mail :</strong></a> <?= htmlspecialchars($userInfo['email']) ?></p>
                <p><a href="index.php?page=modif_profil"><strong>Téléphone :</strong></a> <?= htmlspecialchars($userInfo['phone_number']) ?></p>
                <p><?= afficherNote($userInfo['users_id']); ?></p>
                <p><strong>Crédit en cours : <?= htmlspecialchars($userInfo['Credit']) ?> € </strong></p>
            </div>
            <hr>

            <div class="trajets">
                <a href="index.php?page=mes_trajets"><i class="fa-solid fa-car-side fa-lg" style="color: #148b4b;"></i>Mes trajets</a>
            </div>
            <br>

            <div class="compte">
                <a href="#"><i class="fa-solid fa-credit-card fa-lg" style="color: #148b4b;"></i> Créditer mon compte</a><br><br>
                <a href="index.php?page=modif_profil"><i class="fa-solid fa-pencil fa-lg" style="color: #148b4b;"></i> Modifier mes informations personnelles</a>
            </div>

            <div class="type" data-user-type="<?= htmlspecialchars($userInfo['user_type'] ?? 'passager') ?>">
                <p><strong>Je suis :</strong></p>
                <label for="chauffeur">Chauffeur</label>
                <input type="radio" id="chauffeur" name="type" value="chauffeur" />
                <label for="passager">Passager</label>
                <input type="radio" id="passager" name="type" value="passager" />
                <label for="deux">Les deux</label>
                <input type="radio" id="deux" name="type" value="les_deux"/>
            </div>
            </br>

            <div id ="menuChauffeur">
                <div class="soumettreTrajet">
                    <a href="index.php?page=proposer_trajet"><i class="fa-solid fa-circle-plus" style="color: #148b4b;"></i> Proposer un trajet</a>
                </div>

                <div class="mesVoitures">
                    <p><strong>Mes véhicules :</p></strong>
                    <table class="tableauVoiture">
                        <thead>
                            <tr>
                                <th>Marque</th>
                                <th>Modèle</th>
                                <th>Couleur</th>
                                <th>Numéro de la plaque</th>
                                <th>Date de la 1ere immatriculation</th>
                                <th>Energie</th>
                                <th>Modifier/Supprimer le véhicule</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($voitures): ?>
                                <?php foreach ($voitures as $voiture): ?>
                                    <tr data-id="<?= $voiture['voiture_id'] ?>">
                                        <td class="marque"><?= htmlspecialchars($voiture['marque']) ?></td>
                                        <td class="modele"><?= htmlspecialchars($voiture['modele']) ?></td>
                                        <td class="couleur"><?= htmlspecialchars($voiture['couleur']) ?></td>
                                        <td class="immatriculation"><?= htmlspecialchars($voiture['immatriculation']) ?></td>
                                        <td class="datePlaque"><?= htmlspecialchars($voiture['date_premiere_immatriculation']) ?></td>
                                        <td class="energie"><?= htmlspecialchars($voiture['energie']) ?></td>
                                        <td>
                                            <button class="btnModifier">Modifier</button>
                                            <button class="btnSupprimer" data-id="<?= $voiture['voiture_id'] ?>">Supprimer</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <br>

                <div class="dropdownCars">
                    <button class="carsBtn" onclick="toggleCarsList()">
                    <i class="fa-solid fa-car fa-sm" style="color: #ffffff;"></i>Ajouter un véhicule</button>
                    <div class="carsForm" id="dropdownCarsList">
                        <form method="POST" action="script/ajouter_vehicule.php" novalidate>
                            <label for="marque">Marque :</label>
                            <input type="text" name="marque" id="marque" placeholder="Ex: Renault" required>

                            <label for="modele">Modèle :</label>
                            <input type="text" name="modele" id="modele" placeholder="Ex: Clio">

                            <label for="couleur">Couleur :</label>
                            <input type="text" name="couleur" id="couleur" placeholder="Ex: Rouge" required>

                            <label for="numPlaque">Numéro de la plaque :</label>
                            <input type="text" name="numPlaque" id="numPlaque" placeholder="Ex: AB-123-CD">

                            <label for="datePlaque">Date de première immatriculation :</label>
                            <input type="date" name="datePlaque" id="datePlaque">

                            <label for="energie">Energie :</label>
                            <input type="text" name="energie" id="energie" placeholder="Ex: Essence/Diesel ou Electrique/ Hybride">

                            <button type="submit" id="btnEnregistrer" name="submit">Enregistrer le véhicule</button>
                        </form>
                    </div>
                </div>
            </div>
            <br>
            <div class="preferVoyage">
                <fieldset>
                    <legend>J'accepte de voyager avec :</legend>
                    <div>
                        <label for="cigarette">Fumeur</label>
                        <input type="checkbox" id="cigarette" name="cigarette" />
                    </div>
                    <div>
                        <label for="animaux">Animaux</label>
                        <input type="checkbox" id="animaux" name="animaux" />
                    </div>
                </fieldset>
            </div>
        </div>
    </main>

    <script src="asset/JS/btn_login.js"></script> 
    <script src="asset/JS/btn_modif_voiture.js"></script>
    <script src="asset/JS/users_type.js"></script>

<?php
require_once 'templates/footer.html';
?>
</body>
