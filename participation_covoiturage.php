<?php
require_once 'templates/header.php';
require_once 'libs/bdd.php';
require_once 'libs/auth_users.php';
require_once 'libs/notes_conducteur.php';

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['covoiturage_id'])) {
    $covoiturage_id = $_POST['covoiturage_id'];
    $users_id = $_SESSION['users_id'];

    $recupTrajet = $bdd->prepare("SELECT nb_place, prix_personne FROM covoiturage WHERE covoiturage_id = ?");
    $recupTrajet->execute([$covoiturage_id]);
    $trajet = $recupTrajet->fetch(PDO::FETCH_ASSOC);

    if (!$trajet || $trajet['nb_place'] <= 0) {
        header("Location: reservation.php?id=$covoiturage_id&erreur=plus_de_place");
        exit();
    }

    $prix = $trajet['prix_personne'];

    $recupCredit = $bdd->prepare("SELECT credit FROM users WHERE users_id = ?");
    $recupCredit->execute([$users_id]);
    $credit = $recupCredit->fetchColumn();

    if ($credit < $prix) {
        header("Location: reservation.php?id=$covoiturage_id&erreur=credit_insuffisant");
        exit();
    }

    $bdd->beginTransaction();
    try {
        $bdd->prepare("UPDATE users SET credit = credit - ? WHERE users_id = ?")->execute([$prix, $users_id]);

        $bdd->prepare("UPDATE covoiturage SET nb_place = nb_place - 1 WHERE covoiturage_id = ?")->execute([$covoiturage_id]);

        $bdd->prepare("INSERT INTO reservation (covoiturage_id, passager_id, nb_places_reservees) VALUES (?, ?, 1)")
            ->execute([$covoiturage_id, $users_id]);

        $bdd->commit();
        header("Location: mes_trajets.php?message=reservation_ok");
        exit();
    } catch (Exception $e) {
        $bdd->rollBack();
        echo "Erreur lors de la réservation : " .$e->getMessage();
    }
}

