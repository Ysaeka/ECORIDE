<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');

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
            if (password_verify($passwordConnect, $userInfo['password'])) {
                $_SESSION['users_id'] = $userInfo['users_id'];
                $_SESSION['email'] = $userInfo['email'];
                header("Location: profil.php?users_id=" .$_SESSION['users_id']);
                return;
            } else {
                echo "<script>alert('Mot de passe incorrect !')</script>";
            }
        } else {
            echo "<script>alert('Aucun compte trouvé avec cet email !')</script>";
        }
    } else {
        echo "<script>alert('Veuillez remplir tous les champs !')</script>";
    }
}
?>


<body>
    <?php
    require_once 'html/header.html'
    ?>

        <div class="seConnecter">
            <h2> CONNECTEZ-VOUS </h2>
            <form method ="POST" action ="" id="formConnect">
                <label for="email"> Email* : </label>
                <input type="email" name="emailConnect" placeholder="Entrer votre e-mail" id="email" required/>

                <label for="password"> Mot de passe* : </label>
                <input type="password" name="passwordConnect"placeholder="Entrer votre mot de passe" id= "password" required/>

                <input type="submit" name="formConnexion" value="Se connecter" id="btnConnexion">
                <a href=""> J'ai oublié mon mot de passe </a>
                <P> *champs obligatoires</p>
            </form>
        </div>

   <script src="asset/JS/btn_login.js"></script>
   <?php
    require_once 'html/footer.html'
    ?>
    
</body>
</html>