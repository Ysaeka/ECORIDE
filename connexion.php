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

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
  <link rel="stylesheet" href="asset/CSS/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Happy+Monkey&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    include 'html/header.html'
    ?>

        <div id="seConnecter">
            <h2> CONNECTEZ-VOUS </h2>
            <form method ="POST" action ="" id="formConnect">
                <label for="email"> Email* : </label>
                <input type="email" name="emailConnect" placeholder="Entrer votre e-mail" id="email" required/>

                <label for="password"> Mot de passe* : </label>
                <input type="password" name="passwordConnect"placeholder="Entrer votre mot de passe" id= "password" required/>

                <input type="submit" name="formConnexion" value="Se connecter" id="btnConnexion">
                <p> J'ai oublié mon mot de passe <p>
                <P> *champs obligatoires</p>
            </form>
        </div>

   <script src="asset/JS/btn_login.js"></script>
   <?php
    include 'html/footer.html'
    ?>
    
</body>
</html>