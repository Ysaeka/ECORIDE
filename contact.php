<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/CSS/contact.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Happy+Monkey&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <title>Nous contacter</title>
</head>
<body>
    <?php
    include 'html/header.html'
    ?>

    <div class = "imgSlogan">
      <p>Solution économique pour les voyageurs soucieux de l’environnement.</p>
    </div>

    <div id="nousContacter"><p> NOUS CONTACTER </p></div>
        <form method ="POST" action="" novalidate>

            <label for="last_name"> Nom* :  </label>
            <input type="text" name="last_name" id="last_name" placeholder="Entrez votre nom" required/>

            <label for="first_name"> Prénom :  </label>
            <input type="text" name="first_name" id="first_name" placeholder="Entrez votre prénom"/>

            <label for="email"> Email* : </label>
            <input type="email" name="email" id="email" placeholder="Entrez votre e-mail" required/>

            <label for="subject"> Sujet :  </label>
            <input type="text" name="subject" id="subject" placeholder="Sujet du message"/>

            <label for="message"> Votre message*  </label>
            <textarea name="message" id="message" placeholder="Entrez votre message" required></textarea>

            <button type="submit"> SOUMETTRE </button>
        </form>
    <div id="champs"><p>*Champs obligatoires</P></div>

    
<footer>
    <p> contact@ecoride.com</p>
    <p><a href="#">Mentions légales</a></p>
</footer>
    <?php
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $last_name = htmlspecialchars($_POST['last_name']);
            $message = htmlspecialchars($_POST['message']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                
            if (empty($last_name) || empty($email) || empty($message)) {
                echo '<p>Veuillez remplir les champs obligatoires.</p>';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo "Votre adresse mail est invalide.";
            }

            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'From: contact@ecoride.com' . "\r\n";
            $headers .= 'Reply-To: ' . $email;

            $originMessage = '<h1>Message envoyé depuis la page Contact de Ecoride </h1>
            <p><b> Nom : </b>' . $last_name . '<br>
            <b>Email : </b>' . $email . '<br>
            <b>Message : </b>' . $message . '</p>';
            
            $retour = mail('destinataire@free.fr', 'Envoi depuis page Contact', $originMessage, $headers);
            if($retour) {
                echo "<script>alert('Votre message à bien été envoyé !');</script>";
            }else{
                echo "<script>alert('Une erreur est survenue');</script>";
                }
        }
    ?>

 <script src="asset/JS/btn_login.js"></script>
</body>


</html>