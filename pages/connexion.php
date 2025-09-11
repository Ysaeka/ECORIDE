<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../libs/bdd.php';

    if (isset($_POST['formConnexion'])) {
        $emailConnect = filter_var($_POST['emailConnect'], FILTER_SANITIZE_EMAIL);
        $passwordConnect = $_POST['passwordConnect']; 

        if (!filter_var($emailConnect, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Votre adresse mail est invalide !')</script>";
        } elseif (!empty($emailConnect) && !empty($passwordConnect)) {
            $reqUser = $bdd->prepare("SELECT * FROM users WHERE email = ?");
            $reqUser->execute([$emailConnect]);
            
            if ($reqUser->rowCount() === 1) {
                $userInfo = $reqUser->fetch();

                if ($userInfo['statut'] === 'suspendu') {
                    echo "<script>alert('Votre compte est suspendu. Contactez l’administrateur.')</script>";
                } elseif (password_verify($passwordConnect, $userInfo['password'])) {
                    $_SESSION['users_id'] = $userInfo['users_id'];
                    $_SESSION['role_id'] = $userInfo['role_id'];
                    $_SESSION['email'] = $userInfo['email'];
                    $_SESSION['photo'] = $userInfo['photo'];

                    if (isset($_SESSION['redirection']) && !empty($_SESSION['redirection'])) {
                        $redirectionUrl = $_SESSION['redirection'];
                        unset($_SESSION['redirection']);
                        header("Location: " . $redirectionUrl);
                        exit();
                    } else {
                        header("Location: index.php?page=profil");
                        exit();
                    }

                } else {
                    echo "<script>alert('Email ou Mot de passe incorrect !')</script>";
                }
            } else {
                echo "<script>alert('Identifiants incorrects !')</script>";
            }
        } else {
            echo "<script>alert('Veuillez remplir tous les champs !')</script>";
        }
    }
?>
<?php
    require_once __DIR__ . '/../templates/header.php';
?>

<body>
    <main>
        <div class="seConnecter">
            <h2> CONNECTEZ-VOUS </h2>
            <form method ="POST" action ="" id="formConnect">
                <label for="email"> Email* : </label>
                <input type="email" name="emailConnect" placeholder="Entrer votre e-mail" id="email" required/>
                <label for="password"> Mot de passe* : </label>
                <input type="password" name="passwordConnect"placeholder="Entrer votre mot de passe" id= "password" required/>
                <a href=""> J'ai oublié mon mot de passe </a>
                <input type="submit" name="formConnexion" value="Se connecter" id="btnConnexion">
                <P> *champs obligatoires</p>
                <div class="inscrire"> <p> Pas encore incrit ? </P> <a href="index.php?page=inscription">Inscrivez-vous ici !</a></div>
            </form>
        </div>
    </main>
    
   <script src="asset/JS/btn_login.js"></script>
   <?php
    require_once 'templates/footer.html'
    ?>
    
</body>
</html>