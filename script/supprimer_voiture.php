<?php
require_once './libs/bdd.php';
require_once './libs/auth_users.php';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['voiture_id'])) {
    $voiture_id = intval($_POST['voiture_id']);
    $users_id = $_SESSION['users_id'];

    $supp_voiture = $bdd->prepare("DELETE FROM voitures WHERE voiture_id = :id AND users_id = :users_id");
    $supp_voiture->execute(['id' => $voiture_id, 'users_id' => $users_id]);

    echo $supp_voiture ? 'Ok' : 'ECHEC';
    exit ();
}

?>
