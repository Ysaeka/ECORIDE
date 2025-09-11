<?php
require_once __DIR__ . '/../libs/bdd.php';
require_once __DIR__ . '/../libs/auth_users.php';
require_once __DIR__ . '/mail_avis.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $trajet_id = $_POST['trajet_id'] ?? null;
        $action = $_POST['action'] ?? null;

        if($trajet_id && in_array($action, ['demarrer', 'terminer'])) {
            $nouveau_statut = $action === 'demarrer' ? 'en_cours' : 'terminé';

            try {
                $update = $bdd->prepare("UPDATE covoiturage SET statut = ? WHERE covoiturage_id = ?");
                $update->execute([$nouveau_statut, $trajet_id]);

                if($nouveau_statut === 'terminé'){
                    $recupParticipants = $bdd->prepare("SELECT u.first_name, u.email FROM reservation r JOIN users u ON r.passager_id = u.users_id WHERE r.covoiturage_id = ?");
                    $recupParticipants->execute([$trajet_id]);
                    $participants = $recupParticipants->fetchAll(PDO::FETCH_ASSOC);

                    $recupTrajet = $bdd->prepare("SELECT date_depart FROM covoiturage WHERE covoiturage_id = ?");
                    $recupTrajet->execute([$trajet_id]);
                    $trajet = $recupTrajet->fetch(PDO::FETCH_ASSOC);

                    if (!empty($participants) && $trajet){
                        envoyerMailAvis($participants, $trajet);
                    }
                }

                header("Location: ./index.php?page=mes_trajets"); 
                exit();

            } catch (PDOException $e) {
                echo "Erreur lors du changement de statut :" . $e->getMessage();
            }
        }
    }
?>
