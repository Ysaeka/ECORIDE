<?php
if (session_status() === PHP_SESSION_NONE) {
        session_start();
}

require_once './libs/bdd.php';
require_once "mail_annulation.php";

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

        $recupParticipant = $bdd->prepare("SELECT r.passager_id, u.email, u.first_name FROM reservation r JOIN users u ON r.passager_id = u.users_id WHERE r.covoiturage_id = ?");
        $recupParticipant->execute([$trajet_id]);

        $participants = $recupParticipant->fetchAll(PDO::FETCH_ASSOC);

        if ($participants){
            foreach ($participants as $participant) {
                $remboursement = $bdd->prepare("UPDATE users SET Credit = Credit + ? WHERE users_id = ? ");
                $remboursement->execute([$trajet['prix_personne'], $participant['passager_id']]);
            }

            $trajetInfo = [
                'date' => (new DateTime($trajet['date_depart']))->format('d/m/Y'),
                'depart' => htmlspecialchars($trajet['lieu_depart']),
                'arrivee' => htmlspecialchars($trajet['lieu_arrivee']),
                'prix'=> $trajet['prix_personne']
            ];

            envoyerMailAnnulationGroupe($participants, $trajetInfo);
        }

        $update = $bdd->prepare("UPDATE covoiturage SET statut = 'annulé' WHERE covoiturage_id = ?");
        $update->execute([$trajet_id]);

        $delete = $bdd->prepare("DELETE FROM reservation WHERE covoiturage_id = ?");
        $delete->execute([$trajet_id]);

        header("Location: index.php?page=mes_trajets&annulation=success");
        exit();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo $_SERVER['REQUEST_METHOD'];
    var_dump($_POST);
    var_dump($_SESSION);
    echo "Requête invalide.";
}