<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/../libs/bdd.php';
    require_once __DIR__ . '/../templates/header.php';

    if(isset($_POST['valider'])){
        if(!empty($_POST['last_name']) AND !empty($_POST['first_name']) AND !empty($_POST['password']) AND !empty($_POST['email'])){
            $last_name = htmlspecialchars($_POST['last_name']);
            $first_name = htmlspecialchars($_POST['first_name']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password= $_POST['password'];
            $statut='actif';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo "<script>alert('Votre adresse mail est invalide !')</script>";
            }
            
            $checkUser = $bdd->prepare("SELECT * FROM users WHERE email = ?");
            $checkUser->execute([$email]);
            if ($checkUser->rowCount() > 0) {
                echo "<script>alert('Cet email est déjà utilisé !')</script>";
                exit();
            }
            
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $pattern = "/^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";
            if(!preg_match($pattern, $password)){
                echo "<script>alert('Mot de passe invalide !')</script>";
                exit();
            }

            $insertUser = $bdd->prepare("INSERT INTO users(last_name, first_name, email, password, Credit, statut) VALUES(?,?,?,?, 20,?)");
            $insertUser->execute(array($last_name, $first_name, $email, $password_hashed, $statut));

            $recupUser = $bdd->prepare('SELECT * FROM users WHERE email = ?');
            $recupUser->execute([$email]);
            $user = $recupUser->fetch();

            $_SESSION['users_id']    = $user['users_id'];
            $_SESSION['first_name']  = $user['first_name'];
            $_SESSION['last_name']   = $user['last_name'];
            $_SESSION['email']       = $user['email'];
            $_SESSION['statut']      = $user['statut'];

            if (isset($_SESSION['redirection']) && !empty($_SESSION['redirection'])) {
                $redirectionUrl = $_SESSION['redirection'];
                unset($_SESSION['redirection']);
                header("Location: " . $redirectionUrl);
                exit();
            } else {
                header("Location: index.php?page=profil&users_id=" . $_SESSION['users_id']);
                exit();
            }
        }else{
            echo "<script>alert('Veuillez compléter les champs obligatoires!')</script>";
        }
    }
    ?>

<body>
    <main>
        <div class="inscription">
            <h2> VOUS INSCRIRE </h2>
            <form method ="POST" action ="" class="formInscription">

                <label for="last_name"> Nom* :  </label>
                <input type="text" name="last_name" placeholder="Votre nom" required/>

                <label for="first_name"> Prenom* :  </label>
                <input type="text" name="first_name" placeholder="Votre prenom" required/>

                <label for="email"> Email* : </label>
                <input type="email" name="email" placeholder="Votre e-mail" required/>

                <label for ="password"> Choissisez un mot de passe* </label>
                <input type="password" name="password"placeholder="Mot de passe" minlength="8" required/>

                <p> Le mot de passe doit comporter au moins 8 caractères dont 1 majuscule, 1 chiffre et 1 caractère spécial.</p>

                <input type="submit" name="valider" value="valider" class="btnInscription"/>
                <P> *champs obligatoires</p>
            </form>
        </div>
    </main>

    <script src="asset/JS/btn_login.js"></script> 
    <?php
    require_once 'templates/footer.html'
    ?>
</body>
</html>
