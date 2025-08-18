<?php
require_once 'templates/header.php';
require_once 'libs/bdd.php';
require_once 'libs/auth_users.php';
require_once 'libs/notes_conducteur.php';
require_once 'libs/mail_reservation.php';

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['covoiturage_id'])) {
    $covoiturage_id = $_POST['covoiturage_id'];
    $users_id = $_SESSION['users_id'];

    $recupTrajet = $bdd->prepare("SELECT c.*, u.first_name AS conducteur_first_name, u.last_name AS conducteur_last_name, u.email AS conducteur_email, u.phone_number AS conducteur_tel FROM covoiturage c JOIN users u ON c.conducteur_id = u.users_id WHERE c.covoiturage_id = ?");
    $recupTrajet->execute([$covoiturage_id]);
    $trajet = $recupTrajet->fetch(PDO::FETCH_ASSOC);

    if (!$trajet || $trajet['nb_place'] <= 0) {
        header("Location: reservation.php?id=$covoiturage_id&erreur=plus_de_place");
        exit();
    }

    $prix = $trajet['prix_personne'];

    $recupCredit = $bdd->prepare("SELECT Credit FROM users WHERE users_id = ?");
    $recupCredit->execute([$users_id]);
    $credit = $recupCredit->fetchColumn();

    if ($credit < $prix) {
        header("Location: reservation.php?id=$covoiturage_id&erreur=credit_insuffisant");
        exit();
    }

    $bdd->beginTransaction();
    try {
        $bdd->prepare("UPDATE users SET Credit = Credit - ? WHERE users_id = ?")->execute([$prix, $users_id]);

        $bdd->prepare("UPDATE covoiturage SET nb_place = nb_place - 1 WHERE covoiturage_id = ?")->execute([$covoiturage_id]);

        $bdd->prepare("INSERT INTO reservation (covoiturage_id, passager_id, nb_places_reservees) VALUES (?, ?, 1)")
            ->execute([$covoiturage_id, $users_id]);

        $bdd->commit();

        $recupPassager = $bdd->prepare("SELECT first_name, last_name, email, phone_number AS tel FROM users WHERE users_id = ?");
        $recupPassager->execute([$users_id]);
        $passager = $recupPassager->fetch(PDO::FETCH_ASSOC);

        $conducteur = [
        'first_name' => $trajet['conducteur_first_name'],
        'last_name'  => $trajet['conducteur_last_name'],
        'email'      => $trajet['conducteur_email'],
        'tel'        => $trajet['conducteur_tel']
        ];

        $trajetInfo = [
            'date'    => $trajet['date_depart'] . " à " . $trajet['heure_depart'],
            'depart'  => $trajet['lieu_depart'],
            'arrivee' => $trajet['lieu_arrivee'],
            'prix'    => $trajet['prix_personne']
        ];

        envoyerMailReservation($passager, $conducteur, $trajetInfo);

        header("Location: mes_trajets.php?message=reservation_ok");
        exit();
    } catch (Exception $e) {
        $bdd->rollBack();
        echo "Erreur lors de la réservation : " .$e->getMessage();
    }
}

