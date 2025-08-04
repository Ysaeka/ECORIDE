<?php
require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/libs/bdd.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_employe'])) {
        $nom = $_POST['last_name'];
        $prenom = $_POST['first_name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role_id = 3;

        $creationEmploye = $bdd->prepare("INSERT INTO users (last_name, first_name, email, password, role_id, statut) VALUES (?, ?, ?, ?, ?, 'actif')");
        $creationEmploye->execute([$nom, $prenom, $email, $password, $role_id]);
        $message = "Employé créé avec succès.";
    }
?>

    <div class="formEmploye">
        <h2> Créer un compte employé </h2>
        <form method ="POST" class="formInscription">
            <input type="hidden" name="create_employe" value="1">
            <input type="text" name="last_name" placeholder="Nom" required>
            <input type="text" name="first_name" placeholder="Prénom" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button class ="btnInscription" type="submit">Créer l'employé</button>

            <?php if (isset($message)) : ?>
            <p style="color:green; font-weight:bold;"><?= $message ?></p>
            <?php endif; ?>
        </form>     
    </div>

    <script src="asset/JS/btn_login.js"></script>
    <?php
    require_once 'templates/footer.html'
    ?>
</body>


