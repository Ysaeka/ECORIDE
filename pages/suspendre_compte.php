<?php
require_once __DIR__ . '/../libs/bdd.php';
require_once __DIR__ . '/../templates/header.php';

$users = $bdd->query("SELECT u.users_id, u.last_name, u.first_name, u.email, IFNULL(u.statut, 'actif') AS statut, r.libelle FROM users u JOIN role r ON u.role_id = r.role_id ORDER BY u.created_at DESC")->fetchAll();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['suspend_user_id'])) {
        $userId = intval($_POST['suspend_user_id']);
        $suspendreCompte = $bdd->prepare("UPDATE users SET statut = 'suspendu' WHERE users_id = ?");
        $suspendreCompte->execute([$userId]);
        header("Location: index.php?page=suspendre_compte&success=suspend");
        exit();
    }

    if (isset($_POST['reactivate_user_id'])){
        $userId = intval($_POST['reactivate_user_id']);
        $reactiverCompte = $bdd->prepare("UPDATE users SET statut = 'actif' WHERE users_id = ?");
        $reactiverCompte->execute([$userId]);
        header("Location: index.php?page=suspendre_compte&success=reactivate");
        exit();
    }

    ?>
<body>
    <main>
        <div class ="gestionUti">
            <h2> Gestion des comptes utilisateurs </h2>
                
                <table class = tableauUsers>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['last_name']) ?></td>
                            <td><?= htmlspecialchars($user['first_name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['libelle']) ?></td>
                            <td><?= htmlspecialchars($user['statut']) ?></td>
                            <td class="actionAdmin">
                                <?php if ($user['statut'] === 'actif'): ?>
                                    <form method="POST">
                                        <input type="hidden" name="suspend_user_id" value="<?= $user['users_id'] ?>">
                                        <button type="submit" class="btnSuspendre">Suspendre le compte</button>
                                    </form>
                                <?php else: ?>
                                    <em>Compte suspendu</em>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="reactivate_user_id" value="<?= $user['users_id'] ?>">
                                        <button type="submit" class="btnReactiver">Réactiver</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
        </div>
    </main>
</body>
</html>