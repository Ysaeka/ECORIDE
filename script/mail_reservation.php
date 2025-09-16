<?php
require_once __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../libs/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function envoyerMailReservation($passager, $conducteur, $trajet) {
    global $mailServer, $mailUser, $mailPassword;
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $mailServer;
        $mail->SMTPAuth   = true;
        $mail->Username   = $mailUser;
        $mail->Password   = $mailPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('contactus.ecoride@gmail.com', 'Ecoride');
        $mail->isHTML(true);

        $mail->addAddress($passager['email'], $passager['first_name']);
        $mail->Subject = "Confirmation de réservation - Trajet Ecoride";
        $mail->Body = "
        <html>
        <body>
            <div class='mailContainer'>
                <p>Bonjour {$passager['first_name']},</p>
                <p>Votre réservation pour le trajet Ecoride a été confirmée.</p>
                <p><strong>Trajet :</strong></p>
                <ul>
                    <li>Date : {$trajet['date']}</li>
                    <li>Départ : {$trajet['depart']}</li>
                    <li>Arrivée : {$trajet['arrivee']}</li>
                    <li>Prix : {$trajet['prix']}€</li>
                </ul>
                <p><strong>Conducteur :</strong> {$conducteur['first_name']} {$conducteur['last_name']}</p>
                <p>Téléphone : {$conducteur['tel']}<br>Email : {$conducteur['email']}</p>
                <p>Bon voyage !</p>
                <br>
                <p>L'équipe Ecoride</p>
            </div>
        </body>
        </html>";
        $mail->send();
        $mail->clearAllRecipients();
        $mail->addAddress($conducteur['email'], $conducteur['first_name']);
        $mail->Subject = "Nouveau passager pour votre trajet Ecoride";
        $mail->Body = "
        <html>
        <body>
            <div class='mailContainer'>
                <p>Bonjour {$conducteur['first_name']},</p>
                <p>Un passager a réservé une place pour votre trajet Ecoride.</p>
                <p><strong>Trajet :</strong></p>
                <ul>
                    <li>Date : {$trajet['date']}</li>
                    <li>Départ : {$trajet['depart']}</li>
                    <li>Arrivée : {$trajet['arrivee']}</li>
                    <li>Prix : {$trajet['prix']}€</li>
                </ul>
                <p><strong>Passager :</strong> {$passager['first_name']} {$passager['last_name']}</p>
                <p>Téléphone : {$passager['tel']}<br>Email : {$passager['email']}</p>
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
?>