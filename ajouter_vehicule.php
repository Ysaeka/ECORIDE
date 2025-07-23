<?php
session_start();

require_once "libs/bdd.php";

if(!isset($_SESSION['users_id'])) {
    header("Location: connexion.php");
    exit;
}

$userId = $_SESSION ['users_id'];
$marque = htmlspecialchars($_POST['marque']);
$modele = htmlspecialchars($_POST['modele']);
$couleur = htmlspecialchars($_POST['couleur']);
$numPlaque = htmlspecialchars($_POST['numPlaque']);
$datePlaque = $_POST['datePlaque'];


if(isset($_POST['submit']) && !empty($marque) && !empty($modele) && !empty($couleur) && !empty($numPlaque) && !empty($datePlaque)){

$verifMarque = $bdd->prepare("SELECT marque_id  FROM marque WHERE libelle = ?");
$verifMarque->execute([$marque]);
$marqueData = $verifMarque->fetch();

if($marqueData) {
    $marque_id = $marqueData['marque_id'];
}else{
    $insertionMarque = $bdd->prepare("INSERT INTO marque (libelle) VALUES (?)");
    $insertionMarque->execute([$marque]);
    $marque_id = $bdd->lastInsertID();
}

$insertionVoiture = $bdd->prepare("INSERT INTO voiture (modele, immatriculation, couleur, date_premiere_immatriculation, marque_id, users_id) VALUES (?, ?, ?, ?, ?, ?)");
$insertionVoiture->execute([$modele, $numPlaque, $couleur, $datePlaque, $marque_id, $userId]);

header("Location: profil.php");
exit;

}else{
    echo "Veuillez remplir tous les champs.";
}
?>
    
    
    
