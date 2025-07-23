<?php
session_start();
require_once "libs/bdd.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trajet_id'], $_SESSION['users_id'])) {
    $trajet_id = intval($_POST['trajet_id']);
    $conducteur_id = $_SESSION['users_id'];

    try {
        $verifTrajet = $bdd->prepare("SELECT * FROM covoiturage WHERE covoiturage_id = ? AND conducteur_id = ?");
        $verifTrajet->execute([$trajet_id, $conducteur_id]);

        $trajet = $verifTrajet->fetch(PDO::FETCH_ASSOC);

        if (!$trajet) {
            die("Trajet non trouvé.");
        }

        $recupParticipant = $bdd->prepare("SELECT r.users_id, u.email FROM reservation r JOIN users u ON r.users_id = u.users_id WHERE r.covoiturage_id = ?");
        $recupParticipant->execute([$trajet_id]);

        $participants = $recupParticipant->fetchAll(PDO::FETCH_ASSOC);

        foreach ($participants as $participant) {
            $user_id = $participant['users_id'];
            $email = $participant['email'];

            $remboursement = $bdd->prepare("UPDATE users SET Credit = Credit + ? WHERE users_id = ? ");
            $remboursement->execute([$trajet['prix_personne'], $user_id]);

            $date = (new DateTime($trajet['date_depart']))->format('d/m/Y');
            $lieu_depart = htmlspecialchars($trajet['lieu_depart']);
            $lieu_arrivee = htmlspecialchars($trajet['lieu_arrivee']);

            $headers = "MIME-Version 1.0\r\n";
            $headers .= "Content-type: text/html; charset-UTF-8\r\n";
            $headers .= "From: contact@ecoride.com\r\n";
            $headers .= "Reply-To: contact@ecoride.com\r\n";

            $message = "
                <html>
                <body>
                    <h2> Annulation de votre covoiturage </h2>
                    <p>Bonjour,</p>
                    <p>Le covoiturage prévu de <strong>$lieu_depart</strong> à <strong>$lieu_arrivee</strong> le <strong>$date</strong> a été annulé par le conducteur.</p>
                    <p>Votre crédit a été automatiquement remboursé.</p>
                    <p>Merci de votre compréhension, <br> L'équipe Ecoride.</p>
                </body>
                </html>
            ";

            mail($email, 'Annulation de votre covoiturage' , $message, $headers);
        }

        $update = $bdd->prepare("UPDATE covoiturage SET statut = 'annulé' WHERE covoiturage_id = ?");
        $update->execute([$trajet_id]);

        $delete = $bdd->prepare("DELETE FROM reservation WHERE covoiturage_id = ?");
        $delete->execute([$trajet_id]);

        header("Location: mes_trajets.php?annulation=success");
        exit();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Requête invalide.";
}