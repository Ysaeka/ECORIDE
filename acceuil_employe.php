<?php
require_once __DIR__ . '/libs/bdd.php';
require_once __DIR__ . '/templates/header.php';
require_once 'mongo.php';

$avisList = getAllAvis();

?>

<body>
    <div class= "avisRecus">
        <h2> Gestion des avis utilisateurs </h2>
        <table class="tableauAvis">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Trajet</th>
                    <th>Conducteur</th>
                    <th>Passager</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <?php foreach ($avisList as $avis): ?>
                <tr>
                    <td><?= htmlspecialchars($avis['date_creation'] ?? '') ?></td>
                    <td><?= htmlspecialchars($avis['covoiturage_id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($avis['conducteur_nom'] ?? '') ?></td>
                    <td><?= htmlspecialchars($avis['passager_nom'] ?? '') ?></td>
                    <td><?= htmlspecialchars($avis['note'] ?? '') ?></td>
                    <td><?= htmlspecialchars($avis['commentaire'] ?? '') ?></td>
                    <td><?= htmlspecialchars($avis['statut'] ?? 'en attente') ?></td>
                    <td>
                        <a href="valider_avis.php?id=<?= urlencode($avis['_id']) ?>">Valider</a> |
                        <a href="refuser_avis.php?id=<?= urlencode($avis['_id']) ?>">Refuser</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
   

    <script src="asset/JS/btn_login.js"></script>

   <?php
    require_once 'templates/footer.html'
    ?>
</body>