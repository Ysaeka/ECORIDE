<?php
require_once 'libs/bdd.php';
require_once 'libs/auth_users.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $trajet_id = $_POST['trajet_id'] ?? null;
        $action = $_POST['action'] ?? null;

        if($trajet_id && in_array($action, ['demarrer', 'terminer'])) {
            $nouveau_statut = $action === 'demarrer' ? 'en_cours' : 'terminé';

            try {
                $update = $bdd->prepare("UPDATE covoiturage SET statut = ? WHERE covoiturage_id = ?");
                $update->execute([$nouveau_statut, $trajet_id]);

                header("Location: mes_trajets.php"); 
                exit();
            } catch (PDOException $e) {
                echo "Erreur lors du changement de statut :" . $e->getMessage();
            }
        }
    }
?>
