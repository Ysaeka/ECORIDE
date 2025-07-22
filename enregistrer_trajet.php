<?php
session_start();

require_once 'libs/bdd.php';
require_once 'templates/villes.php';

if(!isset($_SESSION['users_id'])) {
    header("Location: connexion.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ville_depart = $_POST['ville_depart'] ?? '';
    $ville_arrivee = $_POST['ville_arrivee'] ?? '';
    $date_depart = $_POST['date_depart'] ?? '';
    $heure_depart = $_POST['heure_depart'] ?? '';
    $date_arrivee = null;
    $heure_arrivee = $_POST['heure_arrivee'];
    $places = $_POST['places'] ?? 1;
    $prix = $_POST['prix'] ?? 0;
    $trajetEco = isset($_POST['VoyageEco']) ? 1 : 0;
    $details = $_POST['details'] ?? '';
    $voiture_id = $_POST['voiture_id'] ?? null;
    $conducteur_id = $_SESSION['users_id'];

    if (empty($ville_depart) || empty($ville_arrivee) || empty($date_depart) || empty($heure_depart) || $places < 1 || $places > 7 || empty($prix)){
        echo "Veuillez remplir les champs obligatoires.";
        exit;
    }

    $ville_depart_ok = recupVilleCache($ville_depart);
    if (!$ville_depart_ok) {
        echo "Ville de départ introuvable.";
        exit;
    }

    $ville_arrivee_ok = recupVilleCache($ville_arrivee);
    if (!$ville_arrivee_ok) {
        echo "Ville d'arrivée introuvable.";
        exit;
    }

    $verifVoiture = $bdd->prepare('SELECT voiture_id FROM voiture WHERE voiture_id = ? AND users_id = ?');
    $verifVoiture-> execute([$voiture_id, $conducteur_id]);

    if (!$verifVoiture->fetch()) {
        echo "Vehicule invalide.";
        exit();
    }

    $reqInsertion = $bdd->prepare("INSERT INTO covoiturage (date_depart, heure_depart, lieu_depart, date_arrivee, heure_arrivee,lieu_arrivee, nb_place, prix_personne, voiture_id, conducteur_id, trajet_Ecologique, Details)
                                    VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $insertion = $reqInsertion->execute([
        $date_depart, $heure_depart, $ville_depart_ok, $date_arrivee, $heure_arrivee, $ville_arrivee_ok, $places, $prix, $voiture_id, $conducteur_id, $trajetEco, $details]);

    if ($insertion) {
        header("Location: mes_trajets.php");
        exit;
    }else{
        echo "Erreur lors de l'enregistrement du trajet";
    }
}
?>