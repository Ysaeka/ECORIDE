<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');

if(isset($_SESSION['users_id'])){
        $recupUser = $bdd->prepare('SELECT * FROM users WHERE users_id = ?');
        $recupUser->execute([$_SESSION['users_id']]);
        $user = $recupUser->fetch();

        if(isset($_POST['valider'])){
            $last_name = htmlspecialchars($_POST['last_name']);
            $first_name = htmlspecialchars($_POST['first_name']);
            $adresse = htmlspecialchars($_POST['adresse']);
            $phone_number = htmlspecialchars($_POST['phone_number']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password= $_POST['password'];

            if(!empty($password)){
                $password_hashed = password_hash($password, PASSWORD_DEFAULT);
                
                $update = $bdd->prepare('UPDATE users SET last_name = ?, first_name = ?, adresse = ?, phone_number = ?, email = ?, password = ? WHERE users_id = ?');
                $update->execute([$last_name, $first_name, $adresse, $phone_number, $email, $password_hashed, $_SESSION['users_id']]);
            }else{
                $update = $bdd->prepare('UPDATE users SET last_name = ?, first_name = ?, adresse = ?, phone_number = ?, email = ? WHERE users_id = ?');
                $update->execute([$last_name, $first_name, $adresse, $phone_number, $email, $_SESSION['users_id']]);
            }
            
            header("Location: profil.php?users_id=" .$_SESSION['users_id']);
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

<?php
include "html/header.html"
?>
<body>
    <h2> Modifier mes informations </h2>
        <form method ="POST" action ="" id="formInscription">

            <label for="last_name"> Nom :  </label>
            <input type="text" name="last_name" placeholder="Votre nom" value=<?php echo $user['last_name'];?>>

            <label for="first_name"> Prenom :  </label>
            <input type="text" name="first_name" placeholder="Votre prenom" value=<?php echo $user['first_name'];?>>

            <label for="adresse"> Adresse :  </label>
            <input type="text" name="adresse" placeholder="Votre adresse" value=<?php echo $user['adresse'];?>/>

            <label for="phone_number"> Ajouter un numéro de téléphone :  </label>
            <input type="tel" name="phone_number" placeholder="Votre numéro de téléphone" pattern="[0-9]{2}[0-9]{2}[0-9]{2}[0-9]{2}[0-9]{2}" value=<?php echo $user['phone_number'];?>/>

            <label for="email"> Modifier mon email : </label>
            <input type="email" name="email" placeholder="Votre e-mail" value=<?php echo $user['email'];?>>

            <label for ="password"> Modifier mon mot de passe </label>
            <input type="password" name="password"placeholder="Mot de passe" minlength="8"/>

            <p> Le mot de passe doit comporter au moins 8 caractères dont 1 majuscule, 1 chiffre et 1 caractère spécial.</p>

            <input type="submit" name="valider" value="Mettre à jour mes informations" id="btnInscription"/>
        </form>

        <script src = "asset/JS/btn_login.js"></script>
</body>


<?php
}else{
    header("Location: connexion.php");
}
?>