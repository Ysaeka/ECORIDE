<?php
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function envoyerMailAvis($participants, $trajet) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'contactus.ecoride@gmail.com';
        $mail->Password   = 'oykmevrcjyoaeppl';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('contactus.ecoride@gmail.com', 'Ecoride');

        foreach ($participants as $participant) {
            $mail->addAddress($participant ['email'], $participant ['first_name']);
        }

        $mail->isHTML(true);
        $mail->Subject = "Votre trajet Ecoride";

        $mail->Body = "
        <html>
        <body>
            <div class = 'mailContainer'>
                <h1> Bonjour, </h1>
                <p>Nous espérons que votre covoiturage du {$trajet['date_depart']} s’est bien déroulé !</p><br>  
                <p> Pour partager votre expérience, il vous suffit de vous connecter à votre espace : <p><br>
                <p> <a href='http://localhost/ecoride/profil.php'></a> <p> 

                <p> C’est rapide, et ça nous aide à améliorer l’expérience pour tout le monde.<p>
                <br>
                <p>Merci et à très bientôt sur la route !</p>
                <br>
                <p>L'équipe Ecoride</p>
            </div>
        </body>
        </html>";

        $mail->send();
    } catch (Exception $e) {
        echo "Message non envoyé. Mailer Error: {$mail->ErrorInfo}";
    }
}

        