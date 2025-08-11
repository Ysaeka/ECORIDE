<body>
    <?php

    use PHPMailer\PHPMailer\PHPMailer;

    require_once 'templates/header.php';
    require_once 'libs/envoi_mail.php';

    ?>

    <div class = "imgSlogan">
      <p>Solution économique pour les voyageurs soucieux de l’environnement.</p>
    </div>

    <div id="nousContacter"><h2> NOUS CONTACTER </h2></div>
        <form method ="POST" action="" id= formContact novalidate>

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

            <button type="submit" name="soumettre" value="soumettre" id=btnContact> SOUMETTRE </button>
        </form>
        <div id="champs"><p>*Champs obligatoires</P></div>

    <?php

        $message = filter_input(INPUT_POST, "message", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $envoiMail = filter_input(INPUT_POST, "email", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $btnEnvoyer = filter_input(INPUT_POST, "soumettre", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
               
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $last_name = htmlspecialchars($_POST['last_name']);
            $message = htmlspecialchars($_POST['message']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $first_name = htmlspecialchars($_POST['first_name']);
            $subject = htmlspecialchars($_POST['subject']);
                
            if (empty($last_name) || empty($email) || empty($message)) {
                echo '<p>Veuillez remplir les champs obligatoires.</p>';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo "Votre adresse mail est invalide.";
            }

           if (isset($btnEnvoyer) && $btnEnvoyer === "soumettre"){
                $mail = new PHPMailer(true);

                   $content = "
                        <p><strong>Nom :</strong> $last_name</p>
                        <p><strong>Prénom :</strong> $first_name</p>
                        <p><strong>Email :</strong> $email</p>
                        <p><strong>Sujet :</strong> $subject</p>
                        <p><strong>Message :</strong><br>$message</p>
                    ";
                EnvoieMail($mail, $envoiMail, $content, $subject);
           }
        }
    ?>
    
   <?php
    require_once 'templates/footer.html'
    ?>
    <script src="asset/JS/btn_login.js"></script>
</body>

</html>