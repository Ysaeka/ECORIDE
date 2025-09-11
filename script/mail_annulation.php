<?php
require_once __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../libs/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function envoyerMailAnnulationGroupe($participants, $trajet) {
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
        $mail->Subject = "Annulation de votre trajet Ecoride";

        $mail->Body = "
        <html>
        <body>
            <div class = 'mailContainer'>
                <p> Bonjour, </p>
                <p>Nous sommes désolés de vous informer que votre trajet Ecoride a été annulé par le conducteur.</p><br>
                <p> Details du trajet : </p>
                <ul>
                    <li><strong>Date :</strong> {$trajet['date']}</li>
                    <li><strong>Départ :</strong> {$trajet['depart']}</li>
                    <li><strong>Arrivée :</strong> {$trajet['arrivee']}</li>
                </ul>
                <p>Le montant de <strong>{$trajet['prix']}€</strong> a été recrédité sur votre compte Ecoride.</p>
                <br>
                <p>Merci de votre compréhension.</p>
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

        