<?php
require_once __DIR__ . '/libs/bdd.php';
require_once __DIR__ . '/templates/header.php';



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
            <?php foreach ($avis as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['date_depart']) ?></td>
                    <td><?= htmlspecialchars($user['reservation_id']) ?></td>
                    <td><?= htmlspecialchars($user['conducteur_id']) ?></td>
                    <td><?= htmlspecialchars($user['passager_id']) ?></td>
                    <td><?= htmlspecialchars($user['note']) ?></td>
                    <td><?= htmlspecialchars($user['commentaire']) ?></td>
                    <td><?= htmlspecialchars($user['statut']) ?></td>
                    <td>
                        <a href="valider_avis.php?id=<?= urlencode($user['reservation_id']) ?>">Valider</a> |
                        <a href="refuser_avis.php?id=<?= urlencode($user['reservation_id']) ?>">Refuser</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
   

    <script src="asset/JS/btn_login.js"></script>
    <script src ="asset/JS/tableau_bord.js" defer></script>
   <?php
    require_once 'templates/footer.html'
    ?>
</body>