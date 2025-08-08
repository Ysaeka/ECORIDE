<?php

require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

function EnvoieMail($mail, $envoiMail, $content){

    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';                     
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'contactus.ecoride@gmail.com';                     
        $mail->Password   = 'oykmevrcjyoaeppl';                               
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
        $mail->Port       = 465;    
        
        $mail->Debugoutput = 'html';

        $mail->setFrom('contactus.ecoride@gmail.com', 'Email test');
        $mail->addAddress('contactus.ecoride@gmail.com', 'Ecoride Contact');  

    
        $mail->isHTML(true);                                
        $mail->Subject = 'Contact Ecoride ';
        $mail->Body    = '<!DOCTYPE html> <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Ecoride</title>
            
        </head>
        <body>

            <div class = "mailContainer">
                <h1> Mail envoyé avec succès : <h1>
                <p> Essai de mail <p>
                <div class="formContact">'. $content .'</div>
            </div>
        
        </body>
        </html>';
        
        $mail->AltBody = '';

        $mail->send();
        echo 'Le message a bien été envoyé.';
    } catch (Exception $e) {
        echo "Message non envoyé. Mailer Error: {$mail->ErrorInfo}";
    }
}