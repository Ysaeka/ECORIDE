<?php
require_once __DIR__ . '/../libs/bdd.php';
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__. '/../libs/mongo.php';

$avisList = getAllAvis();

?>

<body>
    <main>
        <div class= "avisRecus">
            <h2> Gestion des avis utilisateurs </h2>
            <table class="tableauAvis">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Trajet</th>
                        <th>Conducteur</th>
                        <th>Passager</th>
                        <th>Bien passé</th>
                        <th>Note</th>
                        <th>Commentaire</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <?php foreach ($avisList as $avis): ?>
                    <?php $statut = isset($avis['statut']) ? trim(strtolower($avis['statut'])) : 'en attente';?>
                        <tr>
                            <td><?= htmlspecialchars($avis['date_creation'] ?? '') ?></td>
                            <td><?= htmlspecialchars($avis['covoiturage_id'] ?? '') ?></td>
                            <td><?= htmlspecialchars($avis['conducteur_nom'] ?? '') ?><br><?= htmlspecialchars($avis['infos_sql']['conducteur_email'] ?? '') ?> </td>
                            <td><?= htmlspecialchars($avis['passager_nom'] ?? '') ?><br><?= htmlspecialchars($avis['infos_sql']['passager_email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($avis['bien_passe'] ?? '') ?></td>
                            <td><?= htmlspecialchars($avis['note'] ?? '') ?></td>
                            <td><?= htmlspecialchars($avis['commentaire'] ?? '') ?></td>
                            <td><?= htmlspecialchars($avis['statut'] ?? '') ?></td>
                            <td>
                                <?php if (($avis['statut'] ?? '') === 'en attente'): ?>
                                    <?php if (($avis['bien_passe'] ?? '') === 'non'): ?>
                                        <i class="fa-solid fa-triangle-exclamation" style="color: #d9534f;" title="Trajet signalé"></i>
                                    <?php endif; ?>
                                    <a href="index.php?page=valider_avis&id=<?= urlencode($avis['_id']) ?>">Valider</a> |
                                    <a href="index.php?page=refuser_avis&id=<?= urlencode($avis['_id']) ?>">Refuser</a>
                                <?php else: ?>
                                    Traité
                                <?php endif; ?>
                            </td>
                        </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </main>
   
    <script src="asset/JS/btn_login.js"></script>

   <?php
    require_once 'templates/footer.html'
    ?>
</body>